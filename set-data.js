$(document).ready(function() {
    // Устанавливаем сегодняшнюю дату по умолчанию
    $('#reportDate').val(new Date().toISOString().split('T')[0]);

    class StudentAbsence {
        constructor(type) {
            this.type = type; // Тип пропуска: "honorable" или "unexcused"
            this.count = 0; // Счетчик студентов
        }

        createAbsenceForm() {
            this.count++;
            return $(`
                <div class="student-form">
                    <div class="form-group">
                        <label for="${this.type}Name${this.count}">ФИО студента:</label>
                        <input type="text" class="form-control" id="${this.type}Name${this.count}" placeholder="Введите ФИО" required>
                    </div>
                    ${this.type === 'honorable' ? this.createReasonSelect() : ''}
                    <button type="button" class="btn btn-danger" onclick="removeStudentForm(this)">Удалить студента</button>
                </div>
            `);
        }

        createReasonSelect() {
            return `
                <div class="form-group">
                    <label for="${this.type}Reason${this.count}">Причина пропуска:</label>
                    <select class="form-control" id="${this.type}Reason${this.count}" required>
                        <option value="">Выберите причину</option>
                        <option value="Заявление">Заявление</option>
                        <option value="Больничный">Больничный</option>
                        <option value="Приказ о снятии с пар">Приказ о снятии с пар</option>
                        <option value="Военкомат">Военкомат</option>
                    </select>
                </div>
            `;
        }

        getAbsenceData() {
            const students = [];
            for (let i = 1; i <= this.count; i++) {
                const name = $(`#${this.type}Name${i}`).val();
                const reason = this.type === 'honorable' ? $(`#${this.type}Reason${i}`).val() : "Без причины";
                if (name) {
                    students.push({ name, reason });
                }
            }
            return students;
        }
    }

    // Удаление формы студента
    window.removeStudentForm = function(button) {
        $(button).closest('.student-form').remove();
    };

    const honorableAbsence = new StudentAbsence('honorable');
    const unexcusedAbsence = new StudentAbsence('unexcused');

    $('#addHonorableButton').click(function() {
        $('#honorableAbsences').append(honorableAbsence.createAbsenceForm());
        scrollToBottom();
    });

    $('#addUnexcusedButton').click(function() {
        $('#unexcusedAbsences').append(unexcusedAbsence.createAbsenceForm());
        scrollToBottom();
    });

    $('#attendanceForm').submit(function(event) {
        event.preventDefault(); // Предотвращаем отправку формы

        const data = {
            date: $('#reportDate').val(),
            group: $('#groupSelect').val(),
            totalStudents: $('#totalStudents').val(),
            inplanStudents: $('#inplanStudents').val(),
            presentStudents: $('#presentStudents').val(),
            honorableStudents: honorableAbsence.getAbsenceData(),
            unexcusedStudents: unexcusedAbsence.getAbsenceData()
        };

        // Формируем строку для предварительного просмотра
        const previewData = `
            <h3>Предварительный просмотр данных</h3>
            <p><strong>Дата:</strong> ${data.date}</p>
            <p><strong>Группа:</strong> ${data.group}</p>
            <p><strong>Всего студентов:</strong> ${data.totalStudents}</p>
            <p><strong>Студенты на ИНПЛАНЕ:</strong> ${data.inplanStudents}</p>
            <p><strong>Присутствующие:</strong> ${data.presentStudents}</p>
            <h4>Студенты с уважительной причиной:</h4>
            <ul>${data.honorableStudents.map(s => `<li>${s.name} (${s.reason})</li>`).join('')}</ul>
            <h4>Студенты без уважительной причины:</h4>
            <ul>${data.unexcusedStudents.map(s => `<li>${s.name}</li>`).join('')}</ul>
        `;

        // Создаем модальное окно для предварительного просмотра
        const $modal = $(`
            <div class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Предварительный просмотр</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">${previewData}</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary" id="confirmSubmit">Подтвердить отправку</button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('body').append($modal);
        $modal.modal('show');

        $('#confirmSubmit').click(function() {
            // Отправка данных на сервер
            fetch('save_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                console.log('Успех:', result);
                // Уведомление об успешной отправке
                alert('Данные успешно отправлены!');

                // Очистка полей формы
                $('#attendanceForm')[0].reset();
                $('.student-form').remove(); // Удаление всех форм студентов
            })
            .catch(error => console.error('Ошибка:', error));

            $modal.modal('hide'); // Скрыть модальное окно
        });
    });

    // Прокрутка вниз страницы
    function scrollToBottom() {
        $('html, body').animate({
            scrollTop: $(document).height()
        }, 500);
    }
});
