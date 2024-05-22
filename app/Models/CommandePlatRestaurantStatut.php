<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommandePlatRestaurantStatut extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['quantity', 'price_unit', 'price_total', 'commande_id', 'plat_id', 'restaurant_id', 'statutCommande_id'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function statut()
    {
        return $this->belongsTo(StatutCommande::class, 'statutCommande_id');
    }
}
