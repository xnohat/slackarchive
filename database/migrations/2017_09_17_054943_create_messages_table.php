<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('message');
            $table->string('user')->index();
            $table->string('channel')->index();
            $table->timestamp('ts')->useCurrent()->nullable()->index();
            $table->string('fileid')->nullable();
            $table->string('filename')->nullable();
            $table->string('filetitle')->nullable();
            $table->string('filetype')->nullable();
            $table->string('file_slack_url')->nullable();
            $table->string('file_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user', 'ts']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
