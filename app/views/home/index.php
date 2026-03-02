<?php
$pageTitle = 'Home Stay Booking in Rwanda';
require __DIR__ . '/../shared/header.php';
?>

<section class="hero">
  <div class="hero-content">
    <div class="hero-badge">🏡 Rwanda Home Stays</div>
    <h1>Find Your Perfect<br><span>Home Stay</span></h1>
    <p>Simple, transparent bookings with local Rwanda home-stay owners. No more lost messages or unclear pricing.</p>
    <div class="hero-btns">
      <a href="index.php?page=booking" class="btn btn-gold">Book a Stay</a>
      <a href="index.php?page=dashboard" class="btn btn-outline-white">View Dashboard</a>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hero-card">
      <div class="hc-row"><span class="hc-label">Guest</span><span>Jean Claude</span></div>
      <div class="hc-row"><span class="hc-label">Nights</span><span>3</span></div>
      <div class="hc-row"><span class="hc-label">Price/Night</span><span>RWF 15,000</span></div>
      <div class="hc-divider"></div>
      <div class="hc-row"><span class="hc-label">Total</span><span class="hc-total">RWF 47,250</span></div>
      <div class="hc-status confirmed">✓ Confirmed</div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header">
      <h2>Why TourStack?</h2>
      <p>Solving real problems for Rwanda's tourism community</p>
    </div>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">💰</div>
        <h3>Clear Pricing</h3>
        <p>Total cost auto-calculated before you confirm. No surprises.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📋</div>
        <h3>Booking History</h3>
        <p>All bookings tracked in one dashboard for owners.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">✅</div>
        <h3>Status Tracking</h3>
        <p>Pending → Confirmed → Cancelled at a glance.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📱</div>
        <h3>No WhatsApp Chaos</h3>
        <p>Structured requests replace confusing messages.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <h2>How It Works</h2>
    </div>
    <div class="steps-row">
      <div class="step"><div class="step-num">1</div><p>Fill in guest details & nights</p></div>
      <div class="step-arrow">→</div>
      <div class="step"><div class="step-num step-gold">2</div><p>Cost auto-calculated instantly</p></div>
      <div class="step-arrow">→</div>
      <div class="step"><div class="step-num step-light">3</div><p>Owner confirms or cancels</p></div>
    </div>
    <div style="text-align:center;margin-top:2rem">
      <a href="index.php?page=booking" class="btn btn-green">Get Started →</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../shared/footer.php'; ?>
