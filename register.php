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
    $username = $_POST['username'];
    $customerCode = $_POST['customerCode'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name_surname = $_POST['name_surname'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $subdistrict = $_POST['subdistrict'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $zipCode = $_POST['zipCode'];



    // Check for duplicate username
    $check_username_sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_username_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $username_result = $stmt->get_result();

    // Check for duplicate email
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $email_result = $stmt->get_result();

    if ($username_result->num_rows > 0) {
        echo json_encode(['error' => 'Username already exists!']);
    } elseif ($email_result->num_rows > 0) {
        echo json_encode(['error' => 'Email already exists!']);
    } else {
        // If no duplicates found, insert the new user
        $stmt = $conn->prepare("INSERT INTO users (username, customerCode, email, password, name_surname, phone_number, address, subdistrict, district, province, zipCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $username, $customerCode, $email, $password, $name_surname, $phone_number, $address, $subdistrict, $district, $province, $zipCode);


        if ($stmt->execute()) {
            echo json_encode(['message' => 'User added successfully!']);
        } else {
            echo json_encode(['error' => 'Error adding user.']);
        }
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
