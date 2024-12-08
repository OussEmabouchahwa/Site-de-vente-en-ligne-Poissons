@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-600 leading-tight">
            {{ __('Modifier le Produit') }} - {{ $produit->nom }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black">
                    <form action="{{ route('produits.update', $produit) }}" method="POST" enctype="multipart/form-data" 
                          class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="productForm()">
                        @csrf
                        @method('PUT')

                        <div class="md:col-span-2">
                            <label for="nom" class="block text-blue-600 font-bold mb-2">Nom du Produit</label>
                            <input type="text" name="nom" id="nom" 
                                   class="w-full px-3 py-2 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror" 
                                   value="{{ old('nom', $produit->nom) }}" required>
                            @error('nom')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-blue-600 font-bold mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full px-3 py-2 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      required>{{ old('description', $produit->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prix" class="block text-blue-600 font-bold mb-2">Prix (€)</label>
                            <input type="number" name="prix" id="prix" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prix') border-red-500 @enderror" 
                                   value="{{ old('prix', $produit->prix) }}" required>
                            @error('prix')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="qte" class="block text-blue-600 font-bold mb-2">Quantité en Stock</label>
                            <input type="number" name="qte" id="qte" min="0"
                                   class="w-full px-3 py-2 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('qte') border-red-500 @enderror" 
                                   value="{{ old('qte', $produit->qte) }}" required>
                            @error('qte')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="image" class="block text-blue-600 font-bold mb-2">Image du Produit</label>
                            <div class="flex items-center space-x-4">
                                <input type="file" name="image" id="image" 
                                       class="hidden" 
                                       x-ref="imageInput"
                                       x-on:change="previewImage($event)"
                                       accept="image/*">
                                <button type="button" 
                                        x-on:click="$refs.imageInput.click()" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-upload mr-2"></i>Changer l'Image
                                </button>
                                
                                @if($produit->image_path)
                                    <img x-ref="imagePreview" 
                                         src="{{ asset('storage/' . $produit->image_path) }}" 
                                         alt="Aperçu de l'image" 
                                         class="h-24 w-24 object-cover rounded" />
                                @else
                                    <div x-ref="imagePreview" 
                                         class="h-24 w-24 bg-gray-200 flex items-center justify-center rounded">
                                        Pas d'image
                                    </div>
                                @endif
                            </div>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end space-x-4 mt-6">
                            <a href="{{ route('dashboard') }}" 
                               class="bg-black hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </a>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-save mr-2"></i>Enregistrer les Modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function productForm() {
        return {
            previewImage(event) {
                const input = event.target;
                const preview = this.$refs.imagePreview;
                
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = (e) => {
                        preview.src = e.target.result;
                        preview.classList.remove('bg-gray-200');
                        preview.classList.remove('items-center');
                        preview.classList.remove('justify-center');
                        preview.classList.add('object-cover');
                    };
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };
    }
</script>
@endpush
