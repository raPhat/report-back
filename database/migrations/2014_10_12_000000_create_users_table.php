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
            $table->text('description');
            $table->string('role');
            $table->string('code')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('students_has_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')->foreign()
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->unsignedInteger('user_id')->foreign()
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });

        $student = [
            'name' => 'Veerapat In-ongkarn',
            'description' => 'Junior Frontend Developer',
            'email' => 'karjkeng@hotmail.com',
            'password' => bcrypt('karjkeng'),
            'role' => 'student'
        ];
        $mentor = [
            'name' => 'Juan Welch',
            'description' => 'hmmmm',
            'email' => 'mentor@hotmail.com',
            'password' => bcrypt('123456'),
            'role' => 'mentor',
            'code' => '12345'
        ];
        $mentor2 = [
            'name' => 'Russell Alvarez',
            'description' => 'super mentor',
            'email' => 'mentor2@hotmail.com',
            'password' => bcrypt('karjkeng'),
            'role' => 'mentor',
            'code' => '23456'
        ];
        $super = [
            'name' => 'Eugene Gomez',
            'description' => 'super supervisor',
            'email' => 'super@hotmail.com',
            'password' => bcrypt('karjkeng'),
            'role' => 'supervisor',
            'code' => '11111'
        ];
        DB::table('users')->insert($student);
        DB::table('users')->insert($mentor);
        DB::table('users')->insert($mentor2);
        DB::table('users')->insert($super);
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
