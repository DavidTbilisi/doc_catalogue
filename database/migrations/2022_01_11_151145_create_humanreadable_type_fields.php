<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHumanreadableTypeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('humanreadable_type_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId("io_type_id")->constrained(); // connected with io_types and other type tables
            $table->text("fields"); // json {"field1":"transl1","field2":"transl2",...}
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
        Schema::dropIfExists('humanreadable_type_fields');
    }
}
