<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIoTable extends Migration
{
    /**
     * Run the migrations.
     * io -> information object
     * @return void
     */
    public function up()
    {
        Schema::create('io', function (Blueprint $table) {
            $table->id();
            $table->foreignId('io_type_id')->constrained();
            $table->string('suffix')->nullable();
            $table->integer('identifier')->nullable();
            $table->string('prefix')->nullable();
            $table->string('reference')->nullable();
            $table->integer('level')->nullable();
            $table->integer('parent_id');
            $table->integer('data_id');
            $table->softDeletes($column = 'deleted_at', $precision = 0)->nullable();
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
        Schema::dropIfExists('io');
    }
}
