<?php


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST allowed.']);
    exit;
}

include 'db.php';

$input = file_get_contents('php://input');
$data  = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON.']);
    exit;
}

$booking_id = (int)($data['booking_id'] ?? 0);
$new_status = trim($data['status'] ?? '');

$allowed_statuses = ['pending', 'confirmed', 'cancelled'];

if ($booking_id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
    exit;
}

if (!in_array($new_status, $allowed_statuses)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid status value. Must be: pending, confirmed, or cancelled.']);
    exit;
}

$check = $conn->prepare("SELECT id, status FROM bookings WHERE id = ?");
$check->bind_param('i', $booking_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows === 0) {
    http_response_code(404); 
    echo json_encode(['success' => false, 'message' => "Booking #$booking_id not found."]);
    $check->close();
    $conn->close();
    exit;
}

$existing = $check_result->fetch_assoc();
$check->close();

$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->bind_param('si', $new_status, $booking_id);

if ($stmt->execute()) {
   
    $messages = [
        'confirmed' => "✅ Booking #$booking_id has been confirmed!",
        'cancelled' => "❌ Booking #$booking_id has been cancelled.",
        'pending'   => "↩️ Booking #$booking_id has been moved back to pending."
    ];

    echo json_encode([
        'success' => true,
        'message' => $messages[$new_status],
        'booking_id' => $booking_id,
        'new_status' => $new_status
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
