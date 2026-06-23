<?php
$pageTitle  = 'Services';
$activePage = 'services';
require_once __DIR__ . '/includes/header.php';
?>
 
<!-- ── Page Banner ──────────────────────────────────────────── -->
<div class="page-banner">
  <div class="page-banner-content container">
    <div class="breadcrumb">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">♦</span>
      <span class="breadcrumb-current">Services</span>
    </div>
    <span class="eyebrow">What We Offer</span>
    <h1>Services Crafted for <em>Every Celebration</em></h1>
    <p class="lead mt-3" style="max-width:580px;margin:1rem auto 0">From concept to clean-up, we deliver end-to-end event excellence across KwaZulu-Natal.</p>
  </div>
</div>
 
<!-- ── Weddings ──────────────────────────────────────────────── -->
<section class="section" id="weddings">
  <div class="container">
    <div class="services-split">
      <div class="services-img-wrap reveal">
        <img src="images/ubasi.jpg" alt="Wedding décor" class="services-img"
             onerror="this.src='https://placehold.co/600x750/1c1c1c/d4a017?text=Weddings'"/>
      </div>
      <div class="reveal reveal-delay-1">
        <span class="eyebrow">Service 01</span>
        <h2>Wedding <em>Décor</em></h2>
        <div class="gold-rule"><div class="gold-rule-icon"></div></div>
        <p class="lead">Your wedding day is the most important day of your life. We treat it that way.</p>
        <p>From intimate garden ceremonies to grand ballroom receptions, our wedding styling team creates environments of breathtaking beauty. We specialise in floral installations, draped ceilings, bespoke centrepieces, and full venue transformations.</p>
        <ul style="list-style:none;padding:0;margin:1.5rem 0">
          <?php foreach(['Ceremony arch & backdrop design','Reception table styling & centrepieces','Floral arrangements & bouquets','Fairy light canopies & draping','Chair covers, sashes & linen','Entrance & aisle décor','Welcome signage & stationery styling','Mood lighting design'] as $item): ?>
          <li style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:.5px solid var(--color-border);font-size:.88rem;color:var(--color-text-muted)">
            <span class="text-gold" style="font-size:.6rem">✦</span> <?= $item ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="booking.php" class="btn btn-primary">Request Wedding Quote</a>
      </div>
    </div>
  </div>
</section>
 
<hr class="section-divider"/>
 
<!-- ── Corporate ─────────────────────────────────────────────── -->
<section class="section" id="corporate">
  <div class="container">
    <div class="services-split reverse">
      <div class="services-img-wrap reveal">
        <img src="images/dijo.jpg" alt="Corporate event" class="services-img"
             onerror="this.src='https://placehold.co/600x750/1c1c1c/d4a017?text=Corporate'"/>
      </div>
      <div class="reveal reveal-delay-1">
        <span class="eyebrow">Service 02</span>
        <h2>Corporate <em>Events</em></h2>
        <div class="gold-rule"><div class="gold-rule-icon"></div></div>
        <p class="lead">Make a lasting impression on clients, staff and stakeholders with events that speak of your brand's excellence.</p>
        <p>We partner with businesses across KZN to deliver polished corporate events — from product launches and awards nights to team building and conference styling. Our corporate packages blend professionalism with creativity.</p>
        <ul style="list-style:none;padding:0;margin:1.5rem 0">
          <?php foreach(['Awards gala styling','Product launch environments','Conference & boardroom décor','Branded centrepieces & signage','Cocktail & networking setups','Stage & podium design','Step-and-repeat banners','Branded table settings'] as $item): ?>
          <li style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:.5px solid var(--color-border);font-size:.88rem;color:var(--color-text-muted)">
            <span class="text-gold" style="font-size:.6rem">✦</span> <?= $item ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="booking.php" class="btn btn-primary">Request Corporate Quote</a>
      </div>
    </div>
  </div>
</section>
 
<hr class="section-divider"/>
 
<!-- ── Private ───────────────────────────────────────────────── -->
<section class="section" id="private">
  <div class="container">
    <div class="services-split">
      <div class="services-img-wrap reveal">
        <img src="images/Private_setup.jpg" alt="Private celebration" class="services-img"
             onerror="this.src='https://placehold.co/600x750/1c1c1c/d4a017?text=Private'"/>
      </div>
      <div class="reveal reveal-delay-1">
        <span class="eyebrow">Service 03</span>
        <h2>Private <em>Celebrations</em></h2>
        <div class="gold-rule"><div class="gold-rule-icon"></div></div>
        <p class="lead">Milestones deserve to be celebrated beautifully. Let us make yours unforgettable.</p>
        <p>Whether it's a milestone birthday, an anniversary dinner, a baby shower, or a family reunion, our private event packages are tailored entirely to your personality and vision. No template — pure you.</p>
        <ul style="list-style:none;padding:0;margin:1.5rem 0">
          <?php foreach(['Milestone birthday styling','Anniversary celebrations','Baby & bridal showers','Family reunion setups','Garden party design','Surprise party coordination','Custom theme creation','Grazing & dessert tables'] as $item): ?>
          <li style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:.5px solid var(--color-border);font-size:.88rem;color:var(--color-text-muted)">
            <span class="text-gold" style="font-size:.6rem">✦</span> <?= $item ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="booking.php" class="btn btn-primary">Request Private Quote</a>
      </div>
    </div>
  </div>
</section>
 
<hr class="section-divider"/>
 
<!-- ── Kids ──────────────────────────────────────────────────── -->
<section class="section" id="kids">
  <div class="container">
    <div class="services-split reverse">
      <div class="services-img-wrap reveal">
        <img src="images/kiddies.jpg" alt="Kids party" class="services-img"
             onerror="this.src='https://placehold.co/600x750/1c1c1c/d4a017?text=Kids+Parties'"/>
      </div>
      <div class="reveal reveal-delay-1">
        <span class="eyebrow">Service 04</span>
        <h2>Kids' <em>Parties</em></h2>
        <div class="gold-rule"><div class="gold-rule-icon"></div></div>
        <p class="lead">Every child deserves a magical birthday. We make that magic happen — safely, creatively and joyfully.</p>
        <p>Our kids' party packages bring themes to life with colour, imagination and age-appropriate fun. From princess castles to superhero lairs, enchanted forests to safari adventures — if they can dream it, we can build it.</p>
        <ul style="list-style:none;padding:0;margin:1.5rem 0">
          <?php foreach(['Themed backdrop & balloon setups','Sweet & dessert tables','Custom cake table styling','Character-themed décor','Activity station design','Party favour displays','Photo booth areas','Personalised name signage'] as $item): ?>
          <li style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:.5px solid var(--color-border);font-size:.88rem;color:var(--color-text-muted)">
            <span class="text-gold" style="font-size:.6rem">✦</span> <?= $item ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="booking.php" class="btn btn-primary">Request Kids Party Quote</a>
      </div>
    </div>
  </div>
</section>
 
<hr class="section-divider"/>
 
<!-- ── Catering ──────────────────────────────────────────────── -->
<section class="section" id="catering">
  <div class="container">
    <div class="services-split">
      <div class="services-img-wrap reveal">
        <img src="images/dijo.jpg" alt="Catering" class="services-img"
             onerror="this.src='https://placehold.co/600x750/1c1c1c/d4a017?text=Catering'"/>
      </div>
      <div class="reveal reveal-delay-1">
        <span class="eyebrow">Service 05</span>
        <h2>Catering <em>Packages</em></h2>
        <div class="gold-rule"><div class="gold-rule-icon"></div></div>
        <p class="lead">Food is love made edible. Our menus are crafted to delight every palate and honour every culture.</p>
        <p>Led by head caterer Nomsa Zulu, our culinary team draws inspiration from traditional South African cuisine, modern fusion and international flavours. We source fresh, local produce from the KZN Midlands and tailor every menu to your event and dietary needs.</p>
 
        <!-- Catering packages -->
        <div class="row g-3 mt-2">
          <?php
          $packages = [
            ['name'=>'Premium Buffet',     'price'=>'From R8,000','desc'=>'A lavish self-service spread with multiple stations, ideal for weddings and large celebrations.'],
            ['name'=>'Sit-Down Dinner',    'price'=>'From R10,000','desc'=>'Plated courses served to your guests — elegant, formal and utterly impressive.'],
            ['name'=>'Cocktail & Canapés', 'price'=>'From R5,000', 'desc'=>'Sophisticated finger foods and drinks for networking events, launches and cocktail functions.'],
            ['name'=>'Kids Feast',         'price'=>'From R2,500', 'desc'=>'Child-friendly menu with crowd-pleasing options, allergen-conscious and fun to eat.'],
          ];
          foreach($packages as $pkg): ?>
          <div class="col-6">
            <div class="card-feature p-4">
              <h5 style="font-size:.72rem;letter-spacing:.14em;color:var(--gold-400)"><?= $pkg['name'] ?></h5>
              <div style="font-family:var(--font-display);font-size:1.1rem;color:#fff;margin:.4rem 0"><?= $pkg['price'] ?></div>
              <p style="font-size:.8rem;color:var(--color-text-muted);margin:0"><?= $pkg['desc'] ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="booking.php" class="btn btn-primary mt-4">Request Catering Quote</a>
      </div>
    </div>
  </div>
</section>
 
<!-- ── Pricing Tiers ─────────────────────────────────────────── -->
<section class="section" style="background:var(--black-rich)">
  <div class="container">
    <div class="section-header reveal">
      <span class="eyebrow">Packages</span>
      <h2>Simple, Transparent Pricing</h2>
      <p>All packages are fully customisable. Prices shown are starting guides — your final quote is tailored to your exact needs.</p>
    </div>
    <div class="grid grid-3 mt-4">
      <?php
      $tiers = [
        ['name'=>'Essentials','price'=>'R6,000','desc'=>'Perfect for intimate gatherings','features'=>['Up to 50 guests','Basic theme décor','Table centrepieces','Chair covers & sashes','2-hour setup'],'featured'=>false],
        ['name'=>'Signature', 'price'=>'R15,000','desc'=>'Our most popular package',     'features'=>['Up to 150 guests','Full theme styling','Floral arrangements','Fairy lights & draping','On-day coordinator','Catering optional add-on'],'featured'=>true],
        ['name'=>'Prestige',  'price'=>'R28,000','desc'=>'For truly grand celebrations', 'features'=>['200+ guests','Bespoke design consultation','Premium florals & installations','Full catering package','Dedicated team of 4','Full venue transformation'],'featured'=>false],
      ];
      foreach($tiers as $i => $t): ?>
      <div class="card-pricing <?= $t['featured']?'featured':'' ?> reveal <?= $i>0?'reveal-delay-'.$i:'' ?>">
        <h6><?= $t['name'] ?></h6>
        <div class="price-amount"><?= $t['price'] ?></div>
        <div class="price-period"><?= $t['desc'] ?></div>
        <div class="price-features">
          <?php foreach($t['features'] as $f): ?>
          <div class="price-feature-item"><?= $f ?></div>
          <?php endforeach; ?>
        </div>
        <a href="booking.php" class="btn <?= $t['featured']?'btn-primary':'btn-outline' ?> w-100">Get This Package</a>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="text-center text-muted mt-4" style="font-size:.82rem">* All prices in ZAR. 50% deposit required to confirm booking. Prices exclude travel outside a 60km radius of Bothas Hill.</p>
  </div>
</section>
 
<!-- ── CTA ───────────────────────────────────────────────────── -->
<section class="section-sm text-center">
  <div class="container reveal">
    <h3>Not Sure Which Package Fits?</h3>
    <p class="text-muted mb-4">Use our instant quote calculator to get a personalised estimate in seconds.</p>
    <a href="booking.php" class="btn btn-primary btn-lg">Calculate My Quote</a>
  </div>
</section>
 
<?php require_once __DIR__ . '/includes/footer.php'; ?>