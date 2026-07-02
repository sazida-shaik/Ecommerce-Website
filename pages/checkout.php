<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT p.name, p.price, c.quantity
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

if (isset($_POST['place_order'])) {

    // Empty the cart after placing order
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    echo "<script>
            alert('Order placed successfully!');
            window.location='../index.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Checkout</title>

<style>
body{
    font-family:Arial;
    background:#f5f5f5;
}

.container{
    width:700px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

th{
    background:#28a745;
    color:white;
}

.total{
    margin-top:20px;
    text-align:right;
    font-size:22px;
    font-weight:bold;
}

button{
    margin-top:20px;
    width:100%;
    padding:15px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:18px;
}

button:hover{
    background:#218838;
}
</style>

</head>
<body>

<div class="container">

<h2>Checkout</h2>

<?php if(count($items)==0){ ?>

<h3>Your cart is empty.</h3>

<?php } else { ?>

<table>

<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
</tr>

<?php foreach($items as $item){ ?>

<tr>
<td><?= htmlspecialchars($item['name']) ?></td>
<td>$<?= number_format($item['price'],2) ?></td>
<td><?= $item['quantity'] ?></td>
<td>$<?= number_format($item['price']*$item['quantity'],2) ?></td>
</tr>

<?php } ?>

</table>

<div class="total">
Grand Total: $<?= number_format($total,2); ?>
</div>

<form method="POST">
<button type="submit" name="place_order">
Place Order
</button>
</form>

<?php } ?>

</div>

</body>
</html>