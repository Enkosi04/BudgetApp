<?php
$pageTitle  = 'Portfolio';
$activePage = 'portfolio';
require_once __DIR__ . '/includes/header.php';
 
$category = isset($_GET['cat']) ? clean($_GET['cat']) : 'all';
$allowed  = ['all','weddings','corporate','private','kids'];
if (!in_array($category, $allowed)) $category = 'all';
 
try {
    if ($category === 'all') {
        $items = db()->query('SELECT * FROM portfolio ORDER BY sort_order, id')->fetchAll();
    } else {
        $stmt = db()->prepare('SELECT * FROM portfolio WHERE category = ? ORDER BY sort_order, id');
        $stmt->execute([$category]);
        $items = $stmt->fetchAll();
    }
} catch (Exception $e) {
    $items = [];
}
?>
 
<!-- ── Page Banner ──────────────────────────────────────────── -->
<div class="page-banner">
  <div class="page-banner-content container">
    <div class="breadcrumb">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">♦</span>
      <span class="breadcrumb-current">Portfolio</span>
    </div>
    <span class="eyebrow">Our Work</span>
    <h1>A Gallery of <em>Beautiful Moments</em></h1>
    <p class="lead mt-3" style="max-width:580px;margin:1rem auto 0">Browse our portfolio of weddings, corporate events, private celebrations and kids' parties across KwaZulu-Natal.</p>
  </div>
</div>
 
<!-- ── Gallery ───────────────────────────────────────────────── -->
<section class="section">
  <div class="container">
 
    <!-- Filter buttons -->
    <div class="gallery-filter reveal">
      <?php
      $cats = ['all'=>'All Events','weddings'=>'Weddings','corporate'=>'Corporate','private'=>'Private','kids'=>"Kids' Parties"];
      foreach($cats as $val => $label): ?>
      <a href="portfolio.php?cat=<?= $val ?>"
         class="filter-btn <?= $category===$val?'active':'' ?>">
        <?= $label ?>
      </a>
      <?php endforeach; ?>
    </div>
 
    <!-- Gallery grid -->
    <?php if (!empty($items)): ?>
    <div class="gallery-grid" id="galleryGrid">
      <?php foreach($items as $i => $item): ?>
      <div class="gallery-item reveal <?= $i>0?'reveal-delay-'.min($i%4,4):'' ?>"
           data-category="<?= htmlspecialchars($item['category']) ?>"
           onclick="openLightbox('<?= SITE_URL.'/'.htmlspecialchars($item['image']) ?>','<?= htmlspecialchars($item['title']) ?>')">
        <img src="<?= SITE_URL.'/'.htmlspecialchars($item['image']) ?>"
             alt="<?= htmlspecialchars($item['title']) ?>"
             loading="lazy"
             onerror="this.src='https://placehold.co/600x450/1c1c1c/d4a017?text=<?= urlencode($item['category']) ?>'"/>
        <div class="gallery-overlay">
          <div>
            <div class="gallery-overlay-cat"><?= htmlspecialchars(ucfirst($item['category'])) ?></div>
            <div class="gallery-overlay-title"><?= htmlspecialchars($item['title']) ?></div>
            <?php if($item['description']): ?>
            <div style="font-size:.72rem;color:rgba(255,255,255,.65);margin-top:4px"><?= htmlspecialchars(substr($item['description'],0,80)) ?>…</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5 reveal">
      <div style="font-size:3rem;margin-bottom:1rem">📸</div>
      <h4>No items in this category yet</h4>
      <p class="text-muted">Check back soon — we're always adding new work!</p>
      <a href="portfolio.php" class="btn btn-outline mt-3">View All Events</a>
    </div>
    <?php endif; ?>
 
    <!-- Placeholder items to fill the grid visually -->
    <?php
    $placeholders = [
      ['title'=>'Elegance in White — Wedding 2025',     'cat'=>'weddings',  'emoji'=>'💍'],
      ['title'=>'Thabo Inc. Product Launch',            'cat'=>'corporate', 'emoji'=>'🏢'],
      ['title'=>'Zinhle\'s 21st — Gatsby Theme',       'cat'=>'private',   'emoji'=>'🥂'],
      ['title'=>'Rainbow & Unicorns Birthday',          'cat'=>'kids',      'emoji'=>'🦄'],
      ['title'=>'Sunrise Garden Wedding Ceremony',      'cat'=>'weddings',  'emoji'=>'🌸'],
      ['title'=>'Annual Charity Gala 2025',             'cat'=>'corporate', 'emoji'=>'🌟'],
    ];
    if (count($items) < 6 && $category === 'all'):
    foreach($placeholders as $ph): ?>
    <div class="gallery-item reveal" data-category="<?= $ph['cat'] ?>"
         onclick="openLightbox('https://placehold.co/800x600/1c1c1c/d4a017?text=<?= urlencode($ph['title']) ?>','<?= htmlspecialchars($ph['title']) ?>')">
      <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--black-soft),var(--black-mid));display:flex;align-items:center;justify-content:center;font-size:3.5rem"><?= $ph['emoji'] ?></div>
      <div class="gallery-overlay">
        <div>
          <div class="gallery-overlay-cat"><?= htmlspecialchars($ph['cat']) ?></div>
          <div class="gallery-overlay-title"><?= htmlspecialchars($ph['title']) ?></div>
        </div>
      </div>
    </div>
    <?php endforeach; endif; ?>
 
  </div>
</section>
 
<!-- ── Lightbox ──────────────────────────────────────────────── -->
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true">
  <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close">&times;</button>
  <div style="text-align:center">
    <img src="" id="lightboxImg" class="lightbox-img" alt=""/>
    <p id="lightboxCaption" style="color:var(--gold-400);font-family:var(--font-display);font-size:1.1rem;margin-top:1rem"></p>
  </div>
</div>
 
<!-- ── CTA ───────────────────────────────────────────────────── -->
<section class="section-sm" style="background:var(--black-rich);border-top:.5px solid var(--color-border)">
  <div class="container text-center reveal">
    <span class="eyebrow">Like What You See?</span>
    <h3>Let's Create Your Story</h3>
    <p class="text-muted mb-4">Get in touch and let's start planning your beautiful event today.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="booking.php" class="btn btn-primary">Get a Free Quote</a>
      <a href="contact.php" class="btn btn-outline">Contact Us</a>
    </div>
  </div>
</section>
 
<script>
function openLightbox(src, caption) {
  document.getElementById('lightboxImg').src = src;
  document.getElementById('lightboxCaption').textContent = caption;
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('lightbox').addEventListener('click', function(e){
  if(e.target === this) closeLightbox();
});
document.addEventListener('keydown', e => { if(e.key==='Escape') closeLightbox(); });
</script>
 
<?php require_once __DIR__ . '/includes/footer.php'; ?>