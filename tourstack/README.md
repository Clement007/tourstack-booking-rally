# TourStack – Full Stack Setup Guide

## 📁 Files in this project

```
tourstack/
├── index.html          ← Home page
├── booking.html        ← Booking form (JS + PHP)
├── dashboard.html      ← Owner dashboard (JS + PHP)
├── db.php              ← Database connection (edit this first)
├── get_properties.php  ← Returns properties from MySQL
├── submit_booking.php  ← Saves new booking to MySQL
├── get_bookings.php    ← Returns all bookings for dashboard
├── update_status.php   ← Updates booking status (confirm/cancel)
├── database.sql        ← Run this once to create the database
└── README.md           ← This file
```

---

## ⚙️ Setup Steps

### Step 1 — Install a local PHP + MySQL server
Download and install **XAMPP** (free):
👉 https://www.apachefriends.org/download.html

### Step 2 — Copy project files
Copy the entire `tourstack/` folder into:
```
C:/xampp/htdocs/tourstack/
```

### Step 3 — Create the database
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL**
3. Open your browser → go to: http://localhost/phpmyadmin
4. Click **SQL** tab at the top
5. Paste the contents of `database.sql`
6. Click **Go**

### Step 4 — Configure database connection
Open `db.php` and update if needed:
```php
define('DB_HOST', 'localhost');  // usually localhost
define('DB_USER', 'root');       // your MySQL username
define('DB_PASS', '');           // your MySQL password (XAMPP default: blank)
define('DB_NAME', 'tourstack_db');
```

### Step 5 — Open the website
In your browser go to:
```
http://localhost/tourstack/index.html
```

---

## 🔗 How the 3 technologies work together

```
[HTML Page]  →  JavaScript fetch()  →  [PHP File]  →  MySQL Database
                                              ↓
[HTML Page]  ←  JSON response       ←  [PHP File]
```

| File | Language | Job |
|------|----------|-----|
| `index.html` | HTML + JS | Shows properties loaded from DB |
| `booking.html` | HTML + JS | Form that posts to PHP |
| `dashboard.html` | HTML + JS | Reads & updates bookings via PHP |
| `db.php` | PHP | Connects to MySQL |
| `get_properties.php` | PHP | SELECT from properties table |
| `submit_booking.php` | PHP | INSERT into bookings table |
| `get_bookings.php` | PHP | SELECT from bookings table |
| `update_status.php` | PHP | UPDATE booking status |
| `database.sql` | SQL | Creates tables and sample data |

---

## 🌐 For live hosting (not local)
Upload all files to your web host's `public_html/` folder.
Update `db.php` with your hosting provider's MySQL credentials.
