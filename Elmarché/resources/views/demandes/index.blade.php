@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-600 leading-tight">
                @if(auth()->user()->profile === 'ACHETEUR')
                    Mes Commandes
                @else
                    Mes Demandes de Vente
                @endif
            </h2>
            @if(auth()->user()->profile === 'VENDEUR')
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-black">
                        Total Demandes: {{ $commandes->count() }}
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($commandes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="mb-4">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <p class="text-xl text-gray-600 mb-4">
                        @if(auth()->user()->profile === 'ACHETEUR')
                            Vous n'avez pas encore passé de commandes.
                        @else
                            Aucune demande de vente pour le moment.
                        @endif
                    </p>
                    @if(auth()->user()->profile === 'ACHETEUR')
                        <a href="{{ route('produits.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            Découvrir nos Produits
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 gap-6">
                    @foreach($commandes as $commande)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        @if($commande->produit->image_path)
                                            <img src="{{ asset('storage/' . $commande->produit->image_path) }}" alt="{{ $commande->produit->nom }}" class="h-16 w-16 object-cover rounded-md">
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-500">
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="text-lg font-semibold text-black">
                                                {{ $commande->produit->nom }}
                                            </h3>
                                            @if(auth()->user()->profile === 'VENDEUR')
                                                <p class="text-sm text-gray-600">
                                                    Commandé par {{ $commande->user->name }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Quantité: {{ $commande->qte }}</p>
                                            <p class="text-lg font-bold text-blue-600">
                                                {{ number_format($commande->qte * $commande->produit->prix, 2) }} €
                                            </p>
                                        </div>
                                        
                                        <div>
                                            @switch($commande->etat)
                                                @case('en_attente')
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                        En attente
                                                    </span>
                                                    @break
                                                @case('accepte')
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                        Acceptée
                                                    </span>
                                                    @break
                                                @case('refuse')
                                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                                        Refusée
                                                    </span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                                
                                @if(auth()->user()->profile === 'VENDEUR' && $commande->etat === 'en_attente')
                                    <div class="mt-4 border-t border-gray-200 pt-4 flex justify-end space-x-4">
                                        <form action="{{ route('commandes.update-status', $commande) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="accepte">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                                Accepter
                                            </button>
                                        </form>
                                        <form action="{{ route('commandes.update-status', $commande) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="refuse">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
