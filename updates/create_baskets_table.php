<?php namespace Octoshop\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('octoshop_baskets', function (Blueprint $table) {
            $table->string('identifier');
            $table->string('instance');
            $table->longText('content');
            $table->timestamps();

            $table->primary(['identifier', 'instance']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('octoshop_baskets');
    }
}
