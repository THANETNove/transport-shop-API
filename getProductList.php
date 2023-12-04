<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$select_sql = "SELECT * FROM product WHERE billing_status IS NULL ORDER BY id DESC";
$stmt = $conn->prepare($select_sql);


if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data_array = [];
    while ($row = $result->fetch_assoc()) {
        $data_array[] = $row;
    }

    echo json_encode(['message' => 'product get successfully!', 'product_data' => $data_array]);
} else {
    echo json_encode(['error' => 'Error fetching product data.']);
}

$stmt->close();
$conn->close();