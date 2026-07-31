<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .login-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4">
    <div class="flex flex-col md:flex-row w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden">
        <!-- Left side - Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 to-gray-800/90"></div>
            <div class="relative z-10 flex flex-col justify-center items-center p-12 text-white">
                <div class="text-6xl mb-4">🎓</div>
                <h2 class="text-3xl font-bold mb-4">Ecole Primaire de Nagrin</h2>
                <p class="text-center text-lg opacity-90">Système de gestion complète pour établissements scolaires</p>
                <div class="mt-8 flex space-x-4">
                    <div class="text-center">
                        <div class="text-3xl mb-2">📚</div>
                        <p class="text-sm">Élèves</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-2">👨‍🏫</div>
                        <p class="text-sm">Enseignants</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-2">💰</div>
                        <p class="text-sm">Paiements</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Login Form -->
        <div class="w-full md:w-1/2 glass-effect p-8 md:p-12">
            <div class="mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-gray-800 to-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-3xl">🏫</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Bienvenue</h1>
                <p class="text-center text-gray-600">Connectez-vous à votre espace</p>
            </div>
            
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
            @endif
            
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
            
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">📧</span>
                        <input type="email" name="email" required class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-gray-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Mot de passe</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password" required class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-gray-500 transition-all">
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-gray-800 to-gray-600 text-white py-3 rounded-lg font-semibold hover:from-gray-900 hover:to-gray-700 transition-all transform hover:scale-105 shadow-lg">
                    Se connecter
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Pas encore de compte? <a href="{{ route('register') }}" class="text-gray-800 hover:text-gray-900 font-semibold hover:underline">S'inscrire</a>
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-gray-500 text-sm mb-4">Espace Parents</p>
                <a href="{{ route('guardian.login') }}" class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                    👨‍👩‍👧‍👦 Connexion Parent
                </a>
            </div>
        </div>
    </div>
</body>
</html>
