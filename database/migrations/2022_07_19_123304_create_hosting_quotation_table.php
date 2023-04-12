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
        Schema::create('hosting_quotation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hosting_id')->constrained('hostings')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('price')->default(0);
            $table->integer('quantity')->default(1);
            $table->integer('discount')->default(0);
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
        Schema::dropIfExists('hosting_quotation');
    }
};
