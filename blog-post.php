<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
 
$slug = isset($_GET['slug']) ? clean($_GET['slug']) : '';
$post = null;
if ($slug) {
    try {
        $stmt = db()->prepare('SELECT * FROM blog_posts WHERE slug = ? AND published = 1');
        $stmt->execute([$slug]);
        $post = $stmt->fetch();
    } catch(Exception $e) {}
}
 
if (!$post) {
    header('Location: blog.php');
    exit;
}
 
$pageTitle  = $post['title'];
$activePage = 'blog';
require_once __DIR__ . '/includes/header.php';
 
// Related posts
try {
    $related = db()->prepare('SELECT * FROM blog_posts WHERE category=? AND slug!=? AND published=1 LIMIT 3');
    $related->execute([$post['category'], $slug]);
    $related = $related->fetchAll();
} catch(Exception $e) { $related = []; }
?>
 
<!-- ── Page Banner ──────────────────────────────────────────── -->
<div class="page-banner">
  <div class="page-banner-content container">
    <div class="breadcrumb">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">♦</span>
      <a href="blog.php">Blog</a>
      <span class="breadcrumb-sep">♦</span>
      <span class="breadcrumb-current"><?= htmlspecialchars($post['category'] ?? 'Article') ?></span>
    </div>
  </div>
</div>
 
<!-- ── Post Content ──────────────────────────────────────────── -->
<section class="section">
  <div class="container-narrow">
 
    <!-- Meta -->
    <div class="blog-meta mb-4 reveal">
      <span class="blog-cat"><?= htmlspecialchars($post['category'] ?? 'General') ?></span>
      <span class="blog-date"><?= date('d F Y', strtotime($post['created_at'])) ?></span>
      <span class="blog-date">By <?= htmlspecialchars($post['author']) ?></span>
    </div>
 
    <h1 class="reveal" style="font-size:clamp(1.8rem,4vw,2.8rem);margin-bottom:1.5rem"><?= htmlspecialchars($post['title']) ?></h1>
 
    <!-- Hero image -->
    <?php if($post['image']): ?>
    <div class="reveal mb-5" style="border-radius:var(--radius-lg);overflow:hidden">
      <img src="<?= SITE_URL.'/'.htmlspecialchars($post['image']) ?>"
           alt="<?= htmlspecialchars($post['title']) ?>"
           style="width:100%;aspect-ratio:16/9;object-fit:cover"
           onerror="this.src='https://placehold.co/900x500/1c1c1c/d4a017?text=Blog'"/>
    </div>
    <?php endif; ?>
 
    <!-- Body -->
    <div class="reveal" style="font-size:1rem;line-height:1.9;color:var(--grey-200)">
      <?= $post['body'] ?>
    </div>
 
    <!-- Share -->
    <div class="reveal mt-5 pt-4" style="border-top:.5px solid var(--color-border)">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <span style="font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--color-text-muted)">Share</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL.'/blog-post.php?slug='.$slug) ?>"
           target="_blank" class="btn btn-sm btn-ghost"><i class="fab fa-facebook-f me-1"></i> Facebook</a>
        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL.'/blog-post.php?slug='.$slug) ?>&text=<?= urlencode($post['title']) ?>"
           target="_blank" class="btn btn-sm btn-ghost"><i class="fab fa-twitter me-1"></i> Twitter</a>
        <a href="https://wa.me/?text=<?= urlencode($post['title'].' '.SITE_URL.'/blog-post.php?slug='.$slug) ?>"
           target="_blank" class="btn btn-sm btn-ghost"><i class="fab fa-whatsapp me-1"></i> WhatsApp</a>
      </div>
    </div>
 
    <div class="text-center mt-5 reveal">
      <a href="blog.php" class="btn btn-outline">&larr; Back to Journal</a>
    </div>
  </div>
</section>
 
<!-- ── Related Posts ─────────────────────────────────────────── -->
<?php if(!empty($related)): ?>
<section class="section-sm" style="background:var(--black-rich);border-top:.5px solid var(--color-border)">
  <div class="container">
    <h4 class="reveal mb-4" style="font-family:var(--font-display)">Related Articles</h4>
    <div class="grid grid-3">
      <?php foreach($related as $i => $r): ?>
      <article class="blog-card reveal <?= $i>0?'reveal-delay-'.$i:'' ?>">
        <img src="<?= SITE_URL.'/'.htmlspecialchars($r['image'] ?? 'images/decor.jpg') ?>"
             alt="<?= htmlspecialchars($r['title']) ?>" class="blog-card-img"
             onerror="this.src='https://placehold.co/600x360/1c1c1c/d4a017?text=Blog'"/>
        <div class="blog-card-body">
          <div class="blog-meta"><span class="blog-cat"><?= htmlspecialchars($r['category']) ?></span></div>
          <h3 class="blog-card-title"><?= htmlspecialchars($r['title']) ?></h3>
          <a href="blog-post.php?slug=<?= urlencode($r['slug']) ?>" class="blog-read-more">Read More →</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
 
<!-- Inline blog post styling -->
<style>
.container-narrow h3 { font-family:var(--font-display);color:var(--gold-400);margin:2rem 0 .75rem;font-size:1.3rem }
.container-narrow p  { color:var(--grey-200);line-height:1.9;margin-bottom:1.2rem }
.container-narrow ul,.container-narrow ol { padding-left:1.5rem;color:var(--grey-200);margin-bottom:1.2rem }
.container-narrow li { margin-bottom:.5rem }
</style>
 
<?php require_once __DIR__ . '/includes/footer.php'; ?>