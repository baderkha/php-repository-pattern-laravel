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
        $m = new \App\Models\User();
        Schema::create($m->getTable(),function (Blueprint $table){
            $table->id();
            $table->string("name")->nullable(true);
            $table->string("email")->nullable(true);
            $table->string("password")->nullable(true);
            $table->dateTime("created_at")->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime("updated_at")->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $m = new \App\Models\User();
        Schema::drop($m->getTable());
    }
};
