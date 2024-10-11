<?php
require_once 'Database.php';
require_once 'AdminReport.php';

$database = new Database();
$pdo = $database->getConnection();

$adminReport = new AdminReport($pdo);
$currentDate = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрация</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
        }
        table {
            width: 100%;
        }
        th, td {
            padding: 0.5rem;
            text-align: left;
        }
        h6 {
            font-weight: bold;
            margin-top: 10px;
        }
        ul.list-group {
            margin-bottom: 15px;
        }
        ul.list-group-item {
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">
            Отчеты групп за <input type="date" id="reportDate" value="<?php echo $currentDate; ?>" class="form-control d-inline w-auto">
        </h1>

        <div id="reportContent">
            <!-- Содержимое отчета будет загружаться сюда -->
             
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        document.getElementById('reportDate').addEventListener('change', function () {
            const selectedDate = this.value;

            // Отправляем запрос на сервер для получения отчетов за выбранную дату
            fetch(`fetch_reports.php?date=${selectedDate}`)
                .then(response => {
                    // Проверяем, вернулся ли ответ корректный
                    if (!response.ok) {
                        throw new Error('Сеть ответила с ошибкой: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Полученные данные:', data); // Отладка: выводим данные в консоль
                    const reportContent = document.getElementById('reportContent');
                    reportContent.innerHTML = '';

                    if (data.reports.length > 0) {
                        data.reports.forEach(report => {
                            reportContent.innerHTML += `
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">${report.name}</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-sm">
                                            <tr><th>Всего студентов</th><td>${report.total_students}</td></tr>
                                            <tr><th>Присутствующих</th><td>${report.present_students}</td></tr>
                                            <tr><th>На ИНПЛАНЕ</th><td>${report.inplan_students}</td></tr>
                                        </table>
                                        <h6 class="mt-3">Отсутствующие по уважительной причине:</h6>
                                        <ul class="list-group mb-3">${report.honorable_students.length > 0 ? report.honorable_students.map(student => `<li class="list-group-item">${student.name} (${student.reason})</li>`).join('') : '<li class="list-group-item">Нет отсутствующих по уважительной причине.</li>'}</ul>
                                        <h6>Отсутствующие по неуважительной причине:</h6>
                                        <ul class="list-group">${report.unexcused_students.length > 0 ? report.unexcused_students.map(student => `<li class="list-group-item">${student.name}</li>`).join('') : '<li class="list-group-item">Нет отсутствующих по неуважительной причине.</li>'}</ul>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        reportContent.innerHTML = '<p>Нет отчетов за выбранную дату.</p>';
                    }

                    // Группы, не отправившие отчеты
                    const unsentGroups = Object.values(data.unsentGroups);
                    console.log('Неотправленные группы:',unsentGroups); // Отладка: выводим неотправленные группы
                    reportContent.innerHTML += '<h2 class="mb-3">Группы, не отправившие отчеты</h2>';
                    // console.log(unsentGroups,"fddfdsfsd" )
                    if (unsentGroups.length > 0) {
                        const groupList = unsentGroups.map(group => `<li class="list-group-item">${group}</li>`).join('');
                        reportContent.innerHTML += `<ul class="list-group">${groupList}</ul>`;
                    } else {
                        console.log()
                        reportContent.innerHTML += '<p>Все группы отправили отчеты.</p>';
                    }
                })
                .catch(error => console.error('Ошибка загрузки отчета:', error));
        });
    </script>
</body>

</html>