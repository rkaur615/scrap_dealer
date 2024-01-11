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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->foreignId("category_id")->constrained('ticket_categories')->onDelete('cascade');
            $table->string('title');
            $table->integer('priority')->default(0)->comment('0 - low, 1 - medium, 2 - high');
            $table->text('message');
            $table->integer('status')->default(0)->comment('0 - open, 1 - close, 2 - reOpen');
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
        Schema::dropIfExists('tickets');
    }
};
