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
        </div>
    </header>
    <main>
        <h2>Личный кабинет</h2>
        <div class="button_back">
            <button onclick="window.location.href='auth.php'">Выход</button>
        </div>
        

        <?php
        $result = mysqli_query($mysql , "SELECT * FROM users WHERE id=" . $session_user["id"]);

        if (!$result || !mysqli_num_rows($result)) {
            echo "<p>Такого пользователя не существует.</p>";
            exit;
        }
        
        $user = mysqli_fetch_assoc($result);
        /*$pages = array();
        $result = mysqli_query($connect , "SELECT * FROM pages WHERE user_id = ".$user["id"]);*/
        
        
        /*if ($result) {
            while($page = mysqli_fetch_assoc($result)) {
                $pages[] = $page;
            }
        }*/
        
        $title = "Страница пользователя";
        $content = "<p>".$user["name"]." [".$user["login"]."]</p>";
        echo "<p>" . $user["name"]. " " . "[" . $user['login'] . "]</p>";
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




