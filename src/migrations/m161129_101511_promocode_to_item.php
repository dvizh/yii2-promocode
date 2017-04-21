<?php

use yii\db\Schema;
use yii\db\Migration;

class m161129_101511_promocode_to_item extends Migration
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
            '{{%promocode_to_item}}',
            [
                'id'=> $this->primaryKey(11),
                'promocode_id'=> $this->integer(11)->notNull(),
                'item_model'=> $this->string(255)->notNull(),
                'item_id'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%promocode_to_item}}');
    }
}
