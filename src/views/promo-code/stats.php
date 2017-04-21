<?php
$this->title = 'Статистика по промокодам';
$this->params['breadcrumbs'][] = ['label' => 'Промокоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Статистика по промокодам';
?>
<div class="promo-codes-stats">
    <table class="table table-bordered table-hover table-responsive">
        <tbody>
        <tr>
            <th>Название</th>
            <th>С 1 числа</th>
            <th>За 30 дней</th>
            <th>За все время</th>
            <th>Средняя стоимость</th>
            <th>Доля промокода в заказах</th>
        </tr>
        <?php if (isset($promocodes)){ ?>
        <?php foreach ($promocodes as $key => $promocode){ ?>
            <tr>
                <td><?=$promocode['name']?></td>
                <td><?=$promocode['thisMonth']?></td>
                <td><?=$promocode['lastMonth']?></td>
                <td><?=$promocode['allTime']?></td>
                <td><?=$promocode['avgSum']?></td>
                <td><?=$promocode['percent']?> %</td>
            </tr>
        <?php   } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
