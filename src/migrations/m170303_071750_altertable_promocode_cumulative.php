<?php

use yii\db\Migration;

class m170303_071750_altertable_promocode_cumulative extends Migration
{
    public function up()
    {
    	$this->alterColumn('{{%promocode}}','type','ENUM("percent", "quantum","cumulative") NOT NULL DEFAULT "percent"');
    }

    public function down()
    {
    	//migration can't be reversed
    	return false;
    }
}
