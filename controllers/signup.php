<?php
/**
 * Контроллер регистрации.
 * Отвечает за обработку данных, пришедших из формы регистрации.
 */

// Массив с ошибками заполнения формы
$errors = array();

// Нам переданы из формы email & password
if (isset($_POST['email'], $_POST['password'])) {
    // Отсечём пустые символы (пробелы и переносы строк)
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Проверим e-mail на корректность
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errors[] = 'Введён некорректный e-mail.';
    }

    // Проверим длину пароля
    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль не может быть менее 6 символов.';
    }

    // Найдём пользователя с таким же email в БД
    $user_with_same_email = db_select(
        'SELECT * FROM `users` WHERE `email` = :email LIMIT 1;', array(
        ':email' => $email
    ));

    // Если нашли - добавляем ошибку
    if (!empty($user_with_same_email)) {
        $errors[] = 'Этот e-mail уже занят.';
    }

    // Если ошибок нет - добавим новую запись в таблицу users
    if (empty($errors)) {
        // Мы храним только хэш пароля
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO `users` (`email`, `password_hash`) VALUES (:email, :password_hash)';

        // Выполним SQL-запрос вставки записи, используя именованные параметры
        $affected_rows = db_query($sql, array(
            ':email' => $email,
            ':password_hash' => $password_hash
        ));

        // Вызов db_query должен вернуть 1, потому что мы вставляем 1 запись
        if ($affected_rows < 1) {
            $errors[] = 'Не удалось завершить регистрацию.';
        }
    }

    // Ошибок не произошло - отправляем пользователя на главную страницу
    if (empty($errors)) {
        browser_redirect('homepage');
    }
}

// Отображаем шаблон в случае если нам не пришли никакие данные из формы,
// либо при обработке данных формы возникли ошибки.
display_template('signup', array(
    'errors' => $errors
));