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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users');
            $table->string('subject')->nullable();
            $table->enum('invoice_type', ['quotation', 'custom'])->default('custom');
            $table->integer('total_price')->nullable();
            $table->integer('discount')->default(0);
            $table->integer('grand_total')->nullable();
            $table->integer('qty')->default(1);
            $table->integer('pay')->nullable();
            $table->integer('due')->nullable();
            $table->text('note')->nullable();
            $table->json('items')->nullable();
            $table->enum('status', ['created', 'sent', 'canceled', 'paid'])->default('created');
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('currency')->default('taka');
            $table->longText('trams_of_service')->nullable();
            $table->longText('payment_policy')->nullable();
            $table->longText('payment_methods')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
