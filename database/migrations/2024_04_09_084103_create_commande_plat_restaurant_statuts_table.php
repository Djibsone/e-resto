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
        Schema::create('commande_plat_restaurant_statuts', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->integer('price_unit');
            $table->integer('price_total');
            $table->unsignedBigInteger('commande_id')->constrained()->cascadeOnDelete();
            $table->foreign('commande_id')->references('id')->on('commandes');
            $table->unsignedBigInteger('plat_id')->constrained()->cascadeOnDelete();
            $table->foreign('plat_id')->references('id')->on('plats');
            $table->unsignedBigInteger('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->unsignedBigInteger('statutCommande_id')->constrained()->cascadeOnDelete();
            $table->foreign('statutCommande_id')->references('id')->on('statut_commandes');
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_plat_restaurant_statuts');
    }
};
