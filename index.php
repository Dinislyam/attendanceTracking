<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчетность кураторов</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="gen-groups.js"></script>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="container">
        <header class="text-center my-4">
            <h1>Отчетность кураторов</h1>
            <label for="reportDate">Дата:</label>
            <input type="date" class="form-control" id="reportDate" value="">
        </header>

        <form id="attendanceForm">
            <!-- Выбор группы генерируем с помощью js -->
            <div class="form-group">
                <label for="groupSelect">Выберите группу:</label>
                <select class="form-control" id="groupSelect" required></select>
            </div>

            <!-- Количество студентов -->
            <div class="form-group">
                <label for="totalStudents">Количество студентов в группе:</label>
                <input type="number" class="form-control" id="totalStudents" placeholder="Введите количество" required>
            </div>

            <!-- Количество студентов на ИНПЛАНЕ -->
            <div class="form-group">
                <label for="inplanStudents">Количество студентов на ИНПЛАНЕ:</label>
                <input type="number" class="form-control" id="inplanStudents" placeholder="Введите количество (0 если не указывается)" value="0">
            </div>

            <!-- Количество присутствующих -->
            <div class="form-group">
                <label for="presentStudents">Количество присутствующих на занятиях:</label>
                <input type="number" class="form-control" id="presentStudents" placeholder="Введите количество" required>
            </div>

            <h4>Студенты, пропустившие занятия по уважительной причине</h4>
            <div id="honorableAbsences"></div>
            <button type="button" class="btn btn-secondary" id="addHonorableButton">Добавить студента с уважительной причиной</button>

            <h4 class="mt-4">Студенты, пропустившие занятия по неуважительной причине</h4>
            <div id="unexcusedAbsences"></div>
            <button type="button" class="btn btn-secondary" id="addUnexcusedButton">Добавить студента без уважительной причины</button>
            <br>
            <button type="submit" class="translate-middle btn btn-primary mt-4 col-6 ">Отправить</button>
        </form>
    </div>

    <script src = "set-data.js"></script>
</body>

</html>