<?php
$conn = new mysqli("localhost", "root", "", "code");
$data = json_decode(file_get_contents("php://input"));
$code = $conn->real_escape_string($data->code);

$res = $conn->query("SELECT * FROM students WHERE code = '$code'");
if ($res->num_rows == 0) {
  echo json_encode(["message" => "Student not found."]);
  exit;
}

$row = $res->fetch_assoc();
$name = $row['name'];

if ($row['status'] == 'logged_out') {
  $conn->query("UPDATE students SET login_time = NOW(), status = 'logged_in', logout_time = NULL WHERE code = '$code'");
  echo json_encode(["message" => "$name logged in at " . date("H:i:s")]);
} else {
  $conn->query("UPDATE students SET logout_time = NOW(), status = 'logged_out' WHERE code = '$code'");
  echo json_encode(["message" => "$name logged out at " . date("H:i:s")]);
}
?>
