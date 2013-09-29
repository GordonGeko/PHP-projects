<?php
$title = "Форма за регистрация";
require_once 'header.php';
require_once 'main_functions.php';
if (isset($_SESSION['loggedIn'])) {
    header("Location: list.php");
    exit();
} else if (!isset($_SESSION['loggedIn']) && $_POST != null) {
    $validate_username = validate_data($_POST['username'], "username", 3, 30);
    $validate_password = validate_data($_POST['password'], "password", 3, 30);
    if ($validate_username === true && $validate_password === true) {
        $username = escape_input($_POST['username']);
        $password = escape_input($_POST['password']);
        if (!check_user_exists($username)) {
            if (!file_exists('personal_folders/' . $username)) {
                if (!mkdir('personal_folders/' . $username, 0644)) {
                    $error = "Неуспешно създаване на лична папка!";
                } else {
                    $htaccess = '<Files ~ ".*$">' . PHP_EOL . 'Order Deny,Allow' . PHP_EOL . 'Deny from all' . PHP_EOL . '</Files>';
                    if (file_put_contents('personal_folders/' . $username . '/.htaccess', $htaccess) != FALSE) {
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['username'] = $username;
                        add_user($username, $password);
                        header("Location: list.php");
                        exit();
                    } else {
                        $error = "Неуспешно създаване на нужните файлове за регистрация!";
                    }
                }
            } else {
                $error = "Потребителят вече съществува!";
            }
        } else {
            $error = "Този потребител вече съществува!";
        }
    } else {
        $error = $validate_username . "<br/>" . $validate_password;
    }
}
?>
<div class="LoginPage Module"><div class="contents">
        <div class="Login Module">
            <form method="post" class="loginForm">
                <h1>Регистрация</h1>
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
                            <span class="buttonText">Регистрирай ме</span>
                        </button>
                    </div>
                    <a href="index.php">Вход в системата</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include 'footer.php';
?>