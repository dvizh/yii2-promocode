<?php

use yii\db\Schema;
use yii\db\Migration;

class m160518_123713_Mass extends Migration {

    public function safeUp() {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        else {
            $tableOptions = null;
        }
        $connection = Yii::$app->db;

        try {
            $this->createTable('{{%promocode}}', [
                'id' => Schema::TYPE_PK . "",
                'title' => Schema::TYPE_STRING . "(256) NOT NULL",
                'description' => Schema::TYPE_TEXT . " NOT NULL",
                'code' => Schema::TYPE_STRING . "(14) NOT NULL",
                'discount' => Schema::TYPE_INTEGER . "(2) NOT NULL",
                'status' => Schema::TYPE_INTEGER . "(1) NOT NULL",
                ], $tableOptions);

            $this->createIndex('code', '{{%promocode}}', 'code', 1);
            $this->createTable('{{%promocode_use}}', [
                'id' => Schema::TYPE_PK . "",
                'promocode_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'user_id' => Schema::TYPE_STRING . "(55) NOT NULL",
                'date' => Schema::TYPE_DATETIME . " NOT NULL",
                ], $tableOptions);

            $this->addForeignKey(
                'fk_promocode', '{{%promocode_use}}', 'promocode_id', '{{%promocode}}', 'id', 'CASCADE', 'CASCADE'
            );

        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

    public function safeDown() {
        $connection = Yii::$app->db;
        try {
            $this->dropTable('{{%promocode}}');
            $this->dropTable('{{%promocode_use}}');
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

}
