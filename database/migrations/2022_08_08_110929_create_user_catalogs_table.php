<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('title_id')->constrained('product_titles')->cascadeOnDelete();
            $table->foreignId('category')->constrained('categories')->cascadeOnDelete();
            $table->float('quantity')->nullable()->default(0.00);
            $table->string('unit', 10)->nullable()->default('');
            $table->float('price')->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_catalogs');
    }
};
