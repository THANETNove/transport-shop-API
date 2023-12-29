<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['isAdd']) && $_GET['isAdd'] == 'true') {
    $id = $_GET['id'];
    $select_sql = "SELECT
    product.*  ,
    bill.id as billId,
    bill.created_at as billCreated_at,
    bill.updated_at as billUpdated_at,
    bill.id_user,
    bill.id_address,
    bill.id_product,
    bill.status,
    bill.image as billImage,
    address.id as addressId,
    address.username,
    address.tel,
    address.address,
    address.subdistricts,
    address.districts,
    address.provinces,
    address.zip_code
FROM product
 LEFT JOIN bill ON  product.billing_id = bill.id 
 LEFT JOIN address ON bill.id_address = address.id
 WHERE bill.id_user=?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $id); // Assuming 'id' is an integer in your database
    $stmt->execute();
    $result = $stmt->get_result();
    $data_array = [];
    while ($row = $result->fetch_assoc()) {
        $data_array[] = $row;
    }

    echo json_encode(['message' => 'product type get successfully!', 'data' => $data_array]);
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();