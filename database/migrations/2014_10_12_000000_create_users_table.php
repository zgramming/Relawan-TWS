<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('id_type_organization')->nullable();
            $table->string('phone')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('birth_date')->nullable();
            $table->enum('type', ['relawan', 'organisasi']);
            $table->text('address')->nullable();
            $table->text('website')->nullable();
            $table->text('whatsapp_contact')->nullable();
            $table->text('email_contact')->nullable();
            $table->text('instagram_contact')->nullable();
            $table->text('picture_profile')->nullable();
            $table->text('logo')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
