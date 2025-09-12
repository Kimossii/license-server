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
        Schema::create('server_licenses', function (Blueprint $table) {
             $table->id();
            $table->string('customer_registered_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('hardware_id')->nullable();
            $table->date('expire_in')->nullable();
            $table->text('license_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_licenses');
    }
};
