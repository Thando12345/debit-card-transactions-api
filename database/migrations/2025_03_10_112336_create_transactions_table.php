<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('debit_card_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->enum('type', ['credit', 'debit']);
                $table->enum('status', ['pending', 'completed', 'failed']);
                $table->text('description')->nullable();
                $table->string('merchant_name')->nullable();
                $table->dateTime('transaction_date');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
