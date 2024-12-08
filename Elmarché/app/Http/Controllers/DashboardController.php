<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // For sellers, show their own products
        if ($user->profile === 'VENDEUR') {
            $produits = Produit::where('user_id', $user->id)->latest()->get();
            
            // Product statistics for sellers
            $productStats = [
                'total_products' => $produits->count(),
                'total_stock' => $produits->sum('qte'),
                'low_stock_products' => $produits->where('qte', '<=', 5)->count(),
                'total_value' => $produits->sum(function($produit) {
                    return $produit->prix * $produit->qte;
                })
            ];
        } else {
            // For buyers, show all products
            $produits = Produit::latest()->get();
            $productStats = null;
        }

        // Fetch user's orders
        $commandes = $user->profile === 'VENDEUR' 
            ? Commande::whereHas('produit', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->latest()->get()
            : Commande::where('user_id', $user->id)->latest()->get();

        // Order statistics
        $orderStats = [
            'total_orders' => $commandes->count(),
            'pending_orders' => $commandes->where('etat', 'en_attente')->count(),
            'accepted_orders' => $commandes->where('etat', 'accepte')->count(),
            'rejected_orders' => $commandes->where('etat', 'refuse')->count(),
        ];

        return view('dashboard', [
            'produits' => $produits,
            'commandes' => $commandes,
            'productStats' => $productStats,
            'orderStats' => $orderStats
        ]);
    }
}
