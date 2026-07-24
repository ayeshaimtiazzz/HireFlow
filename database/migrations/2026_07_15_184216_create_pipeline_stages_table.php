<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_posting_id')->nullable()->constrained('job_postings')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('order_position')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('requires_scorecard')->default(false);
            $table->foreignId('auto_email_template_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_stages');
    }
};
