<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'price', 'url', 'restaurant_id'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(CommandePlatRestaurantStatut::class);
    }
}
