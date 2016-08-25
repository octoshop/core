<?php namespace Octoshop\Core\Updates;

use Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('octoshop_products', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->index();
            $table->string('slug')->index()->unique();
            $table->longText('description');
            $table->string('intro')->nullable();
            $table->decimal('price', 20, 5)->default(0)->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->dateTime('available_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octoshop_products');
    }
}
