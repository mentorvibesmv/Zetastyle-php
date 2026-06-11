<?php
require_once __DIR__ . '/../bootstrap.php';
require_admin();
$pdo = admin_db();
$adminTitle = 'Orders';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $items = array_values(array_filter((array)($_POST['items'] ?? []), fn($i) => trim((string)($i['product_name'] ?? '')) !== ''));
    $total = 0;
    foreach ($items as $item) $total += (float)$item['price'] * (int)$item['quantity'];
    $orderId = trim((string)($_POST['order_id'] ?? '')) ?: 'ZS-' . date('ymd') . '-' . random_int(1000, 9999);
    $data = [':order_id'=>$orderId,':customer_name'=>trim($_POST['customer_name']),':phone'=>trim($_POST['phone']),':address'=>trim($_POST['address']),':notes'=>trim($_POST['notes']),':status'=>$_POST['status'],':courier_name'=>trim($_POST['courier_name']),':tracking_number'=>trim($_POST['tracking_number']),':expected_delivery'=>$_POST['expected_delivery'] ?: null,':total_amount'=>$total];
    if ($id > 0) {
        $data[':id']=$id; $pdo->prepare('UPDATE orders SET order_id=:order_id,customer_name=:customer_name,phone=:phone,address=:address,notes=:notes,status=:status,courier_name=:courier_name,tracking_number=:tracking_number,expected_delivery=:expected_delivery,total_amount=:total_amount WHERE id=:id')->execute($data); $pdo->prepare('DELETE FROM order_items WHERE order_id=:id')->execute([':id'=>$id]); $orderPk=$id;
    } else {
        $pdo->prepare('INSERT INTO orders (order_id,customer_name,phone,address,notes,status,courier_name,tracking_number,expected_delivery,total_amount) VALUES (:order_id,:customer_name,:phone,:address,:notes,:status,:courier_name,:tracking_number,:expected_delivery,:total_amount)')->execute($data); $orderPk=(int)$pdo->lastInsertId();
    }
    foreach ($items as $item) $pdo->prepare('INSERT INTO order_items (order_id,product_name,quantity,price) VALUES (:order_id,:product_name,:quantity,:price)')->execute([':order_id'=>$orderPk,':product_name'=>trim($item['product_name']),':quantity'=>(int)$item['quantity'],':price'=>(float)$item['price']]);
    redirect_with(admin_url('orders/'), 'Order saved. Order ID: ' . $orderId);
}
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM orders WHERE id=:id')->execute([':id'=>(int)$_GET['delete']]); redirect_with(admin_url('orders/'), 'Order deleted.'); }
$edit=null; $editItems=[];
if (isset($_GET['edit'])) { $s=$pdo->prepare('SELECT * FROM orders WHERE id=:id'); $s->execute([':id'=>(int)$_GET['edit']]); $edit=$s->fetch(); $it=$pdo->prepare('SELECT * FROM order_items WHERE order_id=:id'); $it->execute([':id'=>(int)$_GET['edit']]); $editItems=$it->fetchAll(); }
$rows=$pdo->query('SELECT * FROM orders ORDER BY id DESC LIMIT 50')->fetchAll();
require __DIR__ . '/../header.php';
?>
<section class="panel"><div class="panel-head"><h2><?= $edit?'Edit':'Create'; ?> Manual Order</h2></div><form class="admin-form product-form" method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>"><input type="hidden" name="id" value="<?= e((string)($edit['id']??'')); ?>">
<label>Order ID<input name="order_id" value="<?= e((string)($edit['order_id']??'')); ?>" placeholder="Auto generated if empty"></label><label>Customer Name<input name="customer_name" value="<?= e((string)($edit['customer_name']??'')); ?>" required></label><label>Phone Number<input name="phone" value="<?= e((string)($edit['phone']??'')); ?>" required></label><label>Status<select name="status"><?php foreach(order_statuses() as $status): ?><option value="<?= e($status); ?>" <?= ($edit['status']??'pending')===$status?'selected':''; ?>><?= e(ucfirst($status)); ?></option><?php endforeach; ?></select></label>
<label class="wide">Address<textarea name="address" rows="3"><?= e((string)($edit['address']??'')); ?></textarea></label><label>Courier Name<input name="courier_name" value="<?= e((string)($edit['courier_name']??'')); ?>"></label><label>Tracking Number<input name="tracking_number" value="<?= e((string)($edit['tracking_number']??'')); ?>"></label><label>Expected Delivery<input type="date" name="expected_delivery" value="<?= e((string)($edit['expected_delivery']??'')); ?>"></label><label class="wide">Notes<textarea name="notes" rows="3"><?= e((string)($edit['notes']??'')); ?></textarea></label>
<div class="wide"><h3>Products</h3><div data-order-items><?php $items=$editItems ?: [['product_name'=>'','quantity'=>1,'price'=>'']]; foreach($items as $i=>$item): ?><div class="order-item-row"><input name="items[<?= $i; ?>][product_name]" placeholder="Product name" value="<?= e((string)($item['product_name']??'')); ?>"><input type="number" name="items[<?= $i; ?>][quantity]" value="<?= e((string)($item['quantity']??1)); ?>"><input type="number" step="0.01" name="items[<?= $i; ?>][price]" placeholder="Price" value="<?= e((string)($item['price']??'')); ?>"></div><?php endforeach; ?></div><button class="admin-btn" type="button" data-add-order-item>Add Product Row</button></div><button class="admin-btn primary">Save Order</button></form></section>
<section class="panel"><div class="panel-head"><h2>Orders</h2></div><div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Total</th><th>Tracking</th><th>Actions</th></tr></thead><tbody><?php foreach($rows as $row): ?><tr><td><?= e($row['order_id']); ?></td><td><?= e($row['customer_name']); ?><small><?= e($row['phone']); ?></small></td><td><span class="pill"><?= e($row['status']); ?></span></td><td><?= money((float)$row['total_amount']); ?></td><td><?= e($row['courier_name']); ?><small><?= e($row['tracking_number']); ?></small></td><td><a href="?edit=<?= (int)$row['id']; ?>">Edit</a> <a class="danger-link" href="?delete=<?= (int)$row['id']; ?>" data-confirm>Delete</a></td></tr><?php endforeach; ?></tbody></table></div></section>
<?php require __DIR__ . '/../footer.php'; ?>
