<h3>Товары в корзине</h3>

<h4>Всего товаров: <?= $total_quantity ?></h4>
<h4>На сумму: <?= $total_cost ?></h4>
<table>
    <tr>
        <th>Наименование</th>
        <th>Стоимость за ед.</th>
        <th>На складе</th>
        <th>Кол-во в корзине</th>
        <th>К оплате, руб.</th>
        <th></th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['title'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['quantity'] ?></td>
            <td><?= $product['quantity_at_cart'] ?></td>
            <td><?= $product['total_price_at_cart'] ?></td>
            <td>
                <form action="./?action=cart" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <input type="submit" name="delete" value="Удалить">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<form action="./index.php?action=checkout" method="POST">
    <button type="submit">Оформить заказ</button>
</form>
