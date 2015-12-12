<?php

$order_id = filter_input(INPUT_GET, 'order_id');
if ($order_id === null) {
    not_found_404();
    exit;
}

if (!is_logged_in()) {
    not_found_404();
    exit;
}

$order = db_select('SELECT * FROM `orders` WHERE `id` = :order_id LIMIT 1;', array(
    ':order_id' => $order_id
));
$order = reset($order);

if (!$order) {
    not_found_404();
    exit;
}

$user_owns_the_order = ($order['user_id'] == $_SESSION['user_id']);

if (!$user_owns_the_order) {
    not_found_404();
    exit;
}

$sql = <<<SQL
SELECT
  products.*, products_at_orders.quantity AS quantity_at_order
FROM products_at_orders
LEFT JOIN products ON products.id = products_at_orders.product_id
WHERE products_at_orders.order_id = :order_id;
SQL;

$products = db_select($sql, array(
    ':order_id' => $order_id
));

display_template('order', array(
    'order' => $order,
    'products' => $products,
));