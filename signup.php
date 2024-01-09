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
        <h2>Регистрация</h2>
        <div class="signup">
            <form method="POST" autocomplete="off">
                <label>ФИО</label>
                <input type="text" name="name" required>
                <br>
                <label>Логин </label>
                <input type="text" name="login" required>
                <br>
                <label>Пароль </label>
                <input type="password" name="password" required>
                <br>
                <div class="back_sign_in">
                    <div class="button_sign_in">
                        <button type="submit">Зарегистрироваться</button>
                    </div>
                </div>
            </form>
            <br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                $result = mysqli_query($mysql, "SELECT * FROM users WHERE login=\"".$_POST['login']."\"");
                if(mysqli_num_rows($result) == 0){
                    mysqli_query($mysql, "INSERT INTO users (name, login, password) VALUES (
                        \"".$_POST["name"]."\", 
                        \"".$_POST["login"]."\",
                        \"".md5($_POST["password"])."\"
                        )"
                    );

                    header("Location: auth.php");
                }
                else{
                    echo '<div class="help_signup">';
                        echo '<p>Такой пользователь уже существует</p>';
                    echo '</div>';
                }
            
            }
            ?>
            <div class="button_back">
                <button onclick="window.location.href='auth.php'">Назад</button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023-2024 Корнеева Е.С.</p>
    </footer>
</body>
</html>




