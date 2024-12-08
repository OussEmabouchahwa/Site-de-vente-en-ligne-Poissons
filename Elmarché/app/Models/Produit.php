<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'qte',
        'image_path',
        'user_id'
    ];

    // Ensure user_id is always set when creating a new product
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Log the creation attempt
            \Log::channel('daily')->info('Produit Model Creating', [
                'current_user_id' => Auth::id(),
                'model_user_id' => $model->user_id,
                'is_authenticated' => Auth::check()
            ]);

            // If user_id is not set, try to set it from the authenticated user
            if (empty($model->user_id)) {
                $model->user_id = Auth::id();
            }

            // If still not set, throw an exception
            if (empty($model->user_id)) {
                \Log::channel('daily')->error('Cannot create Produit: No user ID available');
                throw new \Exception('Impossible de créer un produit sans utilisateur connecté');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('images/default-product.jpg');
    }
}
