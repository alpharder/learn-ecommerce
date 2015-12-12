<?php
/**
 * Контроллер выхода с сайта.
 */

// Пользователь нажал кнопку "выйти"
if (isset($_POST['logout'])) {
    // Очищаем данные о пользователе в сессии
    $_SESSION['user_id'] = 0;
    $_SESSION['user'] = [];

    browser_redirect('homepage');
}