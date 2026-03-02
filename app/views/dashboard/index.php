<?php
$pageTitle = 'Owner Dashboard';
require __DIR__ . '/../shared/header.php';

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>

<div class="container page-wrap">
  <div class="page-header">
    <h1>Owner Dashboard</h1>
    <p>Manage all booking requests in one place.</p>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <!-- Stats Row -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-num"><?= $stats['total'] ?? 0 ?></div>
      <div class="stat-label">Total Bookings</div>
    </div>
    <div class="stat-card stat-pending">
      <div class="stat-num"><?= $stats['pending'] ?? 0 ?></div>
      <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card stat-confirmed">
      <div class="stat-num"><?= $stats['confirmed'] ?? 0 ?></div>
      <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card stat-revenue">
      <div class="stat-num">RWF <?= number_format($stats['total_revenue'] ?? 0) ?></div>
      <div class="stat-label">Total Revenue</div>
    </div>
  </div>

  <!-- Filter + Table -->
  <div class="card table-card">
    <div class="table-toolbar">
      <h3>All Bookings</h3>
      <div class="filter-tabs">
        <?php foreach (['all','pending','confirmed','cancelled'] as $s): ?>
          <a href="index.php?page=dashboard&status=<?= $s ?>"
             class="filter-tab <?= (($_GET['status']??'all')===$s)?'active':'' ?>">
            <?= ucfirst($s) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if (empty($bookings)): ?>
      <div class="empty-state">
        <div style="font-size:3rem">📋</div>
        <p>No bookings found.</p>
        <a href="index.php?page=booking" class="btn btn-green">Create First Booking</a>
      </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Guest</th>
            <th>Phone</th>
            <th>Check-in</th>
            <th>Nights</th>
            <th>Total (RWF)</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $i => $b): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>
              <strong><?= htmlspecialchars($b['guest_name']) ?></strong>
              <?php if ($b['special_requests']): ?>
                <br><small class="note" title="<?= htmlspecialchars($b['special_requests']) ?>">📝 Has notes</small>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($b['phone']) ?></td>
            <td><?= htmlspecialchars($b['checkin_date']) ?><br><small><?= $b['num_guests'] ?> guest(s)</small></td>
            <td><?= $b['nights'] ?></td>
            <td><strong><?= number_format($b['total_cost']) ?></strong></td>
            <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
            <td>
              <?php if ($b['status'] === 'pending'): ?>
                <form method="POST" action="index.php?page=dashboard&action=update" style="display:inline">
                  <input type="hidden" name="id" value="<?= $b['id'] ?>"/>
                  <input type="hidden" name="status" value="confirmed"/>
                  <button type="submit" class="action-btn confirm-btn">Confirm</button>
                </form>
                <form method="POST" action="index.php?page=dashboard&action=update" style="display:inline">
                  <input type="hidden" name="id" value="<?= $b['id'] ?>"/>
                  <input type="hidden" name="status" value="cancelled"/>
                  <button type="submit" class="action-btn cancel-btn">Cancel</button>
                </form>
              <?php else: ?>
                <span style="color:#aaa;font-size:0.8rem">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../shared/footer.php'; ?>
