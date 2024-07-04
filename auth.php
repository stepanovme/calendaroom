<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="shortcut icon" href="/assets/favicon.png" type="image/x-icon">
    <title>Авторизация</title>
</head>
<body>
    <div class="container">
        <div class="content">
            <form onsubmit="event.preventDefault(); loginUser();" class="fade-in">
                <h1>Авторизация</h1>
                <label for="">Введите телефон или email</label>
                <input type="text" placeholder="example@gmail.com, 89539931159" id="login-email">
                <label for="">Пароль</label>
                <input type="password" placeholder="●●●●●●●●●●●●●" id="login-password">
                <button type="submit">ВОЙТИ</button>
                <a href="register.php">Ещё нет аккаунта?</a>
            </form>
        </div>
        <div class="bunner"></div>
    </div>
    <script>
        async function loginUser() {
            const email = document.querySelector('#login-email').value;
            const password = document.querySelector('#login-password').value;

            try {
                const response = await fetch('http://calendaroom.ru/api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password }),
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }

                const result = await response.json();
                if (result.status === 'success') {
                    window.location.href = 'index.php';
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('There was a problem with your login request: ' + error.message);
            }
        }
    </script>
</body>
</html>
