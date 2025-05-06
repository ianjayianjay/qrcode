<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$code = $data['code'];
$field = $data['field'];

$conn = new mysqli("localhost", "root", "", "code");

if ($conn->connect_error) {
  echo json_encode(["status" => "error", "message" => "DB connection failed"]);
  exit;
}

$allowed = ["name", "login", "logout"];
if (!in_array($field, $allowed)) {
  echo json_encode(["status" => "error", "message" => "Invalid field"]);
  exit;
}

switch ($field) {
  case "name":
    $stmt = $conn->prepare("DELETE FROM students WHERE code = ?");
    break;
  case "login":
    $stmt = $conn->prepare("UPDATE students SET login_time = NULL WHERE code = ?");
    break;
  case "logout":
    $stmt = $conn->prepare("UPDATE students SET logout_time = NULL WHERE code = ?");
    break;
}

$stmt->bind_param("s", $code);
$stmt->execute();
echo json_encode(["status" => "ok", "message" => ucfirst($field) . " deleted successfully"]);
?>
