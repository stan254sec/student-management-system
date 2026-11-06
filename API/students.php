<?php
require_once "../db.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(
        file_get_contents("php://input"),
        true
    );

    $reg_no    = $input['reg_no'];
    $full_name = $input['full_name'];
    $course    = $input['course'];
    $gender    = $input['gender'];
    $email     = $input['email'];
    $phone     = $input['phone'];

    $stmt = $conn->prepare(
        "INSERT INTO students
         (reg_no, full_name, course, gender, email, phone)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "ssssss",
        $reg_no,
        $full_name,
        $course,
        $gender,
        $email,
        $phone
    );
    $stmt->execute();

    http_response_code(201);
    echo json_encode([
        "success" => true,
        "message" => "Student created successfully",
        "id" => $stmt->insert_id
    ]);
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare(
        "SELECT id, reg_no, full_name, course, gender, email, phone, photo
         FROM students
         WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "Student not found"
        ]);
        exit;
    }

    $student = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "data" => $student
    ]);
    exit;
}

$sql = "SELECT id, reg_no, full_name, course, gender, email, phone, photo
        FROM students";
$result = $conn->query($sql);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $students
]);