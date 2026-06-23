<?php
$pageTitle  = 'Book & Get a Quote';
$activePage = 'booking';
require_once __DIR__ . '/includes/header.php';
 
$success = false;
$errors  = [];
$bookingRef = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
 
    $name       = clean($_POST['name']       ?? '');
    $email      = sanitizeEmail($_POST['email'] ?? '');
    $phone      = clean($_POST['phone']      ?? '');
    $eventDate  = clean($_POST['event_date'] ?? '');
    $eventType  = clean($_POST['event_type'] ?? '');
    $guests     = (int)($_POST['guests']     ?? 0);
    $decorTheme = clean($_POST['decor_theme']     ?? '');
    $cateringPkg= clean($_POST['catering_package'] ?? '');
    $venue      = clean($_POST['venue']      ?? '');
    $requests   = clean($_POST['special_requests'] ?? '');
    $referralCode = clean($_POST['referral_code'] ?? '');
 
    // Validation
    if (strlen($name) < 2)    $errors[] = 'Please enter your full name.';
    if (!$email)               $errors[] = 'Please enter a valid email address.';
    if (!$eventDate)           $errors[] = 'Please select your event date.';
    elseif (strtotime($eventDate) < strtotime('tomorrow')) $errors[] = 'Event date must be in the future.';
    if (!$eventType)           $errors[] = 'Please select an event type.';
    if ($guests < 1)           $errors[] = 'Please enter the number of guests.';
    if (!isAvailable($eventDate)) $errors[] = 'Sorry, that date is fully booked. Please choose another date.';
 
    if (empty($errors)) {
        $quote = calculateQuote($eventType, $guests, $decorTheme, $cateringPkg);
 
        // Apply referral discount
        $discountAmt = 0;
        if ($referralCode) {
            try {
                $refStmt = db()->prepare('SELECT id FROM clients WHERE referral_code = ?');
                $refStmt->execute([$referralCode]);
                $referrer = $refStmt->fetch();
                if ($referrer) {
                    $discountAmt = $quote['total'] * PRICING['referral_disc'];
                    $quote['total']   -= $discountAmt;
                    $quote['deposit'] = $quote['total'] * PRICING['deposit_pct'];
                    $quote['balance'] = $quote['total'] - $quote['deposit'];
                }
            } catch(Exception $e) {}
        }
 
        // Handle inspiration image upload
        $imgPath = '';
        if (!empty($_FILES['inspiration_img']['name'])) {
            $imgPath = handleUpload('inspiration_img', 'bookings') ?: '';
        }
 
        try {
            $pdo = db();
 
            // Upsert client
            $cStmt = $pdo->prepare('SELECT id FROM clients WHERE email = ?');
            $cStmt->execute([$email]);
            $client = $cStmt->fetch();
 
            if ($client) {
                $clientId = $client['id'];
                $pdo->prepare('UPDATE clients SET name=?, phone=? WHERE id=?')
                    ->execute([$name, $phone, $clientId]);
            } else {
                $refCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/','',$name),0,5)).rand(100,999);
                $pdo->prepare('INSERT INTO clients (name,email,phone,referral_code) VALUES (?,?,?,?)')
                    ->execute([$name, $email, $phone, $refCode]);
                $clientId = (int)$pdo->lastInsertId();
            }
 
            // Save referral
            if ($referralCode && isset($referrer['id'])) {
                try {
                    $pdo->prepare('INSERT INTO referrals (referrer_id, referred_id) VALUES (?,?) ON DUPLICATE KEY UPDATE status=status')
                        ->execute([$referrer['id'], $clientId]);
                } catch(Exception $e) {}
            }
 
            // Create booking
            $pdo->prepare('INSERT INTO bookings (client_id,event_date,event_type,guest_count,decor_theme,catering_package,venue,special_requests,quote_amount,deposit_paid,balance_due,inspiration_img) VALUES (?,?,?,?,?,?,?,?,?,0,?,?)')
                ->execute([$clientId, $eventDate, $eventType, $guests, $decorTheme, $cateringPkg, $venue, $requests, $quote['total'], $quote['total'], $imgPath]);
            $bookingId = (int)$pdo->lastInsertId();
 
            // Award loyalty points
            awardLoyaltyPoints($clientId, $bookingId, $quote['total']);
 
            $bookingRef = 'SEN-' . str_pad($bookingId, 5, '0', STR_PAD_LEFT);
 
            // Confirmation email to client
            $emailBody = "<p>Dear $name,</p>
              <h3 style='color:#d4a017'>Your Booking is Confirmed!</h3>
              <p>Thank you for choosing Senzakwenzeke. Your booking reference is <strong>$bookingRef</strong>.</p>
              <table style='width:100%;border-collapse:collapse;margin:1.5rem 0'>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888;width:40%'>Event Type</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#eee'>$eventType</td></tr>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888'>Event Date</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#eee'>".niceDate($eventDate)."</td></tr>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888'>Guests</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#eee'>$guests</td></tr>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888'>Décor Theme</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#eee'>$decorTheme</td></tr>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888'>Catering</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#eee'>$cateringPkg</td></tr>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888'>Quote Total</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#d4a017;font-size:1.2rem'>".zar($quote['total'])."</td></tr>
                <tr><td style='padding:8px 0;border-bottom:1px solid #333;color:#888'>50% Deposit Due</td><td style='padding:8px 0;border-bottom:1px solid #333;color:#eee'>".zar($quote['deposit'])."</td></tr>
              </table>
              <p>To confirm your booking, please pay the 50% deposit of <strong>".zar($quote['deposit'])."</strong> via EFT:</p>
              <p><strong>Bank:</strong> FNB &nbsp;|&nbsp; <strong>Account:</strong> 123 456 789 &nbsp;|&nbsp; <strong>Reference:</strong> $bookingRef</p>
              <p>Our team will contact you within 48 hours to begin planning the details of your event.</p>
              <p>Warm regards,<br><strong>The Senzakwenzeke Team</strong></p>";
 
            sendEmail($email, "Booking Confirmed — $bookingRef", $emailBody, $name);
 
            // Notify admin
            $adminBody = "<h3>New Booking — $bookingRef</h3>
              <p><strong>Client:</strong> $name ($email, $phone)</p>
              <p><strong>Event:</strong> $eventType on ".niceDate($eventDate)." — $guests guests</p>
              <p><strong>Décor:</strong> $decorTheme | <strong>Catering:</strong> $cateringPkg</p>
              <p><strong>Quote:</strong> ".zar($quote['total'])." | Deposit: ".zar($quote['deposit'])."</p>
              <p><a href='".SITE_URL."/admin/bookings.php'>View in Admin Dashboard →</a></p>";
            sendEmail(ADMIN_EMAIL, "New Booking: $bookingRef — $name", $adminBody);
 
            // Log
            try {
                $pdo->prepare('INSERT INTO communication_log (client_id,booking_id,type,subject,body,status) VALUES (?,?,?,?,?,?)')
                    ->execute([$clientId, $bookingId, 'confirmation', "Booking Confirmed — $bookingRef", $emailBody, 'sent']);
            } catch(Exception $e) {}
 
            $success = true;
 
        } catch(Exception $e) {
            $errors[] = 'There was an error saving your booking. Please try again or contact us directly.';
            error_log($e->getMessage());
        }
    }
}
 
// Pricing for JS
$pricing = json_encode(PRICING);
?>
 
<!-- ── Page Banner ──────────────────────────────────────────── -->
<div class="page-banner">
  <div class="page-banner-content container">
    <div class="breadcrumb">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">♦</span>
      <span class="breadcrumb-current">Book &amp; Quote</span>
    </div>
    <span class="eyebrow">Start Planning</span>
    <h1>Get Your <em>Personalised</em> Quote</h1>
    <p class="lead mt-3" style="max-width:560px;margin:1rem auto 0">Fill in the details below and see your estimated price instantly. No obligation — just clarity.</p>
  </div>
</div>
 
<!-- ── Booking Form ──────────────────────────────────────────── -->
<section class="section">
  <div class="container">
 
    <?php if($success): ?>
    <!-- SUCCESS STATE -->
    <div class="text-center reveal" style="max-width:600px;margin:0 auto;padding:4rem 2rem">
      <div style="width:80px;height:80px;border-radius:50%;background:rgba(76,175,136,.15);border:2px solid var(--color-success);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 2rem;color:var(--color-success)">✓</div>
      <span class="eyebrow">Booking Received</span>
      <h2>Thank You, <?= htmlspecialchars($name) ?>!</h2>
      <div class="gold-rule mt-3"><div class="gold-rule-icon"></div></div>
      <p class="lead">Your booking reference is <strong class="text-gold"><?= $bookingRef ?></strong></p>
      <p class="text-muted">We've sent a detailed confirmation to <strong><?= htmlspecialchars($email) ?></strong>. Our team will be in touch within 48 hours to begin crafting your perfect event.</p>
      <div class="alert alert-warning mt-4" style="text-align:left">
        <strong>Next Step:</strong> Please pay your 50% deposit to confirm your booking. See the email for banking details.
      </div>
      <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
        <a href="index.php"     class="btn btn-outline">Back to Home</a>
        <a href="portfolio.php" class="btn btn-primary">View Our Portfolio</a>
      </div>
    </div>
 
    <?php else: ?>
    <!-- FORM -->
    <div class="row g-5">
      <!-- Main form -->
      <div class="col-lg-8">
        <?php foreach($errors as $err): ?>
        <div class="alert alert-danger mb-2"><i class="fas fa-exclamation-circle me-2"></i><?= $err ?></div>
        <?php endforeach; ?>
 
        <div class="form-card">
          <form method="POST" action="booking.php" enctype="multipart/form-data" novalidate id="bookingForm">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>"/>
 
            <!-- Section 1: Personal Details -->
            <div class="form-section-title"><i class="fas fa-user me-2"></i>Your Details</div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label required">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your full name" required
                       value="<?= isset($name)?htmlspecialchars($name):'' ?>"/>
              </div>
              <div class="form-group">
                <label class="form-label required">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required
                       value="<?= isset($email)?htmlspecialchars($email):'' ?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Phone / WhatsApp</label>
              <input type="tel" name="phone" class="form-control" placeholder="+27 83 000 0000"
                     value="<?= isset($phone)?htmlspecialchars($phone):'' ?>"/>
            </div>
 
            <!-- Section 2: Event Details -->
            <div class="form-section-title mt-4"><i class="fas fa-calendar-alt me-2"></i>Event Details</div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label required">Event Date</label>
                <input type="date" name="event_date" class="form-control" id="eventDate" required
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       value="<?= isset($eventDate)?htmlspecialchars($eventDate):'' ?>"/>
                <div class="form-help" id="availabilityMsg"></div>
              </div>
              <div class="form-group">
                <label class="form-label required">Event Type</label>
                <select name="event_type" class="form-control" id="eventType" required>
                  <option value="">— Select type —</option>
                  <?php foreach(array_keys(PRICING['event_base']) as $et): ?>
                  <option value="<?= $et ?>" <?= isset($eventType)&&$eventType===$et?'selected':'' ?>><?= $et ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label required">Number of Guests</label>
                <input type="number" name="guests" class="form-control" id="guests" placeholder="e.g. 150" min="1" max="2000" required
                       value="<?= isset($guests)&&$guests>0?$guests:'' ?>"/>
              </div>
              <div class="form-group">
                <label class="form-label">Venue / Location</label>
                <input type="text" name="venue" class="form-control" placeholder="Venue name or address"
                       value="<?= isset($venue)?htmlspecialchars($venue):'' ?>"/>
              </div>
            </div>
 
            <!-- Section 3: Package Selection -->
            <div class="form-section-title mt-4"><i class="fas fa-paint-brush me-2"></i>Package Selection</div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Décor Theme</label>
                <select name="decor_theme" class="form-control" id="decorTheme">
                  <option value="">— No décor / TBD —</option>
                  <?php foreach(array_keys(PRICING['decor_theme']) as $dt): ?>
                  <option value="<?= $dt ?>" <?= isset($decorTheme)&&$decorTheme===$dt?'selected':'' ?>><?= $dt ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Catering Package</label>
                <select name="catering_package" class="form-control" id="cateringPkg">
                  <option value="">— No catering / TBD —</option>
                  <?php foreach(array_keys(PRICING['catering_package']) as $cp): ?>
                  <option value="<?= $cp ?>" <?= isset($cateringPkg)&&$cateringPkg===$cp?'selected':'' ?>><?= $cp ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
 
            <!-- Section 4: Extras -->
            <div class="form-section-title mt-4"><i class="fas fa-plus-circle me-2"></i>Additional Information</div>
            <div class="form-group">
              <label class="form-label">Special Requests or Notes</label>
              <textarea name="special_requests" class="form-control" rows="4"
                        placeholder="Any special requirements, colour preferences, dietary needs or ideas you'd like to share…"><?= isset($requests)?htmlspecialchars($requests):'' ?></textarea>
            </div>
 
            <div class="form-group">
              <label class="form-label">Inspiration Image (optional)</label>
              <div class="file-upload">
                <input type="file" name="inspiration_img" accept="image/*" onchange="showFileName(this)"/>
                <div class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="file-upload-label" id="fileLabel">Click or drag to upload an inspiration image<br><small>JPG, PNG, WEBP — Max 5MB</small></div>
              </div>
            </div>
 
            <div class="form-group">
              <label class="form-label">Referral Code <span style="color:var(--color-text-muted);font-size:.7rem">(optional — get 5% discount)</span></label>
              <input type="text" name="referral_code" class="form-control" placeholder="e.g. NANDI001" style="text-transform:uppercase"
                     value="<?= isset($referralCode)?htmlspecialchars($referralCode):'' ?>"/>
              <div class="form-help">Ask a friend who has previously booked with us for their referral code.</div>
            </div>
 
            <div class="form-group">
              <label class="form-check">
                <input type="checkbox" class="form-check-input" required/>
                <span class="form-check-label">I agree to the 50% deposit requirement and <a href="contact.php" class="text-gold">terms and conditions</a></span>
              </label>
            </div>
 
            <button type="submit" class="btn btn-primary btn-lg w-100">
              <i class="fas fa-paper-plane me-2"></i>Submit Booking Request
            </button>
          </form>
        </div>
      </div>
 
      <!-- Sticky quote preview -->
      <div class="col-lg-4">
        <div class="quote-preview">
          <div class="quote-preview-header">
            <i class="fas fa-file-invoice-dollar me-2" style="color:var(--gold-400)"></i>Live Quote Estimate
          </div>
 
          <div class="quote-line">
            <span class="quote-line-label">Event Type</span>
            <span class="quote-line-value" id="qEventType">—</span>
          </div>
          <div class="quote-line">
            <span class="quote-line-label">Base price</span>
            <span class="quote-line-value" id="qBase">R 0.00</span>
          </div>
          <div class="quote-line">
            <span class="quote-line-label">Per-guest (<span id="qGuestCount">0</span> guests)</span>
            <span class="quote-line-value" id="qPerGuest">R 0.00</span>
          </div>
          <div class="quote-line">
            <span class="quote-line-label">Décor theme</span>
            <span class="quote-line-value" id="qDecor">R 0.00</span>
          </div>
          <div class="quote-line">
            <span class="quote-line-label">Catering package</span>
            <span class="quote-line-value" id="qCatering">R 0.00</span>
          </div>
 
          <div class="quote-total">
            <div class="quote-total-label">Estimated Total</div>
            <div class="quote-total-amount" id="qTotal">R 0</div>
          </div>
          <div class="quote-deposit" id="qDepositLine"></div>
 
          <hr style="border-color:var(--color-border);margin:1rem 0"/>
 
          <!-- Availability mini-calendar note -->
          <div style="font-size:.75rem;color:var(--color-text-muted);line-height:1.6">
            <i class="fas fa-info-circle text-gold me-1"></i>
            This is a live estimate. Final pricing confirmed after consultation.
            50% deposit secures your date.
          </div>
 
          <!-- Loyalty points preview -->
          <div class="loyalty-badge mt-3" id="loyaltyPreview" style="display:none">
            <div class="loyalty-badge-icon">⭐</div>
            <div>
              <div class="loyalty-points" id="loyaltyPts">0</div>
              <div class="loyalty-label">Points you'll earn</div>
            </div>
          </div>
 
          <a href="contact.php" class="btn btn-ghost w-100 mt-4" style="font-size:.65rem">
            <i class="fas fa-phone me-2"></i>Prefer to call us?
          </a>
        </div>
 
        <!-- Availability checker -->
        <div class="mini-calendar mt-4">
          <div class="cal-header">
            <span class="cal-title" id="calTitle">Availability</span>
          </div>
          <p style="font-size:.78rem;color:var(--color-text-muted)">Select a date in the form to check availability.</p>
          <div class="cal-legend">
            <div class="cal-legend-item"><div class="cal-legend-dot" style="background:var(--color-success)"></div>Available</div>
            <div class="cal-legend-item"><div class="cal-legend-dot" style="background:var(--color-danger)"></div>Booked</div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
 
  </div>
</section>
 
<script>
const PRICING = <?= $pricing ?>;
 
function fmt(n){ return 'R ' + n.toLocaleString('en-ZA', {minimumFractionDigits:2, maximumFractionDigits:2}); }
 
function updateQuote(){
  const et   = document.getElementById('eventType')?.value   || '';
  const g    = parseInt(document.getElementById('guests')?.value) || 0;
  const dt   = document.getElementById('decorTheme')?.value  || '';
  const cp   = document.getElementById('cateringPkg')?.value || '';
 
  const base    = PRICING.event_base[et]        || 0;
  const perG    = (PRICING.per_guest[et]        || 0) * g;
  const decor   = PRICING.decor_theme[dt]       || 0;
  const catering= PRICING.catering_package[cp]  || 0;
  const total   = base + perG + decor + catering;
  const deposit = total * PRICING.deposit_pct;
  const loyalty = Math.floor((total / 1000) * PRICING.loyalty_rate);
 
  document.getElementById('qEventType').textContent  = et  || '—';
  document.getElementById('qBase').textContent        = fmt(base);
  document.getElementById('qGuestCount').textContent  = g;
  document.getElementById('qPerGuest').textContent    = fmt(perG);
  document.getElementById('qDecor').textContent       = fmt(decor);
  document.getElementById('qCatering').textContent    = fmt(catering);
  document.getElementById('qTotal').textContent       = fmt(total);
 
  const depLine = document.getElementById('qDepositLine');
  if(total > 0){
    depLine.textContent = '50% deposit required: ' + fmt(deposit);
    depLine.style.display = 'block';
  } else {
    depLine.style.display = 'none';
  }
 
  const lp = document.getElementById('loyaltyPreview');
  if(loyalty > 0){
    document.getElementById('loyaltyPts').textContent = loyalty + ' pts';
    lp.style.display = 'flex';
  } else {
    lp.style.display = 'none';
  }
}
 
// Availability check
function checkAvailability(dateVal){
  const msg = document.getElementById('availabilityMsg');
  if(!dateVal){ msg.textContent=''; return; }
  msg.innerHTML = '<span style="color:var(--color-text-muted)">Checking availability…</span>';
  fetch('includes/check_availability.php?date='+encodeURIComponent(dateVal))
    .then(r=>r.json())
    .then(data=>{
      if(data.available){
        msg.innerHTML = '<span style="color:var(--color-success)">✓ This date is available!</span>';
      } else {
        msg.innerHTML = '<span style="color:var(--color-danger)">✕ Sorry, this date is fully booked. Please choose another.</span>';
      }
    }).catch(()=>{ msg.textContent = ''; });
}
 
function showFileName(input){
  const label = document.getElementById('fileLabel');
  if(input.files && input.files[0]){
    label.textContent = '✓ ' + input.files[0].name;
  }
}
 
document.addEventListener('DOMContentLoaded', function(){
  ['eventType','guests','decorTheme','cateringPkg'].forEach(id=>{
    const el = document.getElementById(id);
    if(el) el.addEventListener('input', updateQuote);
  });
  const dateEl = document.getElementById('eventDate');
  if(dateEl){
    dateEl.addEventListener('change', function(){ checkAvailability(this.value); });
  }
  updateQuote();
});
</script>
 
<?php require_once __DIR__ . '/includes/footer.php'; ?>
 
