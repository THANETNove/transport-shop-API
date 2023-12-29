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
    $select_sql = "SELECT
    product.*,
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
    address.zip_code,
    users.customerCode
FROM bill
LEFT JOIN product ON bill.id = product.billing_id
 JOIN address ON bill.id_address = address.id
 JOIN users ON bill.id_user = users.id  ORDER BY bill.updated_at DESC";
   /*  $select_sql = "SELECT  product.*, bill.id as billId, bill.created_at as billCreated_at, bill.updated_at as billUpdated_at,
    bill.id_user,bill.id_address, bill.id_product, bill.status FROM bill LEFT JOIN product ON bill.id = product.billing_id WHERE bill.id_user=?"; */
    $stmt = $conn->prepare($select_sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_array = [];
    while ($row = $result->fetch_assoc()) {
        $billId = $row['billId'];
        $status = $row['status'];
        $billUpdated_at = $row['billUpdated_at'];
        $tel = $row['tel'];
        $address = $row['address'];
        $subdistricts = $row['subdistricts'];
        $districts = $row['districts'];
        $provinces = $row['provinces'];
        $zip_code = $row['zip_code'];
        $username = $row['username'];
        $billImage = $row['billImage'];
        $customerCode = $row['customerCode'];

    
        // If the billId is not yet in the data_array, add it with an empty array
        if (!isset($data_array[$billId])) {
            $data_array[$billId] = [
                'billId' => $billId,
                'status' => $status,
                'tel' => $tel,
                'address' => $address,
                'subdistricts' => $subdistricts,
                'districts' => $districts,
                'provinces' => $provinces,
                'zip_code' => $zip_code,
                'username' => $username,
                'billImage' => $billImage,
                'billUpdated_at' => $billUpdated_at,
                'customerCode' => $customerCode,
                'dataBill' => [],
            ];
        }
    
        // Add the data to the 'dataBill' array
        $data_array[$billId]['dataBill'][] = $row;
    }

    echo json_encode(['message' => 'product type get successfully!', 'data' => $data_array]);
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();