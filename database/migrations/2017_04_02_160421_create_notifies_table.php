<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('obj_id');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('users_has_notifies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('notify_id')->foreign()
                ->references('id')->on('notifies')
                ->onDelete('cascade');
            $table->unsignedInteger('user_id')->foreign()
                ->references('id')->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('notifies');
        Schema::dropIfExists('users_has_notifies');
    }
}
