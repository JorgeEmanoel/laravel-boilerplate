<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('profile_id');
            $table->foreign('profile_id')
                ->references('id')
                ->on('profiles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onUpdate('cascade')
                ->onDelete('restrict');

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
        Schema::dropIfExists('module_profiles');
    }
}
