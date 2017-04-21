<?php

use yii\db\Schema;
use yii\db\Migration;

class m170118_075411_promocode_condition extends Migration
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
            '{{%promocode_condition}}',
            [
                'id'=> $this->primaryKey(11),
                'sum_start'=> $this->integer(10)->notNull(),
                'sum_stop'=> $this->integer(10)->notNull(),
                'value'=> $this->integer(5)->notNull(),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%promocode_condition}}');
    }
}
