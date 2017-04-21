<?php

use yii\db\Migration;

class m170117_131738_altertable_promocode_type extends Migration
{
    public function up()
    {
        $this->addColumn('{{%promocode}}','type','ENUM("percent", "quantum") NOT NULL DEFAULT "percent"');
    }

    public function down()
    {
        $this->dropColumn('{{%promocode}}','type');
    }

}
