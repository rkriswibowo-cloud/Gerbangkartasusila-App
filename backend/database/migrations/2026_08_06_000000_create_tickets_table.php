<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('visitor_name');
            $table->string('visitor_email');
            $table->string('visitor_phone');
            $table->date('visit_date');
            $table->integer('quantity');
            $table->decimal('price_per_ticket', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('payment_method')->default('QRIS');
            $table->string('status')->default('paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
