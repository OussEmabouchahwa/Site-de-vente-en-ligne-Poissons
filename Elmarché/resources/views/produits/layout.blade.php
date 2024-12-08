<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Produits - ElMarché')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('produits.index') }}" class="text-2xl font-bold">
                <i class="fas fa-shopping-basket mr-2"></i>ElMarché
            </a>
            <div>
                @auth
                    <a href="{{ route('produits.create') }}" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded mr-2">
                        <i class="fas fa-plus mr-2"></i>Nouveau Produit
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-black hover:bg-gray-800 px-4 py-2 rounded">
                        <i class="fas fa-user mr-2"></i>Mon Compte
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 px-4">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-blue-800 text-white py-6 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} ElMarché. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
