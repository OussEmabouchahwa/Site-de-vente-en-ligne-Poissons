<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;

class DiagnoseOrderIssue extends Command
{
    protected $signature = 'diagnose:orders {user_id?}';
    protected $description = 'Diagnose order retrieval issues for a specific user or all users';

    public function handle()
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $this->diagnoseUser($userId);
        } else {
            $users = User::where('profile', 'VENDEUR')->get();
            foreach ($users as $user) {
                $this->diagnoseUser($user->id);
            }
        }
    }

    protected function diagnoseUser($userId)
    {
        $user = User::findOrFail($userId);
        
        $this->info("Diagnosing User: {$user->name} (ID: {$user->id})");
        $this->info("Profile: {$user->profile}");

        // Check user's products
        $products = Produit::where('user_id', $userId)->get();
        $this->info("Total Products: {$products->count()}");
        foreach ($products as $product) {
            $this->info("  Product ID: {$product->id}, Name: {$product->nom}, Stock: {$product->qte}");
        }

        // Check commandes for these products
        $commandes = Commande::whereHas('produit', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('produit', 'user')->get();

        $this->info("Total Commandes: {$commandes->count()}");
        foreach ($commandes as $commande) {
            $this->info("  Commande ID: {$commande->id}");
            $this->info("    Produit: {$commande->produit->nom}");
            $this->info("    Quantity: {$commande->qte}");
            $this->info("    Status: {$commande->etat}");
            $this->info("    Client: {$commande->user->name}");
        }

        $this->line('---');
    }
}
