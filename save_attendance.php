<?php
header('Content-Type: application/json');

// Подключение к базе данных
require 'database.php';

$db = new Database();
$conn = $db->getConnection(); // Получаем объект PDO

// Данные из JSON
$data = json_decode(file_get_contents('php://input'), true);

// Сохранение данных в таблицу groups
$stmt = $conn->prepare("INSERT INTO `groups` (name, total_students, inplan_students, present_students, report_date) VALUES (?, ?, ?, ?, ?)");
$stmt->bindParam(1, $data['group']);
$stmt->bindParam(2, $data['totalStudents'], PDO::PARAM_INT);
$stmt->bindParam(3, $data['inplanStudents'], PDO::PARAM_INT);
$stmt->bindParam(4, $data['presentStudents'], PDO::PARAM_INT);
$stmt->bindParam(5, $data['date']);
$stmt->execute();

$group_id = $conn->lastInsertId(); // Получение ID добавленной группы

// Сохранение студентов с уважительными причинами
foreach ($data['honorableStudents'] as $student) {
    $stmt = $conn->prepare("INSERT INTO honorable_students (group_id, name, reason) VALUES (?, ?, ?)");
    $stmt->bindParam(1, $group_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $student['name']);
    $stmt->bindParam(3, $student['reason']);
    $stmt->execute();
}

// Сохранение студентов с неуважительными причинами
foreach ($data['unexcusedStudents'] as $student) {
    $stmt = $conn->prepare("INSERT INTO unexcused_students (group_id, name) VALUES (?, ?)");
    $stmt->bindParam(1, $group_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $student['name']);
    $stmt->execute();
}

// Закрытие соединения
$stmt = null;
$conn = null;

echo json_encode(['status' => 'success']);
?>
