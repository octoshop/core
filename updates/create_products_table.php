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
            $table->string('tagline')->nullable();
            $table->string('model')->nullable();
            $table->longText('description');
            $table->boolean('is_enabled')->default(false);
            $table->integer('is_available', 1)->default(1);
            $table->boolean('is_visible')->default(true);
            $table->dateTime('available_at')->nullable();
            $table->decimal('price', 20, 5)->default(0)->nullable();
            $table->integer('minimum_qty')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octoshop_products');
    }
}
