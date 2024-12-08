<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProduitPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Produit $produit)
    {
        // Only the product owner can update the product
        return $user->id === $produit->user_id;
    }

    public function delete(User $user, Produit $produit)
    {
        // Only the product owner can delete the product
        return $user->id === $produit->user_id;
    }

    public function view(User $user, Produit $produit)
    {
        // Anyone can view a product, but you can add custom logic if needed
        return true;
    }
}
