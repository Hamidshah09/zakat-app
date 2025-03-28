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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('cnic', length:13);
            $table->string('name', length:30);
            $table->string('father_name', length:30)->nullable();
            $table->unsignedInteger('zc_id');
            $table->unsignedBigInteger('ac_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
