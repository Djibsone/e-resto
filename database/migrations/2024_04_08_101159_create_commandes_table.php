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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('ref_cmde')->nullable();
            $table->date('date_cmde');
            $table->integer('price_total');
            $table->string('address_livraison');
            $table->unsignedBigInteger('user_id')->constrained()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
