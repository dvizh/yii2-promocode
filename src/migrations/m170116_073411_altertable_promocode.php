<?php

use yii\db\Schema;
use yii\db\Migration;

class m170116_073411_altertable_promocode extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn('{{%promocode}}','date_elapsed',$this->datetime()->null()->defaultValue(null));
        $this->addColumn('{{%promocode}}','amount',$this->integer(11)->null()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%promocode}}', 'date_elapsed');
        $this->dropColumn('{{%promocode}}', 'amount');
    }
}
