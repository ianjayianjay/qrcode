<?php
$conn = new mysqli("localhost", "root", "", "code");

$data = json_decode(file_get_contents("php://input"));
$name = $conn->real_escape_string($data->name);
$code = $conn->real_escape_string($data->code);

// Check if student already exists
$res = $conn->query("SELECT * FROM students WHERE code = '$code'");
if ($res->num_rows > 0) {
  echo json_encode(["status" => "exists", "message" => "Student already exists."]);
  exit;
}

$conn->query("INSERT INTO students (name, code) VALUES ('$name', '$code')");
echo json_encode(["status" => "ok", "message" => "Student added successfully."]);
?>
