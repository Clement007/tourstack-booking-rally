<?php
// ============================================================
// get_properties.php
//
// Returns all available home-stay properties from MySQL
// as a JSON array.
//
// Called by: booking.html (JavaScript fetch)
// Method: GET
// Response: JSON array of property objects
//
// Example response:
// [
//   { "id": 1, "name": "Lake View Cottage", "price_night": 12000, ... },
//   { "id": 2, "name": "Mountain Breeze", ... }
// ]
// ============================================================

// Allow requests from any origin (needed for JavaScript fetch to work)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include the database connection
include 'db.php';

// --- QUERY: Get all properties ordered by name ---
$sql    = "SELECT id, name, location, price_night, image_url, rating FROM properties ORDER BY name ASC";
$result = $conn->query($sql);

// --- BUILD ARRAY OF PROPERTIES ---
$properties = [];

if ($result && $result->num_rows > 0) {
    // Loop through each row and add it to the array
    while ($row = $result->fetch_assoc()) {
        $properties[] = [
            'id'          => (int)$row['id'],
            'name'        => $row['name'],
            'location'    => $row['location'],
            'price_night' => (float)$row['price_night'],
            'image_url'   => $row['image_url'],
            'rating'      => (float)$row['rating']
        ];
    }
}

// --- SEND JSON RESPONSE ---
echo json_encode($properties);

// Close database connection
$conn->close();
?>
