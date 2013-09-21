<?php
mb_internal_encoding($encoding = 'UTF-8');
$page_title = 'Addressbook - Добавяне на транзакция';
include 'header.php';
$errors = array();
if (!empty($_POST)) {
    $description = escape_input($_POST['description']);
    $amount = escape_input(str_replace(',', '.', $_POST['amount']));
    $trans_type = escape_input($_POST['transaction_type']);
    $category = escape_input($_POST['category']);
    if (mb_strlen($description) <= 3) {
        $errors[] = 'Неправилно описание!';
    }
    if (!(is_numeric($amount) || is_float($amount)) || $amount <= 0) {
        $errors[] = 'Невалиден размер на транзакцията!';
    }
    if (!is_numeric($trans_type) || ($trans_type != 0 && $trans_type != 1)) {
        $errors[] = 'Не пипай html кода :)';
    }
    if (!is_numeric($trans_type) || !is_numeric($category)) {
        $errors[] = 'Не пипай html кода :)';
    }
    if (count($errors) == 0) {
        if (isset($_GET['edit']) && isset($_GET['type']) && is_numeric($_GET['type']) && is_numeric($_GET['edit'])) {
            $get_type = (int) escape_input($_GET['type']);
            if($get_type==$_GET['edit'])
            {
            $new_entry_data = date("j@n@Y") . '@' . $amount . '@' . $trans_type . '@' . $category . '@' . $description . "\n";
            edit_entry((int) $trans_type, (int) escape_input($_GET['edit']), $new_entry_data);
            }
            else
            {
                add_transaction((float) $amount, (int) $trans_type, (int) $category, $description);
                delete_entry($get_type, (int) escape_input($_GET['edit']));
            }
        } else if (!isset($_GET['edit'])) {
            add_transaction((float) $amount, (int) $trans_type, (int) $category, $description);
        }
    }
}
$edit_values[0]=$edit_values[1]=$edit_values[2]=$edit_values[3]=$edit_values[4]=$edit_values[5]=$edit_values[6]='';
if (isset($_GET['edit'])&& is_numeric($_GET['edit'])) {
    $type = (int)escape_input($_GET['type']);
    $edit_values = select_entry($type, (int)trim($_GET['edit']));
}
?>
<div id="main_container">
    <?php
    foreach ($errors as $error) {
        echo '<div id="error">' . $error . '</div>';
    }
    ?>
    <h1>Добавяне на нова транзакция</h1>
    <form method="POST">
        <div class="left_content">Описание: </div>
        <div class="right_content"><input type="text" name="description" value="<?=$edit_values[6]?>"/></div>
        <br/>
        <div class="left_content">Сума: </div>
        <div class="right_content"><input type="text" name="amount" value="<?=$edit_values[3]?>"/></div>
        <br/>
        <div class="left_content">Тип: </div>
        <div class="right_content">
            <select name="transaction_type">
                <option value="0" <?php if($edit_values[4]==0 || $edit_values[4]=='') echo 'selected';?>>Разход</option>
                <option value="1" <?php if($edit_values[4]==1) echo 'selected';?>>Приход</option>
            </select>
        </div>
        <br/>
        <div class="left_content">Група: </div>
        <div class="right_content">
            <select name="category">
                <?php
                print_category('data/groups_data.txt',$edit_values[5]+1);
                ?>
            </select>
        </div>
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