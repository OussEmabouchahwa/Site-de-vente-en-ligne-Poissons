@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(auth()->user()->profile === 'VENDEUR')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm mb-2">Total Produits</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $productStats['total_products'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm mb-2">Stock Total</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $productStats['total_stock'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm mb-2">Produits en Rupture</h3>
                        <p class="text-2xl font-bold text-red-600">{{ $productStats['low_stock_products'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm mb-2">Valeur Totale</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($productStats['total_value'], 2) }} €</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-semibold">{{ __("Gestion des Produits") }}</h2>
                                @if(auth()->user()->profile === 'VENDEUR')
                                    <a href="{{ route('produits.create') }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                        {{ __("Ajouter un Produit") }}
                                    </a>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @forelse ($produits as $produit)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                        <a href="{{ route('produits.show', $produit) }}" class="block">
                                            @if ($produit->image_path)
                                                <img src="{{ asset('storage/' . $produit->image_path) }}"
                                                     alt="{{ $produit->nom }}"
                                                     class="w-full h-48 object-cover">
                                            @else
                                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500">{{ __("Pas d'image") }}</span>
                                                </div>
                                            @endif

                                            <div class="p-4">
                                                <h3 class="text-lg font-semibold mb-2">{{ $produit->nom }}</h3>
                                                <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $produit->description }}</p>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-blue-600 font-bold">{{ number_format($produit->prix, 2) }} €</span>
                                                    <span class="text-gray-500">Stock: {{ $produit->qte }}</span>
                                                </div>
                                            </div>
                                        </a>
                                        @if(auth()->user()->profile === 'VENDEUR')
                                            <div class="p-4 pt-0 flex justify-end space-x-2">
                                                <a href="{{ route('produits.edit', $produit) }}"
                                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                                    {{ __("Modifier") }}
                                                </a>
                                                <form action="{{ route('produits.destroy', $produit) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('{{ __("Êtes-vous sûr de vouloir supprimer ce produit ?") }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                        {{ __("Supprimer") }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-12 text-gray-500">
                                        {{ __("Aucun produit n'a été ajouté pour le moment.") }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Statistiques des Commandes') }}</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ __('Total Commandes') }}</span>
                                    <span class="font-bold text-blue-600">{{ $orderStats['total_orders'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ __('Commandes en Attente') }}</span>
                                    <span class="font-bold text-yellow-600">{{ $orderStats['pending_orders'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ __('Commandes Acceptées') }}</span>
                                    <span class="font-bold text-green-600">{{ $orderStats['accepted_orders'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ __('Commandes Refusées') }}</span>
                                    <span class="font-bold text-red-600">{{ $orderStats['rejected_orders'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Mes Commandes Récentes') }}</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left bg-gray-100">
                                    <th class="px-4 py-2">{{ __('Produit') }}</th>
                                    <th class="px-4 py-2">{{ __('Quantité') }}</th>
                                    <th class="px-4 py-2">{{ __('Prix') }}</th>
                                    <th class="px-4 py-2">{{ __('Statut') }}</th>
                                    <th class="px-4 py-2">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commandes as $commande)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $commande->produit->nom }}</td>
                                        <td class="px-4 py-3">{{ $commande->quantite }}</td>
                                        <td class="px-4 py-3">{{ number_format($commande->produit->prix * $commande->quantite, 2) }} €</td>
                                        <td class="px-4 py-3">
                                            <span class="
                                                px-2 py-1 rounded text-xs font-bold
                                                {{ $commande->etat === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($commande->etat === 'accepte' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}
                                            ">
                                                {{ 
                                                    $commande->etat === 'en_attente' ? 'En attente' : 
                                                    ($commande->etat === 'accepte' ? 'Acceptée' : 'Refusée') 
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $commande->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">
                                            {{ __('Aucune commande pour le moment') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
