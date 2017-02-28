<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->date('start');
            $table->unsignedInteger('image_id')->nullable()->foreign()
            ->references('id')->on('images')
            ->onDelete('cascade');
            $table->unsignedInteger('task_type_id')
            ->default(1)
            ->foreign()
            ->references('id')->on('task_types')
            ->onDelete('cascade');
            $table->unsignedInteger('project_id')->foreign()
            ->references('id')->on('projects')
            ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('task_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        $type = ['name' => 'ToDo'];
        $db = DB::table('task_types')->insert($type);
        $type = ['name' => 'Doing'];
        $db = DB::table('task_types')->insert($type);
        $type = ['name' => 'Done'];
        $db = DB::table('task_types')->insert($type);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_types');
    }
}
