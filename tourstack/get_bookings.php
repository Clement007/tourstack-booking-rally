<?php
// ============================================================
// get_bookings.php
//
// Returns all bookings from MySQL for the owner dashboard.
// Supports filtering by status and searching by guest name.
//
// Called by: dashboard.html (JavaScript fetch)
// Method: GET
// URL params:
//   ?status=pending     (filter by status: pending/confirmed/cancelled/all)
//   ?search=Amina       (search by guest name or property)
// Response: JSON { success, bookings[], stats{} }
// ============================================================

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include 'db.php';

// ---------------------------------------------------------------
// STEP 1: Read query parameters from the URL
// e.g. get_bookings.php?status=pending&search=Amina
// ---------------------------------------------------------------

// Get status filter — default to 'all' if not provided
// We use htmlspecialchars() to prevent XSS attacks
$status_filter = htmlspecialchars($_GET['status'] ?? 'all');
$search        = htmlspecialchars($_GET['search'] ?? '');

// ---------------------------------------------------------------
// STEP 2: Build the SQL query dynamically
// We add WHERE clauses only if filters are set
// ---------------------------------------------------------------
$sql    = "SELECT * FROM bookings WHERE 1=1"; // "1=1" is always true, lets us add AND clauses easily
$params = [];  // values to bind
$types  = '';  // type string for bind_param

// Add status filter if not 'all'
if ($status_filter !== 'all' && in_array($status_filter, ['pending','confirmed','cancelled'])) {
    $sql   .= " AND status = ?";
    $params[] = $status_filter;
    $types   .= 's';
}

// Add search filter if provided
if (!empty($search)) {
    $like = '%' . $search . '%'; // % is SQL wildcard: matches any characters
    $sql   .= " AND (guest_name LIKE ? OR property_name LIKE ?)";
    $params[] = $like;
    $params[] = $like;
    $types   .= 'ss';
}

// Order newest bookings first
$sql .= " ORDER BY created_at DESC";

// ---------------------------------------------------------------
// STEP 3: Execute the query
// ---------------------------------------------------------------
$stmt = $conn->prepare($sql);

// Only bind parameters if there are any
if (!empty($params)) {
    // bind_param needs variables passed by reference, not array values
    // We use a trick with array_unshift and call_user_func_array
    $bind_args = array_merge([$types], $params);
    $refs = [];
    foreach ($bind_args as $key => $val) {
        $refs[$key] = &$bind_args[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);
}

$stmt->execute();
$result = $stmt->get_result();

// ---------------------------------------------------------------
// STEP 4: Build bookings array from results
// ---------------------------------------------------------------
$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = [
        'id'              => $row['id'],
        'guest_name'      => $row['guest_name'],
        'guest_phone'     => $row['guest_phone'],
        'special_requests'=> $row['special_requests'],
        'property_id'     => $row['property_id'],
        'property_name'   => $row['property_name'],
        'check_in'        => $row['check_in'],
        'check_out'       => $row['check_out'],
        'nights'          => (int)$row['nights'],
        'price_per_night' => (float)$row['price_per_night'],
        'service_fee'     => (float)$row['service_fee'],
        'total_cost'      => (float)$row['total_cost'],
        'status'          => $row['status'],
        'created_at'      => $row['created_at']
    ];
}

// ---------------------------------------------------------------
// STEP 5: Get stats (count by status) for the summary cards
// These are always based on ALL bookings, not the filtered set
// ---------------------------------------------------------------
$stats_sql    = "SELECT status, COUNT(*) as count FROM bookings GROUP BY status";
$stats_result = $conn->query($stats_sql);

$stats = ['total' => 0, 'pending' => 0, 'confirmed' => 0, 'cancelled' => 0];

while ($row = $stats_result->fetch_assoc()) {
    $stats[$row['status']] = (int)$row['count'];
    $stats['total'] += (int)$row['count'];
}

// ---------------------------------------------------------------
// STEP 6: Return JSON response
// ---------------------------------------------------------------
echo json_encode([
    'success'  => true,
    'bookings' => $bookings,
    'stats'    => $stats
]);

$stmt->close();
$conn->close();
?>
