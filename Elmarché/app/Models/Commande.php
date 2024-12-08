<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'produit_id',
        'qte',
        'adresse_livraison',
        'code',
        'etat',
        'mode_livraison',
        'mode_payment',
        'RIP'
    ];

    public $errors;

    public function validate()
    {
        $validator = Validator::make($this->attributesToArray(), [
            'user_id' => 'required|exists:users,id',
            'produit_id' => 'required|exists:produits,id',
            'qte' => 'required|integer|min:1',
            'adresse_livraison' => 'required|string|min:10|max:255',
            'code' => 'required|unique:commandes,code',
            'etat' => 'required|in:en_attente,accepte,refuse',
            'mode_livraison' => 'required|in:domicile,point_relais',
            'mode_payment' => 'required|in:carte,virement',
            'RIP' => 'nullable|required_if:mode_payment,virement|string|max:50'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();
            return false;
        }

        return true;
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: Add a boot method for additional validation
    protected static function boot()
    {
        parent::boot();

        // Validate before creating
        static::creating(function ($model) {
            return $model->validate();
        });
    }
}
