<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('payment_code')->nullable();
            $table->timestamp('code_generated_at')->nullable();
            $table->timestamp('code_expires_at')->nullable();
            $table->timestamp('code_used_at')->nullable();
            $table->enum('status', ['pending', 'paid', 'active', 'rejected', 'expired'])->default('pending');
            $table->text('rejected_reason')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_requests');
    }
};