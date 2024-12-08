@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-blue-600 mb-6">{{ __('Ajouter un Produit') }}</h1>

        <form action="{{ route('admin.produits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-blue-600 mb-2" for="nom">{{ __('Nom du produit') }}</label>
                <input type="text" name="nom" id="nom" 
                       value="{{ old('nom') }}"
                       class="w-full border-black rounded-md shadow-sm" required>
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="description">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="4" 
                          class="w-full border-black rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="prix">{{ __('Prix') }}</label>
                <input type="number" name="prix" id="prix" step="0.01" min="0"
                       value="{{ old('prix') }}"
                       class="w-full border-black rounded-md shadow-sm" required>
                @error('prix')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="qte">{{ __('Quantité en stock') }}</label>
                <input type="number" name="qte" id="qte" min="0"
                       value="{{ old('qte') }}"
                       class="w-full border-black rounded-md shadow-sm" required>
                @error('qte')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="image">{{ __('Image du produit') }}</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full border-black rounded-md shadow-sm" required>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8">
                <button type="submit" 
                        class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                    {{ __('Ajouter') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
