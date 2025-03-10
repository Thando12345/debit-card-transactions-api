<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Check if the table already exists before creating it
        if (!Schema::hasTable('debit_cards')) {
            Schema::create('debit_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('card_number', 16);
                $table->string('card_holder'); // Add this line
                $table->date('expiry_date');
                $table->string('cvv', 3);
                $table->string('status')->default('active');
                $table->decimal('daily_limit', 10, 2)->nullable();
                $table->boolean('is_frozen')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('debit_cards');
    }
};
