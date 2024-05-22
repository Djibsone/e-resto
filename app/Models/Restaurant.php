<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name_resto', 'localisation', 'url', 'open_hour', 'close_hour', 'numero_resto', 'description', 'address', 'user_id'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function plats()
    {
        return $this->hasMany(Plat::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(CommandePlatRestaurantStatut::class);
    }
}
