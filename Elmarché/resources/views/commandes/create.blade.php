@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-blue-600 mb-6">Commander {{ $produit->nom }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreurs de validation :</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('commandes.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="produit_id" value="{{ $produit->id }}">

            <div>
                <label class="block text-blue-600 mb-2" for="adresse_livraison">Adresse de Livraison *</label>
                <textarea name="adresse_livraison" id="adresse_livraison" 
                          class="w-full border-black rounded-md shadow-sm @error('adresse_livraison') border-red-500 @enderror" 
                          rows="3" required placeholder="Entrez votre adresse complète">{{ old('adresse_livraison') }}</textarea>
                @error('adresse_livraison')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="quantite">Quantité *</label>
                <input type="number" name="quantite" id="quantite" 
                       min="1" max="{{ $produit->qte }}" 
                       class="w-full border-black rounded-md shadow-sm @error('quantite') border-red-500 @enderror" 
                       required 
                       value="{{ old('quantite', 1) }}"
                       placeholder="Quantité disponible: {{ $produit->qte }}">
                @error('quantite')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @if($produit->qte <= 0)
                    <p class="text-red-500 text-sm mt-2">Désolé, ce produit est actuellement en rupture de stock.</p>
                @endif
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="mode_livraison">Mode de livraison *</label>
                <select name="mode_livraison" id="mode_livraison" 
                        class="w-full border-black rounded-md shadow-sm @error('mode_livraison') border-red-500 @enderror" 
                        required>
                    <option value="">Sélectionnez un mode de livraison</option>
                    <option value="domicile" {{ old('mode_livraison') == 'domicile' ? 'selected' : '' }}>Livraison à domicile</option>
                    <option value="point_relais" {{ old('mode_livraison') == 'point_relais' ? 'selected' : '' }}>Point relais</option>
                </select>
                @error('mode_livraison')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-blue-600 mb-2" for="mode_payment">Mode de paiement *</label>
                <select name="mode_payment" id="mode_payment" 
                        class="w-full border-black rounded-md shadow-sm @error('mode_payment') border-red-500 @enderror" 
                        required>
                    <option value="">Sélectionnez un mode de paiement</option>
                    <option value="carte" {{ old('mode_payment') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                    <option value="virement" {{ old('mode_payment') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                </select>
                @error('mode_payment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="rip_field" class="{{ old('mode_payment') == 'virement' ? '' : 'hidden' }}">
                <label class="block text-blue-600 mb-2" for="RIP">RIP Bancaire *</label>
                <input type="text" name="RIP" id="RIP" 
                       class="w-full border-black rounded-md shadow-sm @error('RIP') border-red-500 @enderror"
                       value="{{ old('RIP') }}"
                       placeholder="Entrez votre RIP bancaire">
                @error('RIP')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8">
                <button type="submit" 
                        class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                    Confirmer la commande
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('mode_payment').addEventListener('change', function() {
    const ripField = document.getElementById('rip_field');
    ripField.classList.toggle('hidden', this.value !== 'virement');
});
</script>
@endpush
@endsection
