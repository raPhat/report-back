<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('code')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('students_has_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('user_id');
            $table->timestamps();
        });

        $student = [
            'name' => 'Veerapat In-ongkarn',
            'email' => 'karjkeng@hotmail.com',
            'password' => bcrypt('karjkeng'),
            'role' => 'student'
        ];
        $mentor = [
            'name' => 'mentor',
            'email' => 'mentor@hotmail.com',
            'password' => bcrypt('karjkeng'),
            'role' => 'mentor'
        ];
        DB::table('users')->insert($student);
        DB::table('users')->insert($mentor);
        DB::table('students_has_users')->insert([
            'student_id' => 1,
            'user_id' => 2
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('students_has_users');
    }
}
