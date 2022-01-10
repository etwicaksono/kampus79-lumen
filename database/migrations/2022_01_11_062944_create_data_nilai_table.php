<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_nilai', function (Blueprint $table) {
            $table->increments("id");
            $table->string("nim", 32);
            $table->integer("id_mata_kuliah")->unsigned();
            $table->integer("id_dosen")->unsigned();
            $table->float("nilai");
            $table->string("keterangan", 128);
            $table->timestamps();
            $table->engine = "InnoDB";

            $table->foreign("nim")
                ->references("nim")
                ->on("mahasiswa")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("id_mata_kuliah")
                ->references("id")
                ->on("mata_kuliah")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("id_dosen")
                ->references("id")
                ->on("dosen")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_nilai');
    }
}