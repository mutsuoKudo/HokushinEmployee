<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependent_infos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('shain_cd',10);
            $table->string('name',30);
            $table->string('name_kana',50);
            $table->string('gender',10);
            $table->date('birthday',10);
            $table->tinyInteger('haigusha');
            $table->string('kisonenkin_bango',11)->nullable($value = true);
            $table->date('shikakushutokubi');
            $table->date('updated_at')->nullable();
            $table->date('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependent_infos');
    }
}
