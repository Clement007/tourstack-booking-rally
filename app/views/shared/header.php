<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TourStack — <?= htmlspecialchars($pageTitle ?? 'Home Stay Booking') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="public/assets/css/main.css"/>
</head>
<body>

<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php" class="nav-brand">Tour<span>Stack</span></a>
    <div class="nav-links">
      <a href="index.php?page=home"      class="<?= ($page==='home')      ? 'active':'' ?>">Home</a>
      <a href="index.php?page=booking"   class="<?= ($page==='booking')   ? 'active':'' ?>">Book a Stay</a>
      <a href="index.php?page=dashboard" class="<?= ($page==='dashboard') ? 'active':'' ?>">Dashboard</a>
    </div>
    <button class="hamburger" onclick="toggleMenu()">☰</button>
  </div>
</nav>

<main class="main-content">
