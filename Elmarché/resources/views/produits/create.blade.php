@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-black">
                <h2 class="text-2xl font-semibold mb-6 text-blue-600">
                    <i class="fas fa-plus-circle mr-2"></i>Créer un Nouveau Produit
                </h2>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data" 
                      class="space-y-6" 
                      x-data="{ 
                          imagePreview: null,
                          previewImage(event) {
                              const input = event.target;
                              if (input.files && input.files[0]) {
                                  const reader = new FileReader();
                                  reader.onload = (e) => {
                                      this.imagePreview = e.target.result;
                                  };
                                  reader.readAsDataURL(input.files[0]);
                              } else {
                                  this.imagePreview = null;
                              }
                          }
                      }">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nom" class="block text-sm font-medium text-blue-600">Nom du Produit *</label>
                        <input type="text" name="nom" id="nom" 
                               value="{{ old('nom') }}"
                               class="mt-1 block w-full rounded-md border-black shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50" 
                               required>
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-blue-600">Description *</label>
                        <textarea name="description" id="description" 
                                  class="mt-1 block w-full rounded-md border-black shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50" 
                                  rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="prix" class="block text-sm font-medium text-blue-600">Prix *</label>
                            <input type="number" name="prix" id="prix" 
                                   value="{{ old('prix') }}"
                                   step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-black shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50" 
                                   required>
                            @error('prix')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="qte" class="block text-sm font-medium text-blue-600">Quantité *</label>
                            <input type="number" name="qte" id="qte" 
                                   value="{{ old('qte') }}"
                                   min="0"
                                   class="mt-1 block w-full rounded-md border-black shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50" 
                                   required>
                            @error('qte')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-blue-600">Image du Produit</label>
                        <div class="flex items-center space-x-4 mt-2">
                            <input type="file" name="image" id="image" 
                                   class="hidden" 
                                   x-ref="imageInput"
                                   x-on:change="previewImage($event)"
                                   accept="image/jpeg,image/png,image/gif">
                            <button type="button" 
                                    x-on:click="$refs.imageInput.click()" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-upload mr-2"></i>Choisir une Image
                            </button>
                            <img x-show="imagePreview" 
                                 :src="imagePreview" 
                                 alt="Aperçu de l'image" 
                                 class="h-24 w-24 object-cover rounded" />
                        </div>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('produits.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i>Enregistrer le Produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
