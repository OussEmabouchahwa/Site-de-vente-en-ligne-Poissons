<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function create()
    {
        return view('admin.produits.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'qte' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produits', 'public');
            $validatedData['image_path'] = $imagePath;
        }

        Produit::create($validatedData);

        return redirect()->route('dashboard')
            ->with('success', 'Produit ajouté avec succès!');
    }

    public function edit(Produit $produit)
    {
        return view('admin.produits.edit', compact('produit'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'qte' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image_path) {
                Storage::disk('public')->delete($produit->image_path);
            }
            
            $imagePath = $request->file('image')->store('produits', 'public');
            $validatedData['image_path'] = $imagePath;
        }

        $produit->update($validatedData);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit mis à jour avec succès!');
    }

    public function destroy(Produit $produit)
    {
        if ($produit->image_path) {
            Storage::disk('public')->delete($produit->image_path);
        }
        
        $produit->delete();
        
        return redirect()->route('dashboard')
            ->with('success', 'Produit supprimé avec succès!');
    }
}
