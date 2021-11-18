<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIoTypesTable extends Migration
{
    public function up()
    {
        Schema::create('io_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("table");
            $table->softDeletes();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('io_types');
    }
}
