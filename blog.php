<?php
$pageTitle  = 'Blog';
$activePage = 'blog';
require_once __DIR__ . '/includes/header.php';
 
try {
    $posts = db()->query('SELECT * FROM blog_posts WHERE published=1 ORDER BY created_at DESC')->fetchAll();
} catch(Exception $e) { $posts = []; }
?>
 
<!-- ── Page Banner ──────────────────────────────────────────── -->
<div class="page-banner">
  <div class="page-banner-content container">
    <div class="breadcrumb">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">♦</span>
      <span class="breadcrumb-current">Blog</span>
    </div>
    <span class="eyebrow">Ideas &amp; Inspiration</span>
    <h1>The Senzakwenzeke <em>Journal</em></h1>
    <p class="lead mt-3" style="max-width:580px;margin:1rem auto 0">Décor trends, catering tips, behind-the-scenes stories and event inspiration from our team.</p>
  </div>
</div>
 
<!-- ── Blog Grid ─────────────────────────────────────────────── -->
<section class="section">
  <div class="container">
    <?php if(!empty($posts)): ?>
    <!-- Featured post (first) -->
    <?php $first = $posts[0]; $rest = array_slice($posts, 1); ?>
    <div class="reveal mb-5">
      <div class="card" style="display:grid;grid-template-columns:1fr 1fr;gap:0;overflow:hidden">
        <div style="overflow:hidden">
          <img src="<?= SITE_URL.'/'.htmlspecialchars($first['image'] ?? 'images/decor.jpg') ?>"
               alt="<?= htmlspecialchars($first['title']) ?>"
               style="width:100%;height:100%;object-fit:cover;transition:transform .6s ease"
               onerror="this.src='https://placehold.co/600x400/1c1c1c/d4a017?text=Blog'"
               onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'"/>
        </div>
        <div style="padding:2.5rem;display:flex;flex-direction:column;justify-content:center">
          <div class="blog-meta">
            <span class="blog-cat"><?= htmlspecialchars($first['category'] ?? 'General') ?></span>
            <span class="blog-date"><?= date('d F Y', strtotime($first['created_at'])) ?></span>
          </div>
          <h2 class="blog-card-title" style="font-size:1.6rem;margin-bottom:.75rem"><?= htmlspecialchars($first['title']) ?></h2>
          <p style="color:var(--color-text-muted);font-size:.9rem;line-height:1.75;margin-bottom:1.5rem"><?= htmlspecialchars($first['excerpt'] ?? '') ?></p>
          <div style="font-size:.72rem;color:var(--color-text-muted);margin-bottom:1rem">By <?= htmlspecialchars($first['author']) ?></div>
          <a href="blog-post.php?slug=<?= urlencode($first['slug']) ?>" class="btn btn-primary" style="align-self:flex-start">Read Article</a>
        </div>
      </div>
    </div>
 
    <!-- Rest of posts -->
    <?php if(!empty($rest)): ?>
    <div class="grid grid-3">
      <?php foreach($rest as $i => $post): ?>
      <article class="blog-card reveal <?= $i>0?'reveal-delay-'.min($i,3):'' ?>">
        <div style="overflow:hidden">
          <img src="<?= SITE_URL.'/'.htmlspecialchars($post['image'] ?? 'images/decor.jpg') ?>"
               alt="<?= htmlspecialchars($post['title']) ?>"
               class="blog-card-img"
               onerror="this.src='https://placehold.co/600x360/1c1c1c/d4a017?text=Blog'"/>
        </div>
        <div class="blog-card-body">
          <div class="blog-meta">
            <span class="blog-cat"><?= htmlspecialchars($post['category'] ?? 'General') ?></span>
            <span class="blog-date"><?= date('d M Y', strtotime($post['created_at'])) ?></span>
          </div>
          <h3 class="blog-card-title"><?= htmlspecialchars($post['title']) ?></h3>
          <p class="blog-card-excerpt"><?= htmlspecialchars(substr($post['excerpt'] ?? '', 0, 140)) ?>…</p>
          <div style="font-size:.72rem;color:var(--color-text-muted);margin-top:.5rem">By <?= htmlspecialchars($post['author']) ?></div>
          <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" class="blog-read-more">
            Read More <i class="fas fa-arrow-right" style="font-size:.6rem"></i>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
 
    <?php else: ?>
    <!-- Fallback static blog posts if DB is empty -->
    <div class="grid grid-3">
      <?php
      $staticPosts = [
        ['title'=>'5 Décor Trends Taking Over KZN Weddings in 2026','cat'=>'Trends','date'=>'15 May 2026','excerpt'=>'From pampas grass to neon signs, we break down the biggest décor trends sweeping KwaZulu-Natal weddings this year.','img'=>'images/decor.jpg','author'=>'Amahle Dlamini'],
        ['title'=>'How to Plan Your Catering Menu for 200 Guests Without the Stress','cat'=>'Catering','date'=>'28 April 2026','excerpt'=>'Planning a large event menu can feel overwhelming. Our head caterer shares the exact process we use to deliver flawless meals.','img'=>'images/dijo.jpg','author'=>'Asanda Ndlovu'],
        ['title'=>'Behind the Scenes: Setting Up a Kids\' Party in 4 Hours','cat'=>'Behind the Scenes','date'=>'10 April 2026','excerpt'=>'Our team transformed a plain back garden into an Enchanted Forest for 60 children in under four hours. Here\'s how.','img'=>'images/kiddies.jpg','author'=>'Luungelo Dube'],
      ];
      foreach($staticPosts as $i => $p): ?>
      <article class="blog-card reveal <?= $i>0?'reveal-delay-'.$i:'' ?>">
        <div style="overflow:hidden">
          <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['title']) ?>"
               class="blog-card-img"
               onerror="this.src='https://placehold.co/600x360/1c1c1c/d4a017?text=Blog'"/>
        </div>
        <div class="blog-card-body">
          <div class="blog-meta">
            <span class="blog-cat"><?= $p['cat'] ?></span>
            <span class="blog-date"><?= $p['date'] ?></span>
          </div>
          <h3 class="blog-card-title"><?= htmlspecialchars($p['title']) ?></h3>
          <p class="blog-card-excerpt"><?= htmlspecialchars($p['excerpt']) ?></p>
          <div style="font-size:.72rem;color:var(--color-text-muted);margin-top:.5rem">By <?= $p['author'] ?></div>
          <a href="blog.php" class="blog-read-more">Read More <i class="fas fa-arrow-right" style="font-size:.6rem"></i></a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
 
    <!-- Newsletter signup -->
    <div class="reveal mt-5" style="background:var(--black-rich);border:.5px solid var(--color-border);border-radius:var(--radius-xl);padding:3rem;text-align:center">
      <span class="eyebrow">Stay Inspired</span>
      <h3>Subscribe to Our Journal</h3>
      <p class="text-muted mb-4">Get décor tips, event inspiration and exclusive offers delivered to your inbox.</p>
      <form style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;max-width:480px;margin:0 auto" onsubmit="subscribeNewsletter(event)">
        <input type="email" class="form-control" placeholder="your@email.com" id="nlEmail" style="flex:1;min-width:200px" required/>
        <button type="submit" class="btn btn-primary">Subscribe</button>
      </form>
      <p class="text-muted mt-3" style="font-size:.75rem">We respect your privacy. Unsubscribe any time.</p>
    </div>
 
  </div>
</section>
 
<script>
function subscribeNewsletter(e) {
  e.preventDefault();
  const btn = e.target.querySelector('button');
  btn.textContent = '✓ Subscribed!';
  btn.disabled = true;
  btn.style.background = 'var(--color-success)';
}
</script>
 
<?php require_once __DIR__ . '/includes/footer.php'; ?>