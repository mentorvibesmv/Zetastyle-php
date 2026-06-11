<?php
require_once __DIR__ . '/includes/functions.php';
$meta = page_meta('Premium Custom Printed Clothing', 'Shop premium custom printed clothing, oversized tees, hoodies, kidswear, and accessories from ZetaStyle.');
require_once __DIR__ . '/includes/header.php';
$cats = categories();
$subs = sub_categories();
$allProducts = products();
?>
<section class="hero">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=1800&q=90" alt="Premium clothing rail with tailored garments">
    </div>
    <div class="container hero-content reveal">
        <p class="eyebrow">Custom printed clothing studio</p>
        <h1>ZetaStyle</h1>
        <p>Minimal luxury apparel made personal, from single keepsakes to polished team collections.</p>
        <div class="hero-actions">
            <a class="btn btn-dark" href="shop.php">Shop Collection</a>
            <a class="btn btn-light" href="contact.php">Start Custom Order</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Curated entry points</p>
            <h2>Shop By Category</h2>
        </div>
        <div class="category-grid">
            <?php foreach ($cats as $category): ?>
                <a class="image-card reveal" href="category.php?category=<?= e($category['slug']); ?>">
                    <img loading="lazy" src="<?= e($category['image']); ?>" alt="<?= e($category['name']); ?> custom printed clothing">
                    <span><?= e($category['name']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="promo-slider section-tight" data-slider>
    <div class="slider-track" data-slider-track>
        <?php foreach (banners() as $banner): ?>
            <a class="promo-slide" href="<?= e($banner['href']); ?>">
                <img loading="lazy" src="<?= e($banner['image']); ?>" alt="<?= e($banner['title']); ?>">
                <span>
                    <small><?= e($banner['subtitle']); ?></small>
                    <strong><?= e($banner['title']); ?></strong>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    <button class="slider-btn prev" type="button" data-slider-prev aria-label="Previous promotion">Prev</button>
    <button class="slider-btn next" type="button" data-slider-next aria-label="Next promotion">Next</button>
    <div class="slider-dots" data-slider-dots></div>
</section>

<?php foreach (['men' => 'Men Collection', 'women' => 'Women Collection', 'kids' => 'Kids Collection'] as $slug => $title): ?>
<section class="section">
    <div class="container">
        <div class="section-heading split">
            <div>
                <p class="eyebrow">Made for <?= e(explode(' ', $title)[0]); ?></p>
                <h2><?= e($title); ?></h2>
            </div>
            <a href="category.php?category=<?= e($slug); ?>">View all</a>
        </div>
        <div class="collection-grid">
            <?php foreach (array_slice(array_values(array_filter($subs, fn($sub) => $sub['category'] === $slug)), 0, 6) as $sub): ?>
                <article class="collection-card reveal">
                    <img loading="lazy" src="<?= e($sub['image']); ?>" alt="<?= e($sub['name']); ?>">
                    <div>
                        <h3><?= e($sub['name']); ?></h3>
                        <a class="btn btn-light" href="category.php?category=<?= e($slug); ?>">Shop Now</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Most watched this week</p>
            <h2>Trending Products</h2>
        </div>
        <div class="product-slider">
            <?php foreach (filter_products(null, 'trending') as $product) { render_product_card($product); } ?>
        </div>
    </div>
</section>

<section class="section muted">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Fresh from the studio</p>
            <h2>New Arrivals</h2>
        </div>
        <div class="product-grid">
            <?php foreach (array_slice(filter_products(null, 'new'), 0, 6) as $product) { render_product_card($product); } ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Customer favorites</p>
            <h2>Best Selling Products</h2>
        </div>
        <div class="product-slider">
            <?php foreach (filter_products(null, 'best') as $product) { render_product_card($product); } ?>
        </div>
    </div>
</section>

<section class="contact-band">
    <div class="container contact-band-inner">
        <div>
            <p class="eyebrow">Custom work</p>
            <h2>Bring your print idea to fabric.</h2>
        </div>
        <a class="btn btn-dark" href="contact.php">Request a Quote</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
