<?php
if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
    exit;
}

if (!is_logged_in()) {
    add_notification('Оформление заказа доступно только авторизованным пользователям.');
    browser_redirect('signup');
    exit;
}

if (empty($_SESSION['cart'])) {
    add_notification('Ваша корзина пуста.');
    browser_redirect('homepage');
    exit;
}


$products = get_product_data_from_cart();

$overhead_quantity_products = array();


foreach ($products as &$product) {
    $product['quantity_at_cart'] = $_SESSION['cart'][$product['id']];

    if ($product['quantity_at_cart'] > $product['quantity']) {
        $overhead_quantity_products[] = $product['title'];
    }
}
unset($product);

if (!empty($overhead_quantity_products)) {
    $overhead_quantity_products = array_map(function ($product_title) {
        return '"' . $product_title . '"';
    }, $overhead_quantity_products);

    add_notification('Для товаров ' . implode(', ', $overhead_quantity_products) . ' недостаточен остаток.');
    browser_redirect('cart');
    exit;
}


$order = array(
    ':user_id' => $_SESSION['user_id']
);

db_query('INSERT INTO `orders` (`user_id`) VALUES (:user_id);', $order);

$order_id = db_select('SELECT LAST_INSERT_ID();');
$order_id = reset($order_id);
$order_id = reset($order_id);
$order_id = (int)$order_id;

$sql_add_product_to_order = <<<SQL
INSERT INTO `products_at_orders`
  (`product_id`, `order_id`, `quantity`)
  VALUES
  (:product_id, :order_id, :quantity)
SQL;

$sql_decrease_product_quantity = <<<SQL
UPDATE `products`
SET `quantity` = :new_quantity
WHERE `id` = :product_id
SQL;

foreach ($products as $product) {
    db_query($sql_add_product_to_order, array(
        ':product_id' => $product['id'],
        ':order_id' => $order_id,
        ':quantity' => $product['quantity_at_cart'],
    ));

    db_query($sql_decrease_product_quantity, array(
        ':product_id' => $product['id'],
        ':new_quantity' => $product['quantity'] - $product['quantity_at_cart'],
    ));
}
$_SESSION['cart'] = array();
add_notification('Заказ успешно создан!');
browser_redirect('order', array(
    'order_id' => $order_id
));
exit;







