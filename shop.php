<?php
require_once __DIR__ . '/includes/functions.php';
$meta = page_meta('Shop', 'Browse premium custom printed clothing across men, women, kids, oversized apparel, and accessories.');
$selected = $_GET['category'] ?? null;
$products = $selected ? filter_products($selected) : products();
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Catalog</p>
        <h1>Shop ZetaStyle</h1>
        <p>Custom printed essentials with premium fabric, refined silhouettes, and made-to-order print quality.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="filter-row">
            <a class="<?= !$selected ? 'active' : ''; ?>" href="shop.php">All</a>
            <?php foreach (categories() as $category): ?>
                <a class="<?= $selected === $category['slug'] ? 'active' : ''; ?>" href="shop.php?category=<?= e($category['slug']); ?>"><?= e($category['name']); ?></a>
            <?php endforeach; ?>
        </div>
        <div class="product-grid">
            <?php foreach ($products as $product) { render_product_card($product); } ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
