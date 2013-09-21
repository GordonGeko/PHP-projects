<?php
if (!isset($_GET['transaction_type']) || (int) $_GET['transaction_type'] == 0) {
    $page_title = 'Addressbook - Списък с разходи';
    $output_heading[] = 'Списък с разходи';
} else {
    $page_title = 'Addressbook - Списък с приходи';
    $output_heading[] = 'Списък с приходи';
}
include 'header.php';
$type = 0; //Разходи
$category = -1;
$date = -1;
if (isset($_GET['transaction_type'])) {
    $buffer_type = trim($_GET['transaction_type']);
    if (is_numeric($buffer_type) && ($buffer_type == 0 || $buffer_type == 1)) {
        $type = (int) $buffer_type;
    }
} else {
    $buffer_type = 0;
}
if (isset($_GET['date'])) {
    $valid_date = validate_date($_GET['date']);
    if ($valid_date != FALSE) {
        $date = $valid_date;
        $output_heading[] = ' на '.implode('.',$date);
    }
}
if (isset($_GET['category'])) {
    $buffer_category = trim($_GET['category']);
    if (is_numeric($buffer_category) && $buffer_category != -1) {
        $category = (int) $buffer_category;
        $output_heading[] = ' за '.find_category($buffer_category);
    }
}
if (isset($_POST['delete_list']) && isset($_POST['trans_type'])) {
    $type = trim($_POST['trans_type']);
    if (is_numeric($buffer_type) && ($buffer_type == 0 || $buffer_type == 1)) {
        $rows = array_filter($_POST['delete_list'], 'ctype_digit');
        delete_entry($type, $rows);
    }
}
?>
<div id="main_container">
    <h1>
        <?php
        foreach ($output_heading as $text) {
            echo $text;
        }
        ?></h1>
    <form method="post">
        <table width="100%" border="1px" style="border-collapse:collapse;">
            <tr>
                <td>
                    &nbsp;
                    <input type="hidden" name="trans_type" value="<?= $type ?>">
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    Тип
                </td>
                <td>
                    Сума
                </td>
                <td>
                    Група
                </td>
                <td>
                    Дата
                </td>
                <td>
                    Основание
                </td>
            </tr>
            <?php
            generate_transaction_table($type, $category, $date);
            ?>
        </table>
        <input type="submit" value="Изтрий селектираните">
    </form>
</div>
<?php
include 'footer.php';
?>