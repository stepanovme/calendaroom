<?php
session_start();
if(!isset($_SESSION["userId"])) {
    header("Location: auth.php");
}

$userId = $_SESSION["userId"];
$name = $_SESSION["name"];
$surname = $_SESSION["surname"];
$patronymic = $_SESSION["patronymic"];
$roleId = $_SESSION["roleId"];
$phone = $_SESSION["phone"];
$email = $_SESSION["email"];

require_once("config.php");

$sql = "SELECT * FROM role WHERE roleId = $roleId";
$result = $conn->query($sql);

if($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
    $roleName = $row['roleName'];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="/assets/favicon.png" type="image/x-icon">
    <title>Календарь</title>
</head>
<body>
    <header>
        <div class="nav-header">
            <p class="name-company">Атлант</p>
        </div>
        <div class="time">
            <p id="time">--:--</p>
        </div>
        <div class="profile">
            <img src="/assets/images/user-solid.svg" alt="">
            <div class="info">
                <p class="name"><?php echo $name .' '. $surname;?></p>
                <p class="role"><?php echo $roleName;?></p>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="nav">
            <p class="title">Личное</p>
            <a href="">Моё расписание</a>
            <a href="" class="active">Календарь</a>
            <a href="">Клиенты</a>
            <a href="">Настройки</a>
            <a href="auth.php">Выйти</a>
            <p class="title">Мои календари</p>
            <p class="active">Атлант</p>
            <p class="">Салон</p>
        </div>
        <div class="content">
            <div class="content-header">
                <a href="index.php" >Мой календарь</a>
                <a href="" class="active">Календарь компании</a>
            </div>
            <div class="calendar-title">
                <h1>Мой календарь</h1>
                <div class="duration">
                    <p>День</p>
                    <p>Неделя</p>
                    <p class="active">Месяц</p>
                </div>
            </div>
            <div class="calendar">
                <div class="calendar-header">
                    <p class="name">Июль, <span>2024</span></p>

                    <div class="navigate">
                        <img src="/assets/images/angle-left.svg" alt="">
                        <p>Сегодня</p>
                        <img src="/assets/images/angle-right.svg" alt="">
                    </div>
                </div>
                <div class="calendar-subheader">
                    <p>ПН</p>
                    <p>ВТ</p>
                    <p>СР</p>
                    <p>ЧТ</p>
                    <p>ПТ</p>
                    <p>СБ</p>
                    <p>ВС</p>
                </div>
                <div class="calendar-days">
                    <div class="cell">
                        <div class="num">1</div>
                    </div>
                    <div class="cell">
                        <div class="num">2</div>
                    </div>
                    <div class="cell">
                        <div class="num">3</div>
                    </div>
                    <div class="cell current">
                        <div class="num">4</div>
                    </div>
                    <div class="cell">
                        <div class="num">5</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">6</div> 
                    </div>
                    <div class="cell weekend">
                        <div class="num">7</div>
                    </div>

                    <div class="cell">
                        <div class="num">1</div>
                    </div>
                    <div class="cell">
                        <div class="num">2</div>
                    </div>
                    <div class="cell">
                        <div class="num">3</div>
                    </div>
                    <div class="cell">
                        <div class="num">4</div>
                    </div>
                    <div class="cell">
                        <div class="num">5</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">6</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">7</div>
                    </div>

                    <div class="cell">
                        <div class="num">1</div>
                    </div>
                    <div class="cell">
                        <div class="num">2</div>
                    </div>
                    <div class="cell">
                        <div class="num">3</div>
                    </div>
                    <div class="cell">
                        <div class="num">4</div>
                        <div class="task lightblue">
                            <div class="name">
                                <p class="name">Название события</p>
                                <p class="time">12:00-13:00</p>
                            </div>
                        </div> 
                        <div class="task lightblue">
                            <div class="name">
                                <p class="name">Название события</p>
                                <p class="time">12:00-13:00</p>
                            </div>
                        </div> 
                    </div>
                    <div class="cell">
                        <div class="num">5</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">6</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">7</div>
                    </div>

                    <div class="cell">
                        <div class="num">1</div>
                    </div>
                    <div class="cell">
                        <div class="num">2</div>
                    </div>
                    <div class="cell">
                        <div class="num">3</div>
                    </div>
                    <div class="cell">
                        <div class="num">4</div>
                    </div>
                    <div class="cell">
                        <div class="num">5</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">6</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">7</div>
                    </div>

                    <div class="cell">
                        <div class="num">1</div>
                    </div>
                    <div class="cell">
                        <div class="num">2</div>
                    </div>
                    <div class="cell">
                        <div class="num">3</div>
                    </div>
                    <div class="cell">
                        <div class="num">4</div>
                    </div>
                    <div class="cell">
                        <div class="num">5</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">6</div>
                    </div>
                    <div class="cell weekend">
                        <div class="num">7</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const options = {
                timeZone: 'Europe/Moscow',
                hour: '2-digit',
                minute: '2-digit'
            };
            const timeString = new Intl.DateTimeFormat('ru-RU', options).format(now);
            document.getElementById('time').textContent = timeString;
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>