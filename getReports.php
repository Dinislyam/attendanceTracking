<?php
require_once 'Database.php';
require_once 'AdminReport.php';

// Проверка, что это AJAX-запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Создаем экземпляр класса Database для подключения к базе данных
    $db = new Database();
    $pdo = $db->getConnection();

    // Создаем экземпляр класса AdminReport
    $adminReport = new AdminReport($pdo);

    // Получаем данные из POST-запроса
    $groupName = $_POST['groupName'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Получаем отчеты для заданного диапазона дат и названия группы
    $reportData = $adminReport->getGroupReportsInRange($startDate, $endDate, $groupName); // Здесь измените метод, если нужно

    // Генерируем HTML для отчетов
    ob_start();
    ?>
    <h1>Отчеты групп с <?php echo htmlspecialchars($startDate); ?> по <?php echo htmlspecialchars($endDate); ?></h1>

    <?php if (!empty($reportData)): ?>
        <?php foreach ($reportData as $date => $data): ?>
            <h2><?php echo htmlspecialchars($date); ?></h2>
            <h3>Отправленные группы:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Название группы</th>
                        <th>Всего студентов</th>
                        <th>Присутствующие</th>
                        <th>Студенты на ИНПЛАНЕ</th>
                        <th>Уважительные причины</th>
                        <th>Неуважительные причины</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['sent'] as $group): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($group['name']); ?></td>
                            <td><?php echo htmlspecialchars($group['total_students']); ?></td>
                            <td><?php echo htmlspecialchars($group['present_students']); ?></td>
                            <td><?php echo htmlspecialchars($group['inplan_students']); ?></td>
                            <td>
                                <?php
                                if (!empty($group['honorable_students'])) {
                                    echo implode(', ', array_map(function ($student) {
                                        return htmlspecialchars($student['name']) . " (Причина: " . htmlspecialchars($student['reason']) . ")";
                                    }, $group['honorable_students']));
                                } else {
                                    echo 'Нет';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($group['unexcused_students'])) {
                                    echo implode(', ', array_map(function ($student) {
                                        return htmlspecialchars($student['name']);
                                    }, $group['unexcused_students']));
                                } else {
                                    echo 'Нет';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Группы, которые не отправили отчет:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Группы, которые не отправили отчет</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <ul class="group-list">
                                <?php foreach ($data['unsent'] as $groupName): ?>
                                    <li><?php echo htmlspecialchars($groupName); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Нет доступных отчетов за указанный период.</p>
    <?php endif; ?>

    <style>
        .group-list {
            list-style-type: none; /* Убираем маркеры */
            padding: 0; /* Убираем отступы */
            margin: 0; /* Убираем поля */
        }
        .group-list li {
            display: inline; /* Выводим элементы в строку */
            margin-right: 10px; /* Отступ между элементами */
        }
    </style>

    <?php
    // Возвращаем сгенерированный HTML
    echo ob_get_clean();
}
?>
