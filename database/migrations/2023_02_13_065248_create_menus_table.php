<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create('menus', function (Blueprint $table) {
            $table -> id();
            $table -> unsignedBigInteger('parent_id') -> nullable();
            $table -> string('title');
            $table -> string('icon') -> nullable();
            $table -> unsignedTinyInteger('type');
            $table -> string('open_type');
            $table -> string('href') -> nullable();
            $table -> unsignedTinyInteger('status') -> default(1);
            $table -> softDeletes();
            $table -> timestamps();
            $table -> index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists('menus');
    }
};
