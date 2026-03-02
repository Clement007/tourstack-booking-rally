<?php
$pageTitle = 'New Booking Request';
require __DIR__ . '/../shared/header.php';

// Flash messages
$errors   = $_SESSION['errors']    ?? [];
$success  = $_SESSION['success']   ?? '';
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['form_data']);

function old($key, $formData) {
    return htmlspecialchars($formData[$key] ?? '');
}
?>

<div class="container page-wrap">
  <div class="page-header">
    <h1>New Booking Request</h1>
    <p>Fill in the details below. Total cost is calculated automatically.</p>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <strong>Please fix the following:</strong>
      <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <div class="form-layout">
    <form method="POST" action="index.php?page=booking" class="booking-form card" novalidate>

      <div class="form-section-title">Guest Information</div>
      <div class="form-row">
        <div class="form-group">
          <label for="guest_name">Full Name <span class="req">*</span></label>
          <input type="text" id="guest_name" name="guest_name" value="<?= old('guest_name',$formData) ?>" placeholder="e.g. Jean Claude Uwimana" required/>
          <span class="field-error" id="err-name"></span>
        </div>
        <div class="form-group">
          <label for="phone">Phone Number <span class="req">*</span></label>
          <input type="tel" id="phone" name="phone" value="<?= old('phone',$formData) ?>" placeholder="e.g. 0788123456" required/>
          <span class="field-error" id="err-phone"></span>
        </div>
      </div>

      <div class="form-section-title">Stay Details</div>
      <div class="form-row">
        <div class="form-group">
          <label for="checkin_date">Check-in Date <span class="req">*</span></label>
          <input type="date" id="checkin_date" name="checkin_date" value="<?= old('checkin_date',$formData) ?>" required/>
          <span class="field-error" id="err-date"></span>
        </div>
        <div class="form-group">
          <label for="nights">Number of Nights <span class="req">*</span></label>
          <input type="number" id="nights" name="nights" value="<?= old('nights',$formData) ?>" min="1" max="30" placeholder="e.g. 3" required oninput="calcCost()"/>
          <span class="field-error" id="err-nights"></span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="price_per_night">Price per Night (RWF) <span class="req">*</span></label>
          <input type="number" id="price_per_night" name="price_per_night" value="<?= old('price_per_night',$formData) ?>" min="1000" placeholder="e.g. 15000" required oninput="calcCost()"/>
          <span class="field-error" id="err-price"></span>
        </div>
        <div class="form-group">
          <label for="num_guests">Number of Guests</label>
          <input type="number" id="num_guests" name="num_guests" value="<?= old('num_guests',$formData) ?: '1' ?>" min="1" max="20"/>
        </div>
      </div>

      <div class="form-group">
        <label for="special_requests">Special Requests</label>
        <textarea id="special_requests" name="special_requests" rows="3" placeholder="Any special needs, dietary requirements, or notes..."><?= old('special_requests',$formData) ?></textarea>
      </div>

      <!-- Cost Summary (shown by JS) -->
      <div class="cost-summary" id="costBox" style="display:none">
        <div class="cost-row">
          <span>Base Cost</span>
          <span id="baseCost">RWF 0</span>
        </div>
        <div class="cost-row">
          <span>Service Fee (5%)</span>
          <span id="feeCost">RWF 0</span>
        </div>
        <div class="cost-divider"></div>
        <div class="cost-row cost-total">
          <span>Total</span>
          <span id="totalCost">RWF 0</span>
        </div>
      </div>

      <button type="submit" class="btn btn-gold btn-full" onclick="return validateForm()">
        Submit Booking Request
      </button>
    </form>

    <aside class="form-aside">
      <div class="card aside-card">
        <h3>📌 What happens next?</h3>
        <ol class="aside-steps">
          <li>Your request is saved with <strong>Pending</strong> status</li>
          <li>The owner reviews your booking</li>
          <li>Status updates to <strong>Confirmed</strong> or <strong>Cancelled</strong></li>
        </ol>
      </div>
      <div class="card aside-card">
        <h3>💡 Pricing Note</h3>
        <p>A <strong>5% service fee</strong> is added to your base cost to cover platform maintenance.</p>
      </div>
    </aside>
  </div>
</div>

<script>
function calcCost() {
  const nights = parseFloat(document.getElementById('nights').value) || 0;
  const price  = parseFloat(document.getElementById('price_per_night').value) || 0;
  const box    = document.getElementById('costBox');
  if (nights > 0 && price > 0) {
    const base = nights * price;
    const fee  = Math.round(base * 0.05);
    const total = base + fee;
    document.getElementById('baseCost').textContent  = 'RWF ' + base.toLocaleString();
    document.getElementById('feeCost').textContent   = 'RWF ' + fee.toLocaleString();
    document.getElementById('totalCost').textContent = 'RWF ' + total.toLocaleString();
    box.style.display = 'block';
  } else {
    box.style.display = 'none';
  }
}

function validateForm() {
  let ok = true;
  const checks = [
    ['guest_name', 'err-name',   v => v.trim().length > 0, 'Name is required'],
    ['phone',      'err-phone',  v => v.trim().length > 0, 'Phone is required'],
    ['checkin_date','err-date',  v => v.length > 0,        'Date is required'],
    ['nights',     'err-nights', v => v >= 1 && v <= 30,   'Nights must be 1–30'],
    ['price_per_night','err-price', v => v >= 1000,        'Price must be ≥ RWF 1,000'],
  ];
  checks.forEach(([id, errId, test, msg]) => {
    const el  = document.getElementById(id);
    const err = document.getElementById(errId);
    if (!test(el.type === 'number' ? parseFloat(el.value)||0 : el.value)) {
      err.textContent = msg; err.style.display = 'block';
      el.classList.add('input-error'); ok = false;
    } else {
      err.style.display = 'none'; el.classList.remove('input-error');
    }
  });
  return ok;
}

// Set min date
document.getElementById('checkin_date').min = new Date().toISOString().split('T')[0];
calcCost();
</script>

<?php require __DIR__ . '/../shared/footer.php'; ?>
