<?php
$title = "Формуляр за вход";
require_once 'includes/header.php';
require_once 'main_functions.php';
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
if (isset($_SESSION['loggedIn'])) {
    header("Location: list.php");
    exit();
} else if (!isset($_SESSION['loggedIn']) && $_POST != null) {
    if (validate_data($_POST['username'], "username", 3, 30) === true && validate_data($_POST['password'], "password", 6, 30) === true) {
        $username = escape_input($_POST['username']);
        $password = escape_input($_POST['password']);
        if (check_user_exists($username, $password)) {
            if (file_exists('personal_folders/' . $username)) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $username;
                header("Location: list.php");
                exit();
            } else {
                $error = "Проблем със системата! Нямате създадена лична папка!";
            }
        } else {
            $error = "Неправилни входни данни!";
        }
    } else {
        $error = "Невалидни входни данни!";
    }
}
?>
<div class="LoginPage Module"><div class="contents">
        <div class="Login Module">
            <form method="post" class="loginForm">
                <h1>Вход в системата</h1>
                <ul class="userLogin">
                    <li class="loginUsername">
                        <input type="text" autocomplete="on" placeholder="Потребител" name="username" class="email" autofocus="">
                    </li>
                    <li class="loginPassword">
                        <input type="password" placeholder="Парола" name="password">
                    </li>
                </ul>
                <div class="loginError">
<?php
if (isset($error)) {
    echo $error;
}
?>
                </div>
                <div class="formFooter">
                    <div class="formFooterButtons">
                        <button class="rounded Button primary Module large hasText btn" type="submit">
                            <span class="buttonText">Вход</span>
                        </button>
                    </div>
                    <a href="register.php">Регистрирай се</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include 'includes/footer.php';
?>