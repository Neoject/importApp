//логика для модальных окон с данными из бд
let logFrame = document.getElementById('logs');
let openLogBtn = document.getElementById('open-log');
let closeLogBtn = document.getElementById('close-log');

openLogBtn.addEventListener('click', function () {
    logFrame.style.display = 'block';
});

closeLogBtn.addEventListener('click', function () {
    logFrame.style.display = 'none';
});

let departmentsFrame = document.getElementById('departments');
let openDepartmentsBtn = document.getElementById('open-departments');
let closeDepartmentsBtn = document.getElementById('close-departments');

openDepartmentsBtn.addEventListener('click', function () {
    departmentsFrame.style.display = 'block';
});

closeDepartmentsBtn.addEventListener('click', function () {
    departmentsFrame.style.display = 'none';
});

let usersFrame = document.getElementById('users');
let openUsersBtn = document.getElementById('open-users');
let closeUsersBtn = document.getElementById('close-users');

openUsersBtn.addEventListener('click', function () {
    usersFrame.style.display = 'block';
});

closeUsersBtn.addEventListener('click', function () {
    usersFrame.style.display = 'none';
});
//ajax запрос для закгрузки файлов и вывода результата об успешной загрузке или же ошибки
$(document).ready(function() {
    $('#import-form').submit(function(e) {
        e.preventDefault();
        let formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: 'db-process.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });
});
