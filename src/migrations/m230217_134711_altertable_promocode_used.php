<?php
use yii\db\Schema;
use yii\db\Migration;
class m230217_134711_altertable_promocode_used extends Migration
{
    public function init()
    {
        $this->db = 'db';
        parent::init();
    }
    public function safeUp()
    {
        $this->addColumn('{{%promocode_used}}','sum',$this->integer(11)->null()->defaultValue(null));
    }
    public function safeDown()
    {
        $this->dropColumn('{{%promocode_used}}', 'sum');
    }
}
