<?php

use yii\db\Schema;
use yii\db\Migration;

class m170116_073511_promocode_used extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%promocode_used}}',
            [
                'id'=> $this->primaryKey(11),
                'promocode_id'=> $this->integer(11)->notNull(),
                'order_id'=> $this->integer(11)->notNull(),
                'date'=> $this->datetime()->notNull(),
                'user'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%promocode_used}}');
    }
}
