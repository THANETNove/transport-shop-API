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
    $code_user = $_POST['code_user']; // assuming you're passing the ID for the product you want to update
    $money = $_POST['money'];
    $status = $_POST['status'];
    
if ($status == "อนุมัติ") {
    $select_sql = "SELECT money FROM users WHERE customerCode = ?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("s", $code_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_money = 0;
    while ($row = $result->fetch_assoc()) {
        $total_money = $row['money'] + $money;
    }

   
    $stmt_users = $conn->prepare("UPDATE users SET money = ? WHERE customerCode = ?");
    $stmt_users->bind_param("ss", $total_money, $code_user);
      // Execute $stmt_users only when $status is "อนุมัติ"
    if ($stmt_users->execute()) {
        echo json_encode(['message_money' => 'users update successfully!']);
    } else {
        echo json_encode(['error' => 'Error update users: ' . $stmt_users->error]);
    }
}
  
  
    $stmt_slip = $conn->prepare("UPDATE slip SET  statusSlip = ? WHERE id = ?");
    $stmt_slip->bind_param("ss",$status, $id);


    if ($stmt_slip->execute()) {

        echo json_encode(['message' => 'price_per_user update successfully!']);
    } else {
        echo json_encode(['error' => 'Error update price_per_user']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();