# TourStack — Home Stay Booking App
**Project C | INES Ruhengeri | Full Stack (PHP + MySQL + MVC)**

---

## 📌 About
TourStack solves the problem of home-stay owners losing bookings in WhatsApp messages. It provides a structured booking request system with transparent pricing and status tracking.

---

## 🗂️ Project Structure (MVC)
```
tourstack/
├── app/
│   ├── controllers/
│   │   └── BookingController.php   ← Handles all booking logic
│   ├── models/
│   │   └── Booking.php             ← Database CRUD operations
│   └── views/
│       ├── home/
│       │   └── index.php           ← Home page
│       ├── booking/
│       │   └── form.php            ← Booking request form
│       ├── dashboard/
│       │   └── index.php           ← Owner dashboard
│       └── shared/
│           ├── header.php          ← Shared navigation/head
│           ├── footer.php          ← Shared footer/scripts
│           └── 404.php             ← Error page
├── config/
│   └── db.php                      ← Database configuration
├── public/
│   └── assets/
│       ├── css/
│       │   └── main.css            ← All styles
│       └── js/
│           └── main.js             ← All scripts
├── database/
│   └── schema.sql                  ← MySQL table + seed data
├── index.php                       ← Front controller / Router
└── README.md
```

---

## ⚙️ Setup Instructions

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache (XAMPP / WAMP / LAMP)

### Installation Steps

1. **Clone / Copy** the project into your server's web directory:
   ```
   C:/xampp/htdocs/tourstack/   (Windows XAMPP)
   /var/www/html/tourstack/     (Linux/Mac)
   ```

2. **Create the database:**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "Import" and select `database/schema.sql`
   - Click "Go"

3. **Configure database connection:**
   - Open `config/db.php`
   - Update `DB_USER` and `DB_PASS` if needed

4. **Run the app:**
   - Start Apache and MySQL in XAMPP
   - Open http://localhost/tourstack/

---

## 🔑 Features
- ✅ Booking request form with validation
- ✅ Auto cost calculation (base + 5% service fee)
- ✅ MySQL data storage
- ✅ Owner dashboard with stats
- ✅ Status management: Pending → Confirmed → Cancelled
- ✅ Responsive design (mobile + desktop)
- ✅ Flash messages for user feedback

---

## 👥 Team Roles (7)
| Role | Marks |
|------|-------|
| Product Planner & Documentation Lead | 15 |
| UI/UX Designer | 20 |
| HTML/CSS Engineer | 15 |
| Backend Developer (PHP/MySQL) | 25 |
| MVC Architect | 10 |
| Tester | 10 |
| Deployment Lead | 5 |

---

## 🧪 Test Cases
| # | Test | Expected | Pass |
|---|------|----------|------|
| TC01 | Submit valid booking | Booking saved, success message | ✅ |
| TC02 | Empty name field | Error: Name required | ✅ |
| TC03 | Empty phone field | Error: Phone required | ✅ |
| TC04 | Nights = 0 | Error: Must be 1–30 | ✅ |
| TC05 | Price < 1000 | Error: Min RWF 1,000 | ✅ |
| TC06 | Enter nights + price | Cost auto-calculated | ✅ |
| TC07 | Service fee check | Fee = 5% of base | ✅ |
| TC08 | Click Confirm | Status → confirmed | ✅ |
| TC09 | Click Cancel | Status → cancelled | ✅ |
| TC10 | Dashboard stats | Counts update correctly | ✅ |

---

*Accredited by Ministerial Order N° 005/2010/Mineduc of 16 June 2010 — Scientia et Lux*
