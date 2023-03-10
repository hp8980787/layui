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
        Schema ::create('files', function (Blueprint $table) {
            $table -> id();
            $table -> string('name');
            $table -> string('original_name');
            $table -> string('extension');
            $table -> string('path');
            $table -> unsignedInteger('model_id');
            $table -> string('model_type');
            $table -> index('model_id');
            $table -> index('model_type');
            $table -> softDeletes();
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists('files');
    }
};
