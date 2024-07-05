<?php
session_start();
if (!isset($_SESSION["userId"])) {
    header("Location: auth.php");
}

$userId = $_SESSION["userId"];
require_once("config.php");

$sql = "SELECT * FROM user WHERE userId = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
    $roleId = $row['roleId'];
    $name = $row['name'];
    $surname = $row['surname'];
    $patronymic = $row['patronymic'];
    $email = $row['email'];
    $phone = $row['phone'];
}

$sql = "SELECT * FROM role WHERE roleId = $roleId";
$result = $conn->query($sql);

if ($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
    $roleName = $row['roleName'];
}

$sql = "SELECT * FROM companyUser WHERE userId = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
    $companyId = $row['companyId'];
}

// Выборка техники для компании
$sql = "SELECT t.*, ct.companyId FROM technic t 
        JOIN companyTechnic ct ON t.technicId = ct.technicId 
        WHERE ct.companyId = $companyId";
$result = $conn->query($sql);
$technics = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $technics[] = $row;
    }
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
    <link rel="stylesheet" href="css/technic.css">
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
            <a href="index.php">Календарь</a>
            <a href="">Клиенты</a>
            <a href="" class="active">Спецтехника</a>
            <a href="">Настройки</a>
            <a href="auth.php">Выйти</a>
            <p class="title">Мои календари</p>
            <p class="active">Атлант</p>
            <p class="">Салон</p>
        </div>
        <div class="content">
            <div class="content-header">
                <a href="" class="active">Все</a>
                <a href="">Эсковатор-погрузчик</a>
            </div>
            <div class="technik-title">
                <h1>Спецтехника</h1>
                <button id="addTech">Добавить</button>
            </div>
            <div class="technics" id="technics">
                <?php foreach ($technics as $technic) { ?>
                <div class="card">
                    <img src="/assets/images/traktor.png" alt="">
                    <p class="name"><?php echo $technic['name']; ?></p>
                    <p class="engine">Двигатель: <span><?php echo $technic['engineModel']; ?></span></p>
                    <p class="power">Мощность двигателя: <span><?php echo $technic['enginePower']; ?> л.с.</span></p>
                    <p class="weight">Объём переднего ковша: <span><?php echo $technic['frontBucketVolume']; ?> м³</span></p>
                    <p class="volume">Объём заднего ковша: <span><?php echo $technic['backBucketVolume']; ?> м³</span></p>
                    <p class="digging-depth">Глубина копания: <span><?php echo $technic['maxDiggingDepth']; ?> м³</span></p>
                    <div class="overlay">
                        <button class="view-button" data-id="<?php echo $technic['technicId']; ?>">Посмотреть</button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="modal" id="modal">
        <div class="content">
            <p class="modal-title">Добавить спецтехнику</p>
            <form id="addTechnicForm">
                <label for="technicType">Тип спецтехники</label>
                <select id="technicType">
                    <option value="1">Эсковатор-погрузчик</option>
                </select>
                <label for="technicName">Название</label>
                <input type="text" id="technicName" placeholder="Большой экскаватор SWE335F">
                <label for="technicWeight">Масса, кг</label>
                <input type="text" id="technicWeight" placeholder="9300">
                <label for="technicCapacity">Грузоподъёмность, кг</label>
                <input type="text" id="technicCapacity" placeholder="2500">
                <label for="technicEngineModel">Модель двигателя</label>
                <input type="text" id="technicEngineModel" placeholder="Yuchai">
                <label for="technicEnginePower">Мощность двигателя, л.с.</label>
                <input type="text" id="technicEnginePower" placeholder="102">
                <label for="technicFrontBucketVolume">Объём переднего ковша, м<sup>3</sup></label>
                <input type="text" id="technicFrontBucketVolume" placeholder="1.2">
                <label for="technicBackBucketVolume">Объём заднего ковша, м<sup>3</sup></label>
                <input type="text" id="technicBackBucketVolume" placeholder="0.3">
                <label for="technicDiggingDepth">Макс. глубина копания, м<sup>3</sup></label>
                <input type="text" id="technicDiggingDepth" placeholder="4.85">
                <button type="submit">Добавить</button>
            </form>
        </div>
    </div>

    <div class="modal2">
        <div class="content">
            <p class="modal-title">Добавить спецтехнику</p>
            <form id="addTechnicForm">
                <label for="technicType">Тип спецтехники</label>
                <select id="technicType">
                    <option value="1">Эсковатор-погрузчик</option>
                </select>
                <label for="technicName">Название</label>
                <input type="text" id="technicName" placeholder="Большой экскаватор SWE335F">
                <label for="technicWeight">Масса, кг</label>
                <input type="text" id="technicWeight" placeholder="9300">
                <label for="technicCapacity">Грузоподъёмность, кг</label>
                <input type="text" id="technicCapacity" placeholder="2500">
                <label for="technicEngineModel">Модель двигателя</label>
                <input type="text" id="technicEngineModel" placeholder="Yuchai">
                <label for="technicEnginePower">Мощность двигателя, л.с.</label>
                <input type="text" id="technicEnginePower" placeholder="102">
                <label for="technicFrontBucketVolume">Объём переднего ковша, м<sup>3</sup></label>
                <input type="text" id="technicFrontBucketVolume" placeholder="1.2">
                <label for="technicBackBucketVolume">Объём заднего ковша, м<sup>3</sup></label>
                <input type="text" id="technicBackBucketVolume" placeholder="0.3">
                <label for="technicDiggingDepth">Макс. глубина копания, м<sup>3</sup></label>
                <input type="text" id="technicDiggingDepth" placeholder="4.85">
                <button type="submit">Добавить</button>
            </form>
        </div>
    </div>

    <script>

        const ws = new WebSocket('ws://localhost:8080');

        ws.onopen = function() {
            console.log('WebSocket connection established');
        };

        ws.onmessage = function(event) {
            console.log('Message from server:', event.data);
        };

        ws.onerror = function(error) {
            console.log('WebSocket error:', error);
        };

        ws.onclose = function() {
            console.log('WebSocket connection closed');
        };


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

        document.addEventListener('DOMContentLoaded', () => {
            const addTechButton = document.getElementById('addTech');
            const modal = document.getElementById('modal');
            const form = document.getElementById('addTechnicForm');
            const technicsContainer = document.getElementById('technics');

            const viewButtons = document.querySelectorAll('button.view-button');
            const modal2 = document.querySelector('.modal2');
            const modal2Content = modal2.querySelector('.content');
            const technics = <?php echo json_encode($technics); ?>;

            const ws = new WebSocket('ws://localhost:8080');
            ws.onmessage = function(event) {
                const data = JSON.parse(event.data);
                if (data.type === 'new_technic' && data.companyId == <?php echo $companyId; ?>) {
                    const technic = data.technic;
                    const technicCard = `
                        <div class="card">
                            <img src="/assets/images/traktor.png" alt="">
                            <p class="name">${technic.name}</p>
                            <p class="engine">Двигатель: <span>${technic.engineModel}</span></p>
                            <p class="power">Мощность двигателя: <span>${technic.enginePower} л.с.</span></p>
                            <p class="weight">Объём переднего ковша: <span>${technic.frontBucketVolume} м³</span></p>
                            <p class="volume">Объём заднего ковша: <span>${technic.backBucketVolume} м³</span></p>
                            <p class="digging-depth">Глубина копания: <span>${technic.diggingDepth} м³</span></p>
                            <div class="overlay">
                                <button class="view-button">Посмотреть</button>
                            </div>
                        </div>
                    `;
                    technicsContainer.insertAdjacentHTML('beforeend', technicCard);
                }
            };

            addTechButton.addEventListener('click', () => {
                modal.classList.add('active');
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const technicData = {
                    companyId: <?php echo $companyId;?>,
                    type: document.getElementById('technicType').value,
                    name: document.getElementById('technicName').value,
                    weight: document.getElementById('technicWeight').value,
                    capacity: document.getElementById('technicCapacity').value,
                    engineModel: document.getElementById('technicEngineModel').value,
                    enginePower: document.getElementById('technicEnginePower').value,
                    frontBucketVolume: document.getElementById('technicFrontBucketVolume').value,
                    backBucketVolume: document.getElementById('technicBackBucketVolume').value,
                    diggingDepth: document.getElementById('technicDiggingDepth').value
                };

                fetch('api/add-technic.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(technicData)
                }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        modal.classList.remove('active');
                    } else {
                        alert('Ошибка: ' + data.message);
                    }
                }).catch(error => {
                    console.error('Ошибка при добавлении техники:', error);
                });
            });





            function openModalWithData(technic) {
                modal2Content.innerHTML = `
                        <p class="modal-title">Информация о спецтехнике</p>
                        <form id="editTechnicForm">
                            <label for="technicName">Название</label>
                            <input type="text" id="technicName" placeholder="Большой экскаватор SWE335F" value="${technic.name}">
                            <label for="technicWeight">Масса, кг</label>
                            <input type="text" id="technicWeight" placeholder="9300" value="${technic.mass}">
                            <label for="technicCapacity">Грузоподъёмность, кг</label>
                            <input type="text" id="technicCapacity" placeholder="2500" value="${technic.loadCapacity}">
                            <label for="technicEngineModel">Модель двигателя</label>
                            <input type="text" id="technicEngineModel" placeholder="Yuchai" value="${technic.engineModel}">
                            <label for="technicEnginePower">Мощность двигателя, л.с.</label>
                            <input type="text" id="technicEnginePower" placeholder="102" value="${technic.enginePower}">
                            <label for="technicFrontBucketVolume">Объём переднего ковша, м<sup>3</sup></label>
                            <input type="text" id="technicFrontBucketVolume" placeholder="1.2" value="${technic.frontBucketVolume}">
                            <label for="technicBackBucketVolume">Объём заднего ковша, м<sup>3</sup></label>
                            <input type="text" id="technicBackBucketVolume" placeholder="0.3" value="${technic.backBucketVolume}">
                            <label for="technicDiggingDepth">Макс. глубина копания, м<sup>3</sup></label>
                            <input type="text" id="technicDiggingDepth" placeholder="4.85" value="${technic.maxDiggingDepth}">
                            <input type="hidden" id="technicId" value="${technic.technicId}">
                            <button type="submit">Применить</button>
                            <button type="button">Удалить</button>
                        </form>
                `;
                modal2.classList.add('active');
                console.log(<?php echo json_encode($technics); ?>);
            }

            // Обработчики для кнопок "Посмотреть"
            viewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const technicId = button.dataset.id;
                    const technic = technics.find(t => t.technicId == technicId);
                    if (technic) {
                        openModalWithData(technic);
                    } else {
                        console.error(`Техника с ID ${technicId} не найдена.`);
                    }
                });
            });

            // Закрытие модального окна при клике на фон
            modal2.addEventListener('click', (event) => {
                if (event.target === modal2) {
                    modal2.classList.remove('active');
                }
            });
        });

    </script>
</body>
</html>
