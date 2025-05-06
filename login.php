<?php
$conn = new mysqli("localhost", "root", "", "code");
$data = json_decode(file_get_contents("php://input"), true);
$code = $conn->real_escape_string($data["code"]);

$result = $conn->query("SELECT * FROM students WHERE code = '$code'");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_scanned = $row["last_scanned"];
    $now = date("Y-m-d H:i:s");

    // Check if scanned within the last 60 seconds
    if ($last_scanned && (strtotime($now) - strtotime($last_scanned) < 60)) {
        echo json_encode(["message" => "Please wait 1 minute before scanning this QR code again."]);
    } else {
        // Update login_time and last_scanned
        $conn->query("UPDATE students SET login_time = '$now', last_scanned = '$now' WHERE code = '$code'");
        echo json_encode(["message" => "Login recorded for: " . $row["name"]]);
    }
} else {
    echo json_encode(["message" => "QR code not recognized."]);
}
?>
