@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-600 leading-tight">
            {{ $produit->nom }} - Détails
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-8 p-8">
                    <div>
                        <div class="relative rounded-lg overflow-hidden shadow-lg mb-6">
                            @if($produit->image_path)
                                <img src="{{ asset('storage/' . $produit->image_path) }}" alt="{{ $produit->nom }}"
                                     class="w-full h-96 object-cover">
                            @else
                                <div class="w-full h-96 bg-gray-200 flex items-center justify-center text-gray-500">
                                    Pas d'image disponible
                                </div>
                            @endif

                            @if($produit->qte <= 0)
                                <span class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded text-sm">
                                    Rupture de stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="mb-6">
                            <h1 class="text-3xl font-bold text-black mb-2">{{ $produit->nom }}</h1>
                            <p class="text-gray-600 text-lg mb-4">{{ $produit->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <span class="block text-sm text-gray-600 mb-1">Prix</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    {{ number_format($produit->prix, 2) }} €
                                </span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-600 mb-1">Stock</span>
                                <span class="text-xl font-semibold
                                    {{ $produit->qte > 10 ? 'text-green-600' :
                                       ($produit->qte > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                    <i class="fas fa-box mr-2"></i>{{ $produit->qte }}
                                    {{ $produit->qte == 1 ? 'unité' : 'unités' }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <span class="block text-sm text-gray-600 mb-2">Vendeur</span>
                            <div class="flex items-center">
                                <img src="{{ $produit->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                     alt="{{ $produit->user->name }}"
                                     class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <span class="font-semibold text-black">{{ $produit->user->name }}</span>
                                    <span class="block text-sm text-gray-500">{{ $produit->user->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-4">
                            @can('update', $produit)
                                <a href="{{ route('produits.edit', $produit) }}"
                                   class="flex-1 text-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded">
                                    <i class="fas fa-edit mr-2"></i>Modifier le Produit
                                </a>
                            @endcan

                            @if($produit->qte > 0)
                                <form action="{{ route('commandes.store') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                    <button type="submit"
                                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded">
                                        <i class="fas fa-shopping-cart mr-2"></i>Ajouter au Panier
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if(auth()->user()->profile === 'ACHETEUR' && $produit->qte > 0)
                            <a href="{{ route('commandes.create', $produit) }}" 
                               class="mt-4 w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 text-center inline-block">
                                Commander
                            </a>
                        @endif
                    </div>
                </div>

                @can('update', $produit)
                    <div class="bg-gray-50 border-t border-gray-200 p-6 text-center">
                        <form action="{{ route('produits.destroy', $produit) }}" method="POST"
                              x-data="{ showConfirm: false }"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    x-on:click="showConfirm = true"
                                    class="text-red-500 hover:text-red-600 font-bold">
                                <i class="fas fa-trash mr-2"></i>Supprimer ce Produit
                            </button>

                            <div x-show="showConfirm"
                                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white p-6 rounded-lg shadow-xl">
                                    <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
                                    <p class="mb-4">Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.</p>
                                    <div class="flex justify-end space-x-4">
                                        <button type="button"
                                                x-on:click="showConfirm = false"
                                                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                            Confirmer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
