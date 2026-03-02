<?php
// app/models/Booking.php — Booking Model (MVC)
// TourStack | INES Ruhengeri

require_once __DIR__ . '/../../config/db.php';

class Booking {

    // CREATE — Insert new booking
    public static function create($data) {
        $conn = getConnection();
        $stmt = $conn->prepare("
            INSERT INTO bookings 
            (guest_name, phone, checkin_date, nights, price_per_night, total_cost, service_fee, num_guests, special_requests)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssidddis",
            $data['guest_name'],
            $data['phone'],
            $data['checkin_date'],
            $data['nights'],
            $data['price_per_night'],
            $data['total_cost'],
            $data['service_fee'],
            $data['num_guests'],
            $data['special_requests']
        );
        $result = $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return $result ? $id : false;
    }

    // READ ALL — Get all bookings (with optional filter)
    public static function getAll($status = null) {
        $conn = getConnection();
        if ($status && in_array($status, ['pending','confirmed','cancelled'])) {
            $stmt = $conn->prepare("SELECT * FROM bookings WHERE status = ? ORDER BY created_at DESC");
            $stmt->bind_param("s", $status);
        } else {
            $stmt = $conn->prepare("SELECT * FROM bookings ORDER BY created_at DESC");
        }
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $result;
    }

    // READ ONE — Get single booking by ID
    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // UPDATE STATUS — confirm / cancel
    public static function updateStatus($id, $status) {
        if (!in_array($status, ['pending','confirmed','cancelled'])) return false;
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // STATS — count by status
    public static function getStats() {
        $conn = getConnection();
        $result = $conn->query("
            SELECT 
                COUNT(*) as total,
                SUM(status = 'pending') as pending,
                SUM(status = 'confirmed') as confirmed,
                SUM(status = 'cancelled') as cancelled,
                SUM(CASE WHEN status = 'confirmed' THEN total_cost ELSE 0 END) as total_revenue
            FROM bookings
        ")->fetch_assoc();
        $conn->close();
        return $result;
    }

    // CALCULATE COST — static utility
    public static function calculateCost($nights, $price_per_night) {
        $base = $nights * $price_per_night;
        $service_fee = round($base * 0.05, 2);
        $total = $base + $service_fee;
        return compact('base', 'service_fee', 'total');
    }

    // VALIDATE booking input
    public static function validate($data) {
        $errors = [];
        if (empty(trim($data['guest_name'])))     $errors[] = 'Guest name is required.';
        if (empty(trim($data['phone'])))           $errors[] = 'Phone number is required.';
        if (empty($data['checkin_date']))          $errors[] = 'Check-in date is required.';
        if (!isset($data['nights']) || $data['nights'] < 1 || $data['nights'] > 30)
                                                   $errors[] = 'Nights must be between 1 and 30.';
        if (!isset($data['price_per_night']) || $data['price_per_night'] < 1000)
                                                   $errors[] = 'Price per night must be at least RWF 1,000.';
        return $errors;
    }
}
