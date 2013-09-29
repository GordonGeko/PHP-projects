<?php
$title = "Формуляр за вход";
require_once 'header.php';
require_once 'main_functions.php';
if (!isset($_SESSION['loggedIn'])) {
    header("Location: index.php");
    exit();
}
$uploaded_files = 0;
$files_count = 0;
if (isset($_POST['submit_checker'])) {
    $max_allowed_file_size = 3145728;
    $files = $_FILES['file'];
    for ($index1 = 0; $index1 < count($files['name']); $index1++) {
        $check_result = validate_file($files, $max_allowed_file_size, $index1);
        if($check_result===TRUE)
        {
            $file_prefix = return_file_index($_SESSION['username']);
            if($file_prefix!==FALSE)
            {
                move_file(short_name($files['name'][$index1],str_pad($file_prefix, 3, "0", STR_PAD_LEFT)), $_SESSION['username'], $files['tmp_name'][$index1]);
                $uploaded_files++;
            }
        }
        $files_count++;
    }
}
?>
<h1><a href="index.php?action=logout">Изход от системата</a></h1>
<br/><hr/><br/>
Допустими разширения на файловете: "txt", "doc", "docx", "ppt", "pptx", "pdf", "jpg", "jpeg", "gif", "png"<br/>
Максимален размер на файла: 3мб<br/>
Задържайки ctrl и селектиране на файловете от windows explorer прозореца, може да изберете повече от 1 файл за качване в личната папка
<br/><hr/><br/>
<form enctype="multipart/form-data" method="POST">
    <input type="file" name="file[]" multiple="multiple">
    <input type="submit" name="submit_checker" value="Качи файл">
</form>
<br/><hr/><br/>
<table border="1">
    <tr>
        <td>Име на Файл</td>
        <td>Тип</td>
        <td>Размер</td>
        <td>Дата на качване</td>
    </tr>
    <?php
    if (file_exists('personal_folders/' . $_SESSION['username'])) {
        $file_list = scandir('personal_folders/' . $_SESSION['username']);
        for ($index = 0; $index < count($file_list); $index++) {
            if ($file_list[$index]{0} != '.') {
                $file_url = 'personal_folders/' . $_SESSION['username'] . '/' . $file_list[$index];
                echo '<tr><td><a href="download_file.php?file='.  rawurlencode($file_list[$index]).'">' . $file_list[$index] . '</a></td><td>' . filetype($file_url) . '</td><td>' . filesize($file_url) . ' байта</td><td>' . date("d-m-y", filemtime($file_url)) . '</td></tr>';
            }
        }
    } else {
        session_unset();
        session_destroy();
    }
    ?>
</table>
<?php
include 'footer.php';
?>