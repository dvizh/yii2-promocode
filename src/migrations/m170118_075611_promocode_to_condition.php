<?php

use yii\db\Schema;
use yii\db\Migration;

class m170118_075611_promocode_to_condition extends Migration
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
            '{{%promocode_to_condition}}',
            [
                'id'=> $this->primaryKey(11),
                'promocode_id'=> $this->integer(11)->notNull(),
                'condition_id'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%promocode_to_condition}}');
    }
}
