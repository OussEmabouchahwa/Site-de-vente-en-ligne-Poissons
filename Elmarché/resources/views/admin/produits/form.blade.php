@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-blue-600 mb-6">
            {{ isset($produit) ? 'Modifier le produit' : 'Ajouter un produit' }}
        </h1>

        <form action="{{ isset($produit) ? route('admin.produits.update', $produit) : route('admin.produits.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($produit))
                @method('PUT')
            @endif

            <div>
                <label class="block text-blue-600 mb-2" for="nom">Nom du produit</label>
                <input type="text" name="nom" id="nom" 
                       value="{{ old('nom', $produit->nom ?? '') }}"
                       class="w-full border-black rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="description">Description</label>
                <textarea name="description" id="description" rows="4" 
                          class="w-full border-black rounded-md shadow-sm" required>{{ old('description', $produit->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="prix">Prix</label>
                <input type="number" name="prix" id="prix" step="0.01" min="0"
                       value="{{ old('prix', $produit->prix ?? '') }}"
                       class="w-full border-black rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="qte">Quantité en stock</label>
                <input type="number" name="qte" id="qte" min="0"
                       value="{{ old('qte', $produit->qte ?? '') }}"
                       class="w-full border-black rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="image">Image du produit</label>
                @if(isset($produit) && $produit->image_path)
                    <img src="{{ $produit->image_url }}" alt="Image actuelle" class="w-32 h-32 object-cover mb-2">
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full border-black rounded-md shadow-sm" 
                       {{ isset($produit) ? '' : 'required' }}>
            </div>

            <div class="mt-8">
                <button type="submit" 
                        class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                    {{ isset($produit) ? 'Mettre à jour' : 'Ajouter' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
