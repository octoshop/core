<?php namespace Octoshop\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTaxesTable extends Migration
{
    protected $table = 'octoshop_taxes';

    public function up()
    {
        Schema::create($this->table, function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code', 30)->nullable();
            $table->text('description')->nullable();
            $table->mediumText('rates')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
