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
    http_response_code(405); // 405 = Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit;
}

// Include the database connection
include 'db.php';


$input = file_get_contents('php://input'); // raw request body
$data  = json_decode($input, true);       


if (!$data) {
    http_response_code(400); // 400 = Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data received.']);
    exit;
}

// ---------------------------------------------------------------
// STEP 2: Extract and sanitize each field
// We use trim() to remove extra spaces, and we'll use prepared
// statements below to prevent SQL Injection attacks.
// ---------------------------------------------------------------
$guest_name      = trim($data['guest_name']      ?? '');
$guest_phone     = trim($data['guest_phone']     ?? '');
$special_requests = trim($data['special_requests'] ?? '');
$property_id     = (int)($data['property_id']    ?? 0);
$property_name   = trim($data['property_name']   ?? '');
$check_in        = trim($data['check_in']        ?? '');
$check_out       = trim($data['check_out']       ?? '');
$nights          = (int)($data['nights']         ?? 0);
$price_per_night = (float)($data['price_per_night'] ?? 0);


$errors = [];

if (empty($guest_name))               $errors[] = 'Guest name is required.';
if (empty($guest_phone))              $errors[] = 'Phone number is required.';
if ($property_id <= 0)                $errors[] = 'A valid property must be selected.';
if (empty($check_in))                 $errors[] = 'Check-in date is required.';
if (empty($check_out))                $errors[] = 'Check-out date is required.';
if ($nights < 1)                      $errors[] = 'Number of nights must be at least 1.';
if ($price_per_night <= 0)            $errors[] = 'Price per night must be greater than 0.';
if ($check_in >= $check_out)          $errors[] = 'Check-out must be after check-in.';

// If there are any errors, return them all at once
if (!empty($errors)) {
    http_response_code(422); // 422 = Unprocessable Entity
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$service_fee = round($nights * $price_per_night * 0.05, 2); 
$total_cost  = round(($nights * $price_per_night) + $service_fee, 2);


$sql = "INSERT INTO bookings
          (guest_name, guest_phone, special_requests,
           property_id, property_name,
           check_in, check_out, nights,
           price_per_night, service_fee, total_cost, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

// Prepare the statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
    exit;
}

$stmt->bind_param(
    'sssissiiddd',
    $guest_name,
    $guest_phone,
    $special_requests,
    $property_id,
    $property_name,
    $check_in,
    $check_out,
    $nights,
    $price_per_night,
    $service_fee,
    $total_cost
);

// Execute the INSERT
if ($stmt->execute()) {
    // Get the new booking's auto-generated ID
    $new_booking_id = $conn->insert_id;

    // Return success response
    echo json_encode([
        'success'    => true,
        'message'    => 'Booking submitted successfully! Awaiting owner confirmation.',
        'booking_id' => $new_booking_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save booking: ' . $stmt->error]);
}

// Clean up
$stmt->close();
$conn->close();
?>
