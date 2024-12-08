<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function index()
    {
        // For sellers, show only their products
        if (Auth::user()->profile === 'VENDEUR') {
            $produits = Produit::where('user_id', Auth::id())->latest()->paginate(9);
        } else {
            // For buyers, show all products
            $produits = Produit::latest()->paginate(9);
        }

        return view('produits.index', compact('produits'));
    }

    public function show(Produit $produit)
    {
        return view('produits.show', compact('produit'));
    }

    public function create()
    {
        return view('produits.create');
    }

    public function store(Request $request)
    {
        // More comprehensive validation
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|min:3',
            'description' => 'required|string|min:10|max:1000',
            'prix' => 'required|numeric|min:0|max:1000000',
            'qte' => 'required|integer|min:0|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nom.min' => 'Le nom du produit doit contenir au moins 3 caractères.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'prix.max' => 'Le prix ne peut pas dépasser 1 000 000.',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo.'
        ]);

        // Debugging: Log authentication and profile details
        \Log::channel('daily')->info('Product Creation Attempt', [
            'is_authenticated' => Auth::check(),
            'user_profile' => Auth::check() ? Auth::user()->profile : 'Not Authenticated',
            'user_id' => Auth::id(),
            'input_data' => $request->except(['_token', 'image']),
        ]);

        // Ensure user is authenticated and is a seller
        if (!Auth::check()) {
            \Log::channel('daily')->warning('Product Creation Failed: Not Authenticated');
            return back()->with('error', 'Vous devez être connecté pour créer un produit.');
        }

        if (Auth::user()->profile !== 'VENDEUR') {
            \Log::channel('daily')->warning('Product Creation Failed: Not a Seller', [
                'user_profile' => Auth::user()->profile
            ]);
            return back()->with('error', 'Vous n\'êtes pas autorisé à créer un produit. Votre profil doit être VENDEUR.');
        }

        // Transaction for data integrity
        DB::beginTransaction();
        try {
            // Create a new product
            $produit = new Produit();
            $produit->fill($validatedData);
            $produit->user_id = Auth::id();

            // Handle image upload with unique filename
            if ($request->hasFile('image')) {
                $imageName = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
                $imagePath = $request->file('image')->storeAs('produits', $imageName, 'public');
                $produit->image_path = $imagePath;
            }

            // Save the product
            $produit->save();

            DB::commit();

            // Log product creation
            Log::info('Nouveau produit créé', [
                'user_id' => Auth::id(),
                'produit_id' => $produit->id,
                'nom' => $produit->nom
            ]);

            return redirect()->route('produits.index')
                ->with('success', 'Produit créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error with full details
            Log::error('Erreur lors de la création du produit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'input_data' => $request->except(['_token', 'image'])
            ]);

            return back()->with('error', 'Une erreur est survenue lors de la création du produit: ' . $e->getMessage());
        }
    }

    public function edit(Produit $produit)
    {
        return view('produits.edit', compact('produit'));
    }

    public function update(Request $request, Produit $produit)
    {
        // Validate the request
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'qte' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Ensure user is authenticated and authorized
        if (!Auth::check() || Auth::id() !== $produit->user_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce produit.');
        }

        // Update the product
        $produit->update($validatedData);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($produit->image_path) {
                Storage::disk('public')->delete($produit->image_path);
            }

            // Store new image
            $imagePath = $request->file('image')->store('produits', 'public');
            $produit->image_path = $imagePath;
            $produit->save();
        }

        return redirect()->route('dashboard')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit)
    {
        // Ensure user is authenticated and authorized
        if (!Auth::check() || Auth::id() !== $produit->user_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce produit.');
        }

        // Delete the product's image if it exists
        if ($produit->image_path) {
            Storage::disk('public')->delete($produit->image_path);
        }

        // Delete the product
        $produit->delete();

        return redirect()->route('dashboard')->with('success', 'Produit supprimé avec succès.');
    }
}
