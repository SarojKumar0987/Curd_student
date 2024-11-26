<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$name = $data['name'];
$subject = $data['subject'];
$marks = $data['marks'];

$stmt = $conn->prepare("UPDATE `students` SET name=?, subject=?, marks=? WHERE id=?");
$stmt->bind_param("ssii", $name, $subject, $marks, $id);
if ($stmt->execute()) {
    echo json_encode(["message" => "Student updated successfully."]);
} else {
    echo json_encode(["message" => "Error updating student."]);
}
$stmt->close();
$conn->close();
?>
