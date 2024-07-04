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
    <title>Регистрация</title>
</head>
<body>
    <div class="container">
        <div class="content">
            <form onsubmit="event.preventDefault(); registerUser();" class="fade-in">
                <h1>Регистрация</h1>
                <label for="">Имя</label>
                <input type="text" placeholder="Степан" id="register-name">
                <label for="">Фамилия</label>
                <input type="text" placeholder="Степанов" id="register-surname">
                <label for="">Отчество</label>
                <input type="text" placeholder="Александрович" id="register-patronymic">
                <label for="">Номер телефона</label>
                <input type="text" placeholder="89539931159" id="register-phone">
                <label for="">Email</label>
                <input type="text" placeholder="example@gmail.com" id="register-email">
                <label for="">Пароль</label>
                <input type="password" placeholder="●●●●●●●●●●●●●" id="register-password">
                <button type="submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>
                <a href="auth.php">Уже есть аккаунт?</a>
            </form>
        </div>
        <div class="bunner"></div>
    </div>
    <script>
        async function registerUser() {
            const email = document.querySelector('#register-email').value;
            const phone = document.querySelector('#register-phone').value;
            const password = document.querySelector('#register-password').value;
            const name = document.querySelector('#register-name').value;
            const surname = document.querySelector('#register-surname').value;
            const patronymic = document.querySelector('#register-patronymic').value;
            
            try {
                const response = await fetch('http://calendaroom.ru/api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, phone, password, name, surname, patronymic }),
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }

                const result = await response.json();
                alert(result.message);
            } catch (error) {
                alert('There was a problem with your registration request: ' + error.message);
            }
        }
    </script>
</body>
</html>
