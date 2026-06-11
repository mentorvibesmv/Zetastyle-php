<?php
require_once __DIR__ . '/includes/functions.php';

$meta = page_meta('Track Order', 'Track your ZetaStyle custom printed clothing order status.');
$trackingQuery = trim((string) ($_GET['track'] ?? $_POST['track'] ?? ''));
$trackedOrder = null;
$trackError = '';

if ($trackingQuery !== '') {
    $pdo = db();
    if ($pdo instanceof PDO) {
        $statement = $pdo->prepare(
            'SELECT * FROM orders WHERE order_id = :query OR phone = :query ORDER BY id DESC LIMIT 1'
        );
        $statement->execute([':query' => $trackingQuery]);
        $trackedOrder = $statement->fetch();

        if (!$trackedOrder) {
            $trackError = 'No order was found for that Order ID or phone number.';
        }
    } else {
        $trackError = 'Tracking is temporarily unavailable. Please try again later.';
    }
}

$statuses = ['confirmed', 'printing', 'packed', 'shipped', 'delivered'];
$statusLabels = [
    'confirmed' => 'Order Confirmed',
    'printing' => 'Printing',
    'packed' => 'Packed',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
];
$statusDescriptions = [
    'confirmed' => 'Artwork and garment details received.',
    'printing' => 'Your custom print is in production.',
    'packed' => 'Quality check and premium packaging.',
    'shipped' => 'Carrier pickup and tracking assignment.',
    'delivered' => 'Your order has arrived.',
];
$currentStatus = $trackedOrder['status'] ?? 'printing';
$currentIndex = array_search($currentStatus, $statuses, true);
if ($currentStatus === 'pending') {
    $currentIndex = -1;
}
if ($currentStatus === 'cancelled') {
    $currentIndex = -2;
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Order tracking</p>
        <h1>Track your print order.</h1>
        <p>Enter your Order ID or phone number to view the latest production and delivery status.</p>
    </div>
</section>
<section class="section">
    <div class="container track-layout">
        <form class="track-form reveal" method="post">
            <label for="order-id">Order ID or Phone Number</label>
            <div>
                <input id="order-id" name="track" type="text" value="<?= e($trackingQuery); ?>" placeholder="ZS-10482 or +15550199" required>
                <button class="btn btn-dark" type="submit">Track</button>
            </div>
        </form>

        <?php if ($trackError): ?>
            <div class="empty-cart reveal"><?= e($trackError); ?></div>
        <?php endif; ?>

        <?php if ($trackedOrder): ?>
            <article class="contact-panel reveal">
                <h2><?= e($trackedOrder['order_id']); ?></h2>
                <p><strong>Status:</strong> <?= e(ucfirst($trackedOrder['status'])); ?></p>
                <p><strong>Courier:</strong> <?= e($trackedOrder['courier_name'] ?: 'Not assigned yet'); ?></p>
                <p><strong>Tracking Number:</strong> <?= e($trackedOrder['tracking_number'] ?: 'Not assigned yet'); ?></p>
                <p><strong>Expected Delivery:</strong> <?= e($trackedOrder['expected_delivery'] ?: 'To be confirmed'); ?></p>
            </article>
        <?php endif; ?>

        <ol class="timeline reveal">
            <?php foreach ($statuses as $index => $status): ?>
                <li class="<?= $currentIndex >= $index ? 'done' : ''; ?>">
                    <span></span>
                    <strong><?= e($statusLabels[$status]); ?></strong>
                    <small><?= e($statusDescriptions[$status]); ?></small>
                </li>
            <?php endforeach; ?>
            <?php if ($currentStatus === 'cancelled'): ?>
                <li class="done"><span></span><strong>Cancelled</strong><small>This order has been cancelled by the studio.</small></li>
            <?php endif; ?>
        </ol>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
