// public/assets/js/main.js — TourStack Scripts
// INES Ruhengeri | Project C

// ── Mobile Nav Toggle ──
function toggleMenu() {
  const nav = document.querySelector('.nav-links');
  nav.classList.toggle('open');
}

// ── Toast Notification ──
function showToast(message, duration = 3000) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = message;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), duration);
}

// ── Auto-dismiss alerts ──
document.addEventListener('DOMContentLoaded', () => {
  const alerts = document.querySelectorAll('.alert-success');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 4000);
  });
});

// ── Confirm before cancel action ──
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.cancel-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (!confirm('Are you sure you want to cancel this booking?')) {
        e.preventDefault();
      }
    });
  });
});
