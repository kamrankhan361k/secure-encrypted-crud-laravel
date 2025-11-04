<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecureDataTable extends Migration
{
    public function up()
    {
        Schema::create('secure_data', function (Blueprint $table) {
            $table->id();
            $table->text('name'); // Encrypted
            $table->text('email'); // Encrypted
            $table->text('phone'); // Encrypted
            $table->text('address'); // Encrypted
            $table->text('credit_card'); // Encrypted
            $table->text('social_security_number'); // Encrypted
            $table->text('medical_info')->nullable(); // Encrypted
            $table->text('financial_info')->nullable(); // Encrypted
            $table->string('security_level')->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('security_level');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('secure_data');
    }
}
