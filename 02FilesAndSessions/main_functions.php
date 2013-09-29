<?php

function validate_data($data, $type, $min_length, $max_length) {
    if ($type == 'username') {
        $fill = 'Потребителското име ';
    } else if ($type == 'password') {
        $fill = 'Паролата ';
    }
    $regex_result = preg_match('/^[0-9a-zA-Z-_]+$/', $data);
    if ($regex_result == 1) {
        if (strlen($data) < $min_length || strlen($data) > $max_length) {
            return $fill . 'трябва да бъде между ' . $min_length . ' и ' . $max_length . ' символа!';
        } else {
            return TRUE;
        }
    } else {
        return $fill . 'съдържа невалидни символи - позволени са само цифри и букви(малки и големи)!';
    }
}

function add_user($username, $password) {
    file_put_contents('data/users.txt', $username . '<' . $password . PHP_EOL, FILE_APPEND);
}

function check_user_exists($username, $password = '') {
    $file_content = file('data/users.txt');
    $searched_entry = $username . '<' . $password;
    foreach ($file_content as $user) {
        if ($username == '') {
            $username_part = explode('<', $user);
            if (trim($user) == $username_part) {
                return TRUE;
            }
        } else {
            if (trim($user) == $searched_entry) {
                return TRUE;
            }
        }
    }
    return FALSE;
}

function escape_input($input) {
    $escaped = strip_tags(str_replace('<', '', trim($input)));
    return $escaped;
}

function validate_file($file, $max_size, $file_index) {
    $allowedExtensions = array("txt", "doc", "docx", "ppt", "pptx", "pdf", "jpg", "jpeg", "gif", "png");
    $allowedMimeTypes = array("text/plain", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/pdf", "image/jpeg", "image/jpeg", "image/gif", "image/png");
    if ($file["size"][$file_index] < $max_size && $file["size"][$file_index] > 0) {
        $extension = explode(".", strtolower($file['name'][$file_index]));
        $extension = end($extension);
        if ($file['name'][$file_index] != '' && in_array($extension, $allowedExtensions)) {
            $extension_index = array_search($extension, $allowedExtensions);
            if ($allowedMimeTypes[$extension_index] == $file['type'][$file_index]) {
                return TRUE;
            }
        }
    }
    return FALSE;
}

function return_file_index($username) {
    if (file_exists('personal_folders/' . $username)) {
        $file_list = scandir('personal_folders/' . $username);
        if(count($file_list)>3) {
            $last_file_index = count($file_list) - 1;
            $last_file_number = substr($file_list[$last_file_index], 0, 3);
            if((int)$last_file_number==999)
            {
                return FALSE;
            }
            return (int)++$last_file_number;
        }
        else
        {
            return 1;
        }
    }
    return FALSE;
}

function move_file($file_name, $username, $tmp_path) {
    $path = dirname(__FILE__);
    $new_path_name = $path . '/personal_folders/' . $username .'/'. $file_name;
    if (move_uploaded_file($tmp_path, $new_path_name)) {
        return TRUE;
    }
    return FALSE;
}

function short_name($file_name, $number) {
    if (mb_strlen($file_name > 10)) {
        $extension = explode(".", strtolower($file['name'][$file_index]));
        $extension = end($extension);
        $file_name = substr($file_name, 0, 10) . $extension;
    }
    return $number . $file_name;
}

?>