<?php
/**
 * Контроллер авторизации (входа на сайт).
 * Отвечает за обработку данных, пришедших из формы авторизации.
 */

// Массив с ошибками заполнения формы
$errors = [];

// Нам переданы из формы email & password
if (isset($_POST['email'], $_POST['password'])) {
    // Отсечём пустые символы (пробелы и переносы строк)
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Найдём пользователя в БД с переданным нам email'ом
    $user = db_select('SELECT * FROM `users` WHERE `email` = :email LIMIT 1;', [
        ':email' => $email
    ]);
    $user = reset($user);

    // Не нашли такого пользователя в БД
    if (empty($user)) {
        $errors[] = 'Пользователь не найден.';
    } else {
        // Нашли, проверим пришедший из формы пароль.
        // Сравним хэш из БД с хэшем пароля, введённого в форму.
        $password_is_valid = password_verify($password, $user['password_hash']);
        if ($password_is_valid) {
            // Хэши совпали, запишем ID пользователя в сессию.
            $_SESSION['user_id'] = (int) $user['id'];

            // ...и отправим на главную страницу
            browser_redirect('homepage');
        } else {
            $errors[] = 'Введён неверный пароль.';
        }
    }
}

display_template('login', [
    'errors' => $errors
]);