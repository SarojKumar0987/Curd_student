<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM `students` WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(["message" => "Student deleted successfully."]);
} else {
    echo json_encode(["message" => "Error deleting student."]);
}
$stmt->close();
$conn->close();
?>
