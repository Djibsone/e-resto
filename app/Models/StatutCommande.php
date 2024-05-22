<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutCommande extends Model
{
    use HasFactory;

    protected $fillable = ['type_statut'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    
    public function ligneCommandes()
    {
        return $this->hasMany(CommandePlatRestaurantStatut::class);
    }
}
