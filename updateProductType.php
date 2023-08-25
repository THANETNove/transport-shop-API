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

if (isset($_POST['isAdd']) && $_POST['isAdd'] == 'true') {
    $id = $_POST['id']; // assuming you're passing the ID for the product you want to update
    $name = $_POST['name'];
    $kg = $_POST['kg'];
    $cbm = $_POST['cbm'];

    $stmt = $conn->prepare("UPDATE product_type SET name = ?, kg = ?, cbm = ? WHERE id = ?");
    $stmt->bind_param("ssss", $name, $kg, $cbm, $id);


    if ($stmt->execute()) {
        $select_sql = "SELECT * FROM product_type";
        $stmt = $conn->prepare($select_sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_array = [];
        while ($row = $result->fetch_assoc()) {
            $data_array[] = $row;
        }

        echo json_encode(['message' => 'product type added successfully!', 'product_type_data' => $data_array]);
    } else {
        echo json_encode(['error' => 'Error adding product type.']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
