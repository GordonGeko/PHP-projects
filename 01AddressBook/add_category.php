<?php
mb_internal_encoding($encoding = 'UTF-8');
$page_title = 'Addressbook - Добавяне на категория';
include 'header.php';
if (isset($_POST['category_name'])) {
    add_new_category(escape_input($_POST['category_name']));
}
?>
<div id="main_container">
    <h1>Добавяне на нова категория</h1>
    <form method="POST">
        <div class="left_content">Име на категорията: </div>
        <div class="right_content"><input type="text" name="category_name" value=""/></div>
        <br/>
        <div class="left_content">&nbsp;</div>
        <div class="right_content">
            <input type="submit" value="Добави">
        </div>
    </form>
</div>
<?php
include 'footer.php';
?>
