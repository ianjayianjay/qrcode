<?php
$conn = new mysqli("localhost", "root", "", "code");

$result = $conn->query("SELECT * FROM students ORDER BY name ASC");
$students = [];

while ($row = $result->fetch_assoc()) {
  $students[] = $row;
}

echo json_encode($students);
?>
