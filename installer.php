<?php
/**
 * Скрипт-установщик интернет-магазина
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$install = array();

$install['Create DB'] = 'CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` DEFAULT CHARACTER SET = `utf8`;';
$install['Use DB'] = 'USE `' . DB_NAME . '`;';

// HEREDOC-syntax

// Create users table
$install['Delete users table'] = 'DROP TABLE IF EXISTS `users`;';
$install['Create users table'] = <<<SQL
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) NOT NULL DEFAULT "",
  `password_hash` varchar(70) NOT NULL DEFAULT "",
  `last_logged_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

// Create products table
$install['Delete products table'] = 'DROP TABLE IF EXISTS `products`;';
$install['Create products table'] = <<<SQL
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;


// Create carts table
$install['Delete carts table'] = 'DROP TABLE IF EXISTS `carts`;';
$install['Create carts table'] = <<<SQL
CREATE TABLE `carts` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

// Create orders table
$install['Delete orders table'] = 'DROP TABLE IF EXISTS `orders`;';
$install['Create orders table'] = <<<SQL
CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

// Связь между товарами и заказами
$install['Delete products_at_orders table'] = 'DROP TABLE IF EXISTS `products_at_orders`;';
$install['Create products_at_orders table'] = <<<SQL
CREATE TABLE `products_at_orders` (
`product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

foreach ($install as $query_description => $query_body)
{
    echo $query_description . '<br>';
    db_query($query_body);
}