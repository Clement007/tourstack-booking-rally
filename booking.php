<?php
$guest = $_POST['guest'] ?? '';
$nights = $_POST['nights'] ?? 0;
$price = $_POST['price'] ?? 0;
$total = $nights * $price;
?>

<h2>Booking Summary</h2>

<p><?php echo $guest; ?></p>
<p>Total Cost: <?php echo $total; ?></p>

<?php
// RELAY:
// Student 1 → Sanitize data
// Student 2 → Add conditional message
// Student 3 → Add back button
?>