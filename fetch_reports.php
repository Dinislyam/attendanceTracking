<?php
require_once 'Database.php';
require_once 'AdminReport.php'; // Подключаем класс AdminReport
header('Content-Type: application/json');

$database = new Database();
$pdo = $database->getConnection();


$adminReport = new AdminReport($pdo);

// Получаем дату из запроса, если она не указана, используем текущую дату
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Получаем отчеты и группы, не отправившие отчеты
$reports = $adminReport->getReportsByDate($date);
$unsentGroups = $adminReport->getUnsentGroups($date);

// Возвращаем данные в формате JSON
header('Content-Type: application/json'); // Устанавливаем заголовок для JSON
echo json_encode(['reports' => $reports, 'unsentGroups' => $unsentGroups]);

echo 'sad';
?>
