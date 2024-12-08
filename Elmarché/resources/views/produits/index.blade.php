@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-black">
                <h2 class="text-2xl font-semibold mb-6 text-blue-600">Nos Produits</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($produits as $produit)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @if($produit->image_path)
                                <img src="{{ asset('storage/' . $produit->image_path) }}" 
                                     alt="{{ $produit->nom }}" 
                                     class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <h3 class="text-xl font-semibold mb-2">{{ $produit->nom }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($produit->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-blue-600">{{ number_format($produit->prix, 2) }} €</span>
                                    <span class="text-sm {{ $produit->qte > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $produit->qte }} en stock
                                    </span>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('produits.show', $produit) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($produits->hasPages())
                    <div class="mt-6">
                        {{ $produits->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
