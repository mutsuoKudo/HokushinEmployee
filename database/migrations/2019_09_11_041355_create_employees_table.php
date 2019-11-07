<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('shain_cd',10);
            $table->primary('shain_cd');
            $table->string('shain_mei',30);
            $table->string('shain_mei_kana',50);
            $table->string('shain_mei_romaji',50);
            $table->string('shain_mail',50)->nullable();
            $table->string('gender',10);
            $table->string('shain_zip_code',8)->nullable();
            $table->string('shain_jyusho',100)->nullable();
            $table->string('shain_jyusho_tatemono',100)->nullable();
            $table->string('shain_birthday',100)->nullable();
            $table->date('nyushabi')->nullable();
            $table->date('seishain_tenkanbi')->nullable();
            $table->date('tensekibi')->nullable();
            $table->date('taishokubi')->nullable();
            $table->string('shain_keitai',13)->nullable();
            $table->string('shain_tel',12)->nullable();
            $table->string('koyohoken_bango',13)->nullable();
            $table->integer('shakaihoken_bango')->nullable();
            $table->string('kisonenkin_bango',11)->nullable();
            $table->integer('monthly_saraly')->nullable();
            $table->string('department',2)->nullable();
            $table->integer('name_card')->nullable();
            $table->tinyInteger('id_card')->nullable();
            $table->tinyInteger('fuyo_kazoku')->nullable();
            $table->integer('test')->nullable();
            $table->string('pic')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
