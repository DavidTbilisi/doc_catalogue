<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIoGroupsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('io_groups_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("io_id")->constrained("io");
            $table->foreignId("groups_id")->constrained("groups");
            $table->integer("permission")->default(17)->comment("ერთი რიცხვი რომელიც მიუთითებს სრული უფლებების სიას");
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
        Schema::dropIfExists('io_groups_permissions');
    }
}
