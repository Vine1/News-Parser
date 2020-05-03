<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id');
            $table->text('author');
            $table->text('title');
            $table->text('description');
            $table->text('content');
            $table->text('published_at');
            $table->text('url');
            $table->text('url_to_image');
            $table->timestamps();

            $table->foreign('source_id')->references('id')->on('news_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
