Yii2-promocode
==========
Добавление функционала скидок (промокодов, купонов) на сайт, стабильно работает с [dvizh/cart](http://github.com/dvizh/yii2-cart).

Модуль умеет через Behavior динамически менять цену заказа, исходя из вида примененного купона: накопительный, "процент скидки", "сумма скидки".

Установка
---------------------------------
Выполнить команду

```
php composer require dvizh/yii2-promocode "@dev"
```

Или добавить в composer.json

```
"dvizh/yii2-promocode": "@dev",
```

И выполнить

```
php composer update
```

Миграция:

php yii migrate --migrationPath=vendor/dvizh/yii2-promocode/src/migrations

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль promocode 

В targetModelList указать модели для привязки промокода

```php
    'modules' => [
        //..
        'promocode' => [
            'class' => 'dvizh\promocode\Module',
            'informer' => 'dvizh\cart\widgets\CartInformer', // namespace to custom cartInformer widget
            'informerSettings' => [], //settings for custom cartInformer widget
            'clientsModel' => 'dektrium\user\models\User', //Модель пользователей
            //Указываем модели, к которым будем привязывать промокод
            'targetModelList' => [
                'Категории' => [
                    'model' => 'dvizh\service\models\Category',
                    'searchModel' => 'dvizh\service\models\category\CategorySearch'
                ],
                'Продукты' => [
                    'model' => 'dvizh\shop\models\Product',
                    'searchModel' => 'dvizh\shop\models\product\ProductSearch'
                ],            
            ],
        ],
        //..
    ]
```

Использование
---------------------------------

Чтобы управлять промокодами, нужно перейти к контроллеру модуля: ?r=promocode/promo-code

Добавление промокода для текущего пользователя:
```php
yii::$app->promocode->enter($promocode);
```

Очистить текущий промокод пользователя:
```php
yii::$app->promocode->clear()
```

Проверить, введен ли промокод:
```php
if(yii::$app->promocode->has())
```

Получить текущий промокод:
```php
yii::$app->promocode->getCode()
```

Получить процент скидки текущий:
```php
$persent = yii::$app->promocode->get()->promocode->discount;
```

Чтобы скидка применялась для [dvizh/cart](http://github.com/dvizh/yii2-cart), необходимо добавить поведение dvizh\promocode\behaviors\Discount для cart при подключении cart в конфиге:

```php
    'cart' => [
        'class' => 'dvizh\cart\Cart',
        'as PromoDiscount' => ['class' => 'dvizh\promocode\behaviors\Discount'],
    ]
```

Чтобы скидка применялась для отдельных моделей необходимо добавить поведение dvizh\promocode\behaviors\DiscountToElement для cart при подключении компонента в конфиге:

```php
    'cart' => [
        'class' => 'dvizh\cart\Cart',
        //'as PromoDiscount' => ['class' => 'dvizh\promocode\behaviors\Discount'],
        'as ElementDiscount' => ['class' => 'dvizh\promocode\behaviors\DiscountToElement'],
    ]
```

Виджеты
---------------------------------
Вывод формы ввода промокода для пользователя:
<?=\dvizh\promocode\widgets\Enter::widget();?>

Целую, пока!
