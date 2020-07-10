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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->char('fathers_name');
            $table->char('mothers_name');
            $table->char('cpf', 11);
            $table->char('rg');
            $table->char('orgao_exp', 10);
            $table->char('uf_ex', 2);
            $table->char('city', 2);
            $table->char('state', 2);
            $table->char('cep', 10);
            $table->char('number', 10);
            $table->char('neighborhood');
            $table->string('complement')->nullable()->default(null);
            $table->char('main_phone', 11);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('admin')->default(false);
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
