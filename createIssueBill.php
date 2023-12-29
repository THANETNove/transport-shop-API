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
    $id = $_POST['id'];
    $id_address = $_POST['id_address'];
    $data = $_POST['data'];
    $point = $_POST['point'];
    $dataArray = json_decode($data, true);

    // Extract and store the 'id' values in a new array
    $idArray = array();
    foreach ($dataArray as $item) {
        if (isset($item['id'])) {
            $idArray[] = $item['id'];
        }
    }

    $status = "รอตรวจสอบ";

    $stmt = $conn->prepare("INSERT INTO bill (id_user, id_address, id_product, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $id_address, json_encode($idArray), $status);

    if ($stmt->execute()) {
        $insertedId = $conn->insert_id;

        foreach ($idArray as $individualId) {
            $stmt2 = $conn->prepare("UPDATE product SET billing_id = ? WHERE id = ?");
            $stmt2->bind_param("ss", $insertedId, $individualId);

            // Execute the statement for each individual ID
            if ($stmt2->execute()) {
              /*   echo json_encode(['message' => 'product update successfully!']); */
            } else {
                echo json_encode(['error' => 'Error updating product']);
            }

            // Close the statement
            $stmt2->close();
        }

    
        $stmt3 = $conn->prepare("UPDATE users SET money = ? WHERE id = ?");
        $stmt3->bind_param("ii", $point, $id);
        $stmt3->execute();
        $stmt3->close();


        
        echo json_encode(['message' => 'product update successfully!']);
    } else {
        echo json_encode(['error' => 'Error updating bill']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();