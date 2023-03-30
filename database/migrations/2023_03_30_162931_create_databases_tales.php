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
        Schema ::create('databases', function (Blueprint $table) {
            $table -> id();
            $table -> unsignedBigInteger('domain_id');
            $table -> string('host');
            $table -> unsignedInteger('port') -> default(3306);
            $table -> string('database');
            $table -> string('username');
            $table -> string('password');
            $table -> string('cart_name') -> nullable();
            $table -> string('product_name') -> nullable();
            $table -> string('cart_primary_key') -> nullable();
            $table -> string('product_primary_key') -> nullable();
            $table -> index('domain_id');
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
        Schema ::dropIfExists('databases');
    }
};
