<?php
$pageTitle  = 'Contact Us';
$activePage = 'contact';
require_once __DIR__ . '/includes/header.php';
 
$success = false;
$errors  = [];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name    = clean($_POST['name']    ?? '');
    $email   = sanitizeEmail($_POST['email'] ?? '');
    $phone   = clean($_POST['phone']   ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $message = clean($_POST['message'] ?? '');
 
    if (strlen($name) < 2)      $errors[] = 'Please enter your full name.';
    if (!$email)                 $errors[] = 'Please enter a valid email address.';
    if (strlen($message) < 10)  $errors[] = 'Please enter a message (at least 10 characters).';
 
    if (empty($errors)) {
        $body = "<h3>New Contact Form Submission</h3>
                 <p><strong>Name:</strong> $name</p>
                 <p><strong>Email:</strong> $email</p>
                 <p><strong>Phone:</strong> $phone</p>
                 <p><strong>Subject:</strong> $subject</p>
                 <p><strong>Message:</strong><br>".nl2br($message)."</p>";
 
        sendEmail(ADMIN_EMAIL, "Contact Form: $subject", $body);
 
        // Auto-reply
        $reply = "<p>Dear $name,</p>
                  <p>Thank you for reaching out to Senzakwenzeke! We've received your message and will get back to you within 24 hours.</p>
                  <p>In the meantime, feel free to browse our portfolio at <a href='".SITE_URL."/portfolio.php' style='color:#d4a017'>".SITE_URL."/portfolio.php</a></p>
                  <p>Warm regards,<br><strong>The Senzakwenzeke Team</strong></p>";
        sendEmail($email, 'Thank you for contacting Senzakwenzeke', $reply, $name);
 
        $success = true;
    }
}
?>
 
<!-- ── Page Banner ──────────────────────────────────────────── -->
<div class="page-banner">
  <div class="page-banner-content container">
    <div class="breadcrumb">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">♦</span>
      <span class="breadcrumb-current">Contact</span>
    </div>
    <span class="eyebrow">Get in Touch</span>
    <h1>We'd Love to Hear <em>From You</em></h1>
    <p class="lead mt-3" style="max-width:540px;margin:1rem auto 0">Whether you have a question or are ready to start planning — we're here and happy to help.</p>
  </div>
</div>
 
<!-- ── Contact Section ───────────────────────────────────────── -->
<section class="section">
  <div class="container">
    <div class="row g-5">
 
      <!-- Info column -->
      <div class="col-lg-4 reveal">
        <span class="eyebrow">Contact Information</span>
        <h3 style="margin-bottom:2rem">Reach Us <em>Directly</em></h3>
 
        <div class="contact-info-item">
          <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="contact-info-label">Location</div>
            <div class="contact-info-value">Bothas Hill, KwaZulu-Natal<br>South Africa</div>
          </div>
        </div>
 
        <div class="contact-info-item">
          <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
          <div>
            <div class="contact-info-label">Phone / WhatsApp</div>
            <div class="contact-info-value">
              <a href="tel:+27830000000">+27 83 000 0000</a>
            </div>
          </div>
        </div>
 
        <div class="contact-info-item">
          <div class="contact-icon"><i class="far fa-envelope"></i></div>
          <div>
            <div class="contact-info-label">Email</div>
            <div class="contact-info-value">
              <a href="mailto:info@senzakwenzeke.co.za">info@senzakwenzeke.co.za</a>
            </div>
          </div>
        </div>
 
        <div class="contact-info-item">
          <div class="contact-icon"><i class="fab fa-instagram"></i></div>
          <div>
            <div class="contact-info-label">Instagram</div>
            <div class="contact-info-value">
              <a href="https://instagram.com/senzakwenzeke" target="_blank">@senzakwenzeke</a>
            </div>
          </div>
        </div>
 
        <div class="contact-info-item">
          <div class="contact-icon"><i class="far fa-clock"></i></div>
          <div>
            <div class="contact-info-label">Business Hours</div>
            <div class="contact-info-value">Mon – Sat: 8:00 AM – 6:00 PM<br><span style="color:var(--color-text-muted);font-size:.82rem">Sunday: By appointment only</span></div>
          </div>
        </div>
 
        <!-- Social links -->
        <div class="mt-4">
          <div class="contact-info-label mb-3">Follow Us</div>
          <div class="social-links">
            <a href="https://instagram.com/senzakwenzeke" target="_blank" class="social-link"><i class="fab fa-instagram"></i></a>
            <a href="https://facebook.com/senzakwenzeke"  target="_blank" class="social-link"><i class="fab fa-facebook-f"></i></a>
            <a href="https://wa.me/27830000000"           target="_blank" class="social-link"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.tiktok.com/@senzakwenzeke" target="_blank" class="social-link"><i class="fab fa-tiktok"></i></a>
          </div>
        </div>
      </div>
 
      <!-- Form column -->
      <div class="col-lg-8 reveal reveal-delay-1">
        <?php if($success): ?>
        <div class="alert alert-success mb-4">
          <i class="fas fa-check-circle me-2"></i>
          <div>
            <strong>Message sent!</strong> Thank you <?= htmlspecialchars($name) ?>. We'll be in touch within 24 hours. Check your inbox for a confirmation email.
          </div>
        </div>
        <?php endif; ?>
 
        <?php foreach($errors as $err): ?>
        <div class="alert alert-danger mb-2"><i class="fas fa-exclamation-circle me-2"></i><?= $err ?></div>
        <?php endforeach; ?>
 
        <div class="form-card">
          <div class="form-section-title">Send Us a Message</div>
          <form method="POST" action="contact.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>"/>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label required">Full Name</label>
                <input type="text" name="name" class="form-control"
                       value="<?= isset($name)?htmlspecialchars($name):'' ?>"
                       placeholder="Your full name" required/>
              </div>
              <div class="form-group">
                <label class="form-label required">Email Address</label>
                <input type="email" name="email" class="form-control"
                       value="<?= isset($email)?htmlspecialchars($email):'' ?>"
                       placeholder="your@email.com" required/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control"
                       value="<?= isset($phone)?htmlspecialchars($phone):'' ?>"
                       placeholder="+27 83 000 0000"/>
              </div>
              <div class="form-group">
                <label class="form-label">Subject</label>
                <select name="subject" class="form-control">
                  <option value="General Enquiry">General Enquiry</option>
                  <option value="Wedding Quote">Wedding Quote</option>
                  <option value="Corporate Event">Corporate Event</option>
                  <option value="Private Celebration">Private Celebration</option>
                  <option value="Kids Party">Kids Party</option>
                  <option value="Catering Enquiry">Catering Enquiry</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label required">Message</label>
              <textarea name="message" class="form-control" rows="6"
                        placeholder="Tell us about your event, your date, guest count and any ideas you have…" required><?= isset($message)?htmlspecialchars($message):'' ?></textarea>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane me-2"></i>Send Message
              </button>
              <a href="booking.php" class="btn btn-outline">
                <i class="fas fa-calculator me-2"></i>Get a Quote Instead
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
 
<!-- ── Map placeholder ───────────────────────────────────────── -->
<div style="background:var(--black-rich);border-top:.5px solid var(--color-border);border-bottom:.5px solid var(--color-border);height:320px;display:flex;align-items:center;justify-content:center;text-align:center">
  <div>
    <div style="font-size:3rem;margin-bottom:1rem">📍</div>
    <h4 style="color:var(--gold-400);font-family:var(--font-display)">Bothas Hill, KwaZulu-Natal</h4>
    <p style="color:var(--color-text-muted);font-size:.88rem">We serve clients across the greater KZN region.<br>Travel within 60km of Bothas Hill included in all packages.</p>
    <a href="https://maps.google.com/?q=Bothas+Hill+KwaZulu-Natal" target="_blank" class="btn btn-sm btn-outline mt-3">
      <i class="fas fa-map-marker-alt me-2"></i>View on Google Maps
    </a>
  </div>
</div>
 
<!-- ── Quick CTA ─────────────────────────────────────────────── -->
<section class="section-sm text-center">
  <div class="container reveal">
    <h3>Prefer to Get a Quote Directly?</h3>
    <p class="text-muted mb-4">Use our booking form for an instant price estimate.</p>
    <a href="booking.php" class="btn btn-primary btn-lg">Get Your Free Quote</a>
  </div>
</section>
 
<?php require_once __DIR__ . '/includes/footer.php'; ?>