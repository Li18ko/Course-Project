<?php
include 'db.php';
require("session.php");

if(!isset($_SESSION["user"])){
	echo "Укажите идентификатор пользователя.";
	exit;
}
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
                <a href="logout.php">
                    <img src="image\exit.svg" alt="Выход">
                </a>
                <br>
                <br>
                <a href="index.php">
                    <img src="image\home.svg" alt="на главную">
                </a>
            </div>
        </div>
    </header>
    <main>
        <?php
        $result = mysqli_query($mysql , "SELECT * FROM users WHERE id=" . $session_user["id"]);
        
        $user = mysqli_fetch_assoc($result);
        /*$pages = array();
        $result = mysqli_query($connect , "SELECT * FROM pages WHERE user_id = ".$user["id"]);*/
        
        
        /*if ($result) {
            while($page = mysqli_fetch_assoc($result)) {
                $pages[] = $page;
            }
        }*/

        echo '<h2>Личный кабинет</h2>';
        echo '<h3>' . $user["name"]. ' ' . '[' . $user['login'] . ']</h3>';
        /*$content .= "<h2>Страницы пользователя</h2>";
        $content .= "<p>Общее количество страниц: ". mysqli_num_rows($result) ."</p>";*/
        
        /*if(count($pages)) {
            $content .= "<ul>";
            foreach($pages as $page){
                $content .= "<li><a href =\"page.php?id=".$page["id"]."\">".$page["title"]."</a></li>";
            }
            $content .= "</ul>";
        } 
        else{
            $content .= "<p>У данного пользователя еще нет страниц.</p>";
        }
        */
        ?>
    </main>

    <footer>
        <p>&copy; 2023-2024 Корнеева Е.С.</p>
    </footer>
</body>
</html>




