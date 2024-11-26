<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$subject = $data['subject'];
$marks = $data['marks'];

$stmt = $conn->prepare("INSERT INTO `students` (name, subject, marks) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $name, $subject, $marks);
if ($stmt->execute()) {
    echo json_encode(["message" => "Student added successfully."]);
} else {
    echo json_encode(["message" => "Error adding student."]);
}
$stmt->close();
$conn->close();
?>
