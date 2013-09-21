<?php
require_once 'includes/main_functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="images/main.css">
        <title><?= $page_title ?></title>
    </head>
    <body>
        <div id="top_logo_container">
            <img src="images/logo.png" style="margin-left: 20px;">
        </div>
        <div id="menu">
            <ul style="margin-left: 25px;">
                <li><a href="index.php"><img src="images/icons/excerpt.png" style="width:18px; height: 18px; border: 0px;"> Извлечение</a></li>
                <li><a href="add_transaction.php"><img src="images/icons/add.png" style="width:18px; height: 18px; border: 0px;"> Добави транзакция</a></li>
                <li><a href="add_category.php"><img src="images/icons/category.png" style="width:18px; height: 18px; border: 0px;"> Добави категория</a></li>
            </ul>
            <form method="GET" action="index.php">
                <div style="float:left; margin:10px; width:100px;">Тип: </div>
                <div style="float:left; margin:10px;">
                    <select name="transaction_type">
                        <option value="0" selected>Разходи</option>
                        <option value="1">Приходи</option>
                    </select>
                </div>
                <div style="clear:both;"></div>
                <div style="float:left; margin:10px; width:100px;">Група: </div>
                <div style="float:left; margin:10px;">
                    <select name="category">
                        <option value="-1" selected>---</option>
                        <?php
                        print_category('data/groups_data.txt',-1);
                        ?>
                    </select>
                </div>
                <div style="clear:both;"></div>
                <div style="float:left; margin:10px; width:100px;">Дата: </div>
                <div style="float:left; margin:10px;">
                    <input type="text" name="date">
                </div>
                <div style="clear:both;"></div>
                <div style="float:left; margin:10px; width:100px;">&nbsp;</div>
                <div style="float:left; margin:10px;">
                    <input type="submit" value="Покажи">
                </div>
            </form>
        </div>