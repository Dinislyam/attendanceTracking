<?php

class AdminReport
{
    private $pdo;
    private $allGroups = [
        'ИС-112/24',
        'ИСк-114/24',
        'ТО-115/24',
        'МЭ-117/23',
        'МР-15/22',
        'МР-16/24',
        'СВ-11/24',
        'ИС-212/23',
        'ИС-214/23',
        'ТО-215/23',
        'МЭ-217/23',
        'МЭ-219/23',
        'МР-25/23',
        'СВ-21/23',
        'ИС-312/22',
        'ИС-314/22',
        'ТО-315/22',
        'МЭ-317/22',
        'МЭ-319/22',
        'МР-35/22',
        'СВ-31/22',
        'ИС-412/21',
        'ИС-414/21',
        'ТО-413/21',
        'ТО-415/21',
        'МЭ-417/21'
    ];

    // Инъекция зависимости через конструктор
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Получаем данные о группах за конкретную дату
    public function getReportsByDate($date)
    {
        $query = "
            SELECT g.id, g.name, g.total_students, g.inplan_students, g.present_students
            FROM `groups` g
            WHERE g.report_date = :report_date
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['report_date' => $date]);

        $reports = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $groupId = $row['id'];

            // Получаем студентов с уважительными причинами
            $honorableStmt = $this->pdo->prepare("SELECT name, reason FROM honorable_students WHERE group_id = :group_id");
            $honorableStmt->execute(['group_id' => $groupId]);
            $honorableStudents = $honorableStmt->fetchAll(PDO::FETCH_ASSOC);

            // Получаем студентов с неуважительными причинами
            $unexcusedStmt = $this->pdo->prepare("SELECT name FROM unexcused_students WHERE group_id = :group_id");
            $unexcusedStmt->execute(['group_id' => $groupId]);
            $unexcusedStudents = $unexcusedStmt->fetchAll(PDO::FETCH_ASSOC);

            $reports[] = [
                'name' => $row['name'],
                'total_students' => $row['total_students'],
                'present_students' => $row['present_students'],
                'inplan_students' => $row['inplan_students'],
                'honorable_students' => $honorableStudents,
                'unexcused_students' => $unexcusedStudents
            ];
        }

        return $reports;
    }

    // Получаем группы, которые не отправили отчет
    public function getUnsentGroups($date)
    {
        $query = "
            SELECT name
            FROM `groups`
            WHERE report_date = :report_date
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['report_date' => $date]);

        $sentGroupNames = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return array_diff($this->allGroups, $sentGroupNames);
    }

    // Получаем данные о группах в указанном диапазоне дат
    public function getGroupReportsInRange($startDate, $endDate, $groupName = null)
    {
        // Переменная для хранения информации о группах по датам
        $reportData = [];
    
        // Перебор дат в диапазоне
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            (new DateTime($endDate))->modify('+1 day')
        );
    
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
    
            // Получаем группы, отправившие отчет на эту дату с полной информацией
            $sentGroups = $this->getSentGroups($formattedDate, $groupName);
    
            // Сравниваем отправившие группы со всеми группами
            $unsentGroups = array_diff($this->allGroups, array_column($sentGroups, 'name'));
    
            // Сохраняем результат для текущей даты
            $reportData[$formattedDate] = [
                'sent' => $sentGroups,
                'unsent' => $unsentGroups
            ];
        }
    
        return $reportData;
    }
    
    private function getSentGroups($date, $groupName = null)
    {
        // Подготавливаем основной запрос
        $query = "
            SELECT g.id, g.name, g.total_students, g.inplan_students, g.present_students
            FROM `groups` g
            WHERE g.report_date = :report_date
        ";
    
        // Если передано название группы, добавляем условие в запрос
        if ($groupName) {
            $query .= " AND g.name = :group_name"; // Добавляем условие для группы
        }
    
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':report_date', $date);
    
        // Если название группы передано, связываем его
        if ($groupName) {
            $stmt->bindParam(':group_name', $groupName);
        }
    
        $stmt->execute();
    
        $groups = [];
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $groupId = $row['id'];
    
            // Получаем студентов с уважительными причинами
            $honorableStmt = $this->pdo->prepare("SELECT name, reason FROM honorable_students WHERE group_id = :group_id");
            $honorableStmt->execute(['group_id' => $groupId]);
            $honorableStudents = $honorableStmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Получаем студентов с неуважительными причинами
            $unexcusedStmt = $this->pdo->prepare("SELECT name FROM unexcused_students WHERE group_id = :group_id");
            $unexcusedStmt->execute(['group_id' => $groupId]);
            $unexcusedStudents = $unexcusedStmt->fetchAll(PDO::FETCH_ASSOC);
    
            $groups[] = [
                'name' => $row['name'],
                'total_students' => $row['total_students'],
                'present_students' => $row['present_students'],
                'inplan_students' => $row['inplan_students'],
                'honorable_students' => $honorableStudents,
                'unexcused_students' => $unexcusedStudents
            ];
        }
    
        return $groups;
    }
}
