<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToToppingTable extends Migration
{
    public function up()
    {
        Schema::table('topping', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('topping', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

};
