<?php

function print_category($filename, $selectF = 1) {
    if (!file_exists($filename)) {
        file_put_contents($filename, "3\n0@храна\n1@дрехи\n2@мебели\n3@техника\n");
    }
    $lines = file($filename);
    for ($index = 1; $index < count($lines); $index++) {
        $line_part = explode("@", $lines[$index]);
        if ($selectF == $index) {
            echo '<option value="' . $line_part[0] . '" selected>' . trim($line_part[1]) . '</option>';
        } else {
            echo '<option value="' . $line_part[0] . '">' . trim($line_part[1]) . '</option>';
        }
    }
}

function escape_input($input) {
    $escaped = strip_tags(str_replace('@', '', trim($input)));
    return $escaped;
}

function add_transaction($amount, $type, $category, $description) {
    if ($type == 0) {
        $file_path = 'data/expenses_transactions.txt';
    } else {
        $file_path = 'data/incomes_transactions.txt';
    }
    $data = date("j@n@Y") . '@' . $amount . '@' . $type . '@' . $category . '@' . $description . "\n";
    file_put_contents($file_path, $data, FILE_APPEND);
}

function validate_date($date) {
    $buffer_date = explode('-', str_replace(array('.', '-', '/', ',', ' '), '-', escape_input($date)));
    if (count($buffer_date) == 3 && checkdate($buffer_date[1], $buffer_date[0], $buffer_date[2])) {
        return $buffer_date;
    } else {
        return FALSE;
    }
}

function find_category($cat_index) {
    if (file_exists('data/groups_data.txt')) {
        $file_content = file('data/groups_data.txt');
        foreach ($file_content as $value) {
            $get_parts = explode('@', $value);
            if (count($get_parts) == 2) {
                if ($get_parts[0] == $cat_index) {
                    return $get_parts[1];
                }
            }
        }
    }
    return '---';
}

function generate_transaction_table($type, $category, $date) {
    if ($type == 0) {
        $file_path = 'data/expenses_transactions.txt';
    } else {
        $file_path = 'data/incomes_transactions.txt';
    }
    if (file_exists($file_path)) {
        $sum = 0;
        $output_buffer = '';
        $file_content = file($file_path);
        $row_counter = 0;
        foreach ($file_content as $line) {
            $information = explode('@', $line);
            if ($category != -1 && $information[5] != trim($category)) {
                $information = NULL;
            }
            if ($information != NULL && is_array($date) && ($information[0] != $date[0] || $information[1] != $date[1] || $information[2] != $date[2])) {
                $information = NULL;
            }
            if ($information != null) {
                $sum+=$information[3];
                $output_buffer.='<tr>
            <td>
            <input type="checkbox" name="delete_list[]" value="' . $row_counter . '" />
            </td>
            <td>
            <a href="add_transaction.php?edit=' . $row_counter . '&type=' . $type . '"><img src="images/icons/edit.png" style="width:18px; height: 18px; border: 0px;"></a>
            </td>
            <td>
                ';
                if ($type == 0) {
                    $output_buffer.='разход';
                } else {
                    $output_buffer.='приход';
                }
                $output_buffer.='
            </td>
            <td>
                ' . $information[3] . '
            </td>
            <td>
                ' . find_category($information[5]) . '
            </td>
            <td>
                ' . $information[0] . '.' . $information[1] . '.' . $information[2] . '
            </td>
            <td>
                ' . $information[6] . '
            </td>
        </tr>';
            }
            $row_counter++;
        }
        $output_buffer.='<tr><td></td><td></td><td>Общо:</td><td>' . $sum . '</td><td></td><td></td><td></td></tr>';
        echo $output_buffer;
    }
}

function delete_entry($type, $rows) {
    if ($type == 0) {
        $file_path = 'data/expenses_transactions.txt';
    } else {
        $file_path = 'data/incomes_transactions.txt';
    }
    if (!is_array($rows)) {
        $rows = array($rows);
    }
    if (file_exists($file_path)) {
        $file_content = file($file_path);
        foreach ($rows as $row) {
            unset($file_content[$row]);
        }
        file_put_contents($file_path, $file_content);
    }
}

function edit_entry($type, $row, $new_entry) {
    if ($type == 0) {
        $file_path = 'data/expenses_transactions.txt';
    } else {
        $file_path = 'data/incomes_transactions.txt';
    }
    echo $file_path;
    if (file_exists($file_path)) {
        $file_content = file($file_path);
        if ($row >= 0 && $row < count(($file_content))) {
            $file_content[$row] = $new_entry;
            file_put_contents($file_path, $file_content);
        }
    }
}

function select_entry($type, $row) {
    if ($type == 0) {
        $file_path = 'data/expenses_transactions.txt';
    } else {
        $file_path = 'data/incomes_transactions.txt';
    }
    if (file_exists($file_path)) {
        $file_content = file($file_path);
        if ($row >= 0 && $row < count(($file_content))) {
            return explode('@', $file_content[$row]);
        }
    }
}

function add_new_category($category_name) {
    $file_path = 'data/groups_data.txt';
    if (file_exists($file_path)) {
        $file_content = file($file_path);
        $file_content[0]++;
        $file_content[$file_content[0]] = trim($file_content[0]).'@'.$category_name."\n";
        file_put_contents($file_path, $file_content);
    }
}

?>