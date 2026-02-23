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
        Schema::create('pending_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_group_id')->constrained();
            $table->foreignId('assigned_to_user_id')->constrained('users');
            $table->foreignId('creator_user_id')->constrained('users');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('due_date')->nullable();
            $table->enum('status', ['PENDING', 'PAID', 'CANCELLED'])->default('PENDING');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_transactions');
    }
};
