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
        Schema::create('zakat_committies', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('lzc_name', length:60);
            $table->unsignedInteger('no_of_beneficiaries');
            $table->string('bank_name', length:60);
            $table->string('acc_no', length:60);
            $table->unsignedBigInteger('ac_id');
            $table->unsignedBigInteger('mna_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakatcommitties');
    }
};
