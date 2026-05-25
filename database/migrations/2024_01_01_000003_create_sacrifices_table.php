<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sacrifices', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->enum('sacrifice_type', ['palestina', 'nusantara'])->default('nusantara');
            $table->string('donor_name');
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->enum('animal_type', ['unta', 'sapi', 'domba']);
            $table->decimal('animal_price', 12, 2)->nullable();
            $table->enum('sharing_type', ['full', 'collective'])->default('full');
            $table->integer('share_ratio')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('purchase_location')->nullable();
            $table->string('slaughter_location')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->text('beneficiary_address')->nullable();
            $table->string('beneficiary_type')->nullable();
            $table->enum('status_purchase', ['pending', 'completed'])->default('pending');
            $table->date('date_purchase_completed')->nullable();
            $table->enum('status_slaughter', ['pending', 'completed'])->default('pending');
            $table->date('date_slaughter_completed')->nullable();
            $table->enum('status_distribution', ['pending', 'completed'])->default('pending');
            $table->date('date_distribution_completed')->nullable();
            $table->enum('status_report', ['pending', 'completed'])->default('pending');
            $table->date('date_report_completed')->nullable();
            $table->string('certificate_file_path')->nullable();
            $table->timestamp('certificate_generated_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status_purchase', 'status_slaughter', 'status_distribution', 'status_report'], 'idx_all_statuses');
            $table->index('created_at');
            $table->index('donor_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sacrifices');
    }
};
