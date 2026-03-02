<?php
// app/controllers/BookingController.php — Booking Controller (MVC)
// TourStack | INES Ruhengeri

require_once __DIR__ . '/../models/Booking.php';

class BookingController {

    // Show booking form (GET)
    public function showForm() {
        require __DIR__ . '/../views/booking/form.php';
    }

    // Handle booking submission (POST)
    public function store() {
        $data = [
            'guest_name'      => trim($_POST['guest_name'] ?? ''),
            'phone'           => trim($_POST['phone'] ?? ''),
            'checkin_date'    => $_POST['checkin_date'] ?? '',
            'nights'          => (int)($_POST['nights'] ?? 0),
            'price_per_night' => (float)($_POST['price_per_night'] ?? 0),
            'num_guests'      => (int)($_POST['num_guests'] ?? 1),
            'special_requests'=> trim($_POST['special_requests'] ?? ''),
        ];

        // Validate
        $errors = Booking::validate($data);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            header('Location: index.php?page=booking');
            exit;
        }

        // Calculate cost
        $cost = Booking::calculateCost($data['nights'], $data['price_per_night']);
        $data['total_cost']  = $cost['total'];
        $data['service_fee'] = $cost['service_fee'];

        // Save
        $id = Booking::create($data);
        if ($id) {
            $_SESSION['success'] = "Booking #$id submitted successfully!";
            header('Location: index.php?page=booking');
        } else {
            $_SESSION['errors'] = ['Failed to save booking. Try again.'];
            header('Location: index.php?page=booking');
        }
        exit;
    }

    // Show dashboard
    public function dashboard() {
        $status = $_GET['status'] ?? null;
        $bookings = Booking::getAll($status);
        $stats    = Booking::getStats();
        require __DIR__ . '/../views/dashboard/index.php';
    }

    // Update booking status (confirm / cancel)
    public function updateStatus() {
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if ($id && in_array($status, ['confirmed','cancelled'])) {
            Booking::updateStatus($id, $status);
            $_SESSION['success'] = "Booking #$id has been $status.";
        }
        header('Location: index.php?page=dashboard');
        exit;
    }
}
