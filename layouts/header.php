<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?></title>
    <meta charset="UTF-8">
</head>
<body>

<?php $cart = get_cart_data() ?>
<div>
    <h4><a href="./?action=cart">Корзина</a></h4>
    <?= $cart['quantity'] ?> товаров на сумму <?= $cart['cost'] ?>р.
</div>

<div>
    <?php if (is_logged_in()): ?>
        <p>Привет, <?= $_SESSION['user']['name'] ?>!</p>
        <form action="./index.php?action=logout" method="POST">
            <input type="submit" name="logout" value="Выйти">
        </form>
    <?php endif; ?>
</div>

<ul style="color: red;">
<?php foreach (get_and_delete_notifications() as $notification): ?>
        <li><?=$notification ?></li>
<?php endforeach; ?>
</ul>

<ul>
    <?php foreach (get_menu_items() as $menu_item): ?>
        <?php if ($menu_item['is_active']): ?>
            <li><b><?= $menu_item['title'] ?></b></li>
            <?php else: ?>
            <li>
                <a href="<?= $menu_item['url'] ?>"><?= $menu_item['title'] ?></a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>