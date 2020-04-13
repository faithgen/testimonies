<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimoniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fg_testimonies', function (Blueprint $table) {
            $table->string('id', 150)->primary();
            $table->string('ministry_id', 150)->index();
            $table->string('user_id', 150)->index();
            $table->string('title');
            $table->text('testimony');
            $table->string('resource')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();

            $table->foreign('ministry_id')->references('id')->on('ministries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fg_testimonies');
    }
}
