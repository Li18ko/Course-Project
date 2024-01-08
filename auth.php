<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Внутридворовые спортивные площадки Санкт-Петербурга</title>
    <link rel="stylesheet" href="styless.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="icon" href="image\logo.svg" type="image/x-icon">
</head>

<body>
    <header>
        <div class="osn_sign_in">
            <div class="osn">
                <div class="logo-container">
                    <img src="image\logo.svg" alt="Логотип">
                </div>
                <h1>Внутридворовые спортивные площадки Санкт-Петербурга</h1>
                <div class="animated-line"></div>
            </div>
            <div class="sign_in-container">
                <a href="index.php">
                    <img src="image\home.svg" alt="На главную">
                </a>
            </div>
        </div>
    </header>
    <main>
        <h2>Авторизация</h2>
        <div class="auth">
            <form method="POST" autocomplete="off">
                <label>Логин </label>
                <input type="text" name="login">
                <br>
                <label>Пароль </label>
                <input type="password" name="password">
                <br>
                <div class="back_sign_in">
                    <div class="button_sign_in">
                        <button type="submit">Войти</button>
                    </div>
                </div>
            </form>
            <br>
            <div class="button_back">
                <button onclick="window.location.href='index.php'">Назад</button>
            </div>
            <h5>Еще нет аккаунта? <a href="signup.php">Зарегистрируйся!</a></h5>
        </div>
        

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = mysqli_real_escape_string($mysql, $_POST['login']);
            $password = md5($_POST['password']);

            $stmt = $mysql->prepare("SELECT * FROM users WHERE login=? AND password=?");
            $stmt->bind_param("ss", $login, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if(!$result || mysqli_num_rows($result) == 0){
                echo "Такой пользователь не существует.";
                exit;
            }
            
            session_start();
            $_SESSION["user"] = mysqli_fetch_assoc($result);
            $stmt->close();
            
            
            header("Location: user.php");
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2023-2024 Корнеева Е.С.</p>
    </footer>
</body>
</html>




