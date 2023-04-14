<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->renameColumn('date_approved_at', 'approved_at');
            $table->dateTime('denied_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->dateTime('due_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->renameColumn('approved_at', 'date_approved_at');
            $table->dropColumn('denied_at');
            $table->dropColumn('returned_at');
            $table->dropColumn('due_at');
        });
    }
};
