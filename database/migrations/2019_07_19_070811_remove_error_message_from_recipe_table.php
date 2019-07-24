<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveErrorMessageFromRecipeTable extends Migration
{
    public function up()
    {
        Schema::table('recipe', function (Blueprint $table) {
            $table->dropColumn('error_message');
        });
    }

    public function down()
    {
        Schema::table('recipe', function (Blueprint $table) {
            $table->string('error_message')->nullable();
        });
    }
}
