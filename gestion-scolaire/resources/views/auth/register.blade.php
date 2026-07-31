<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Inscription</h1>
        
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
        
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
                <select name="role" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="gestionnaire">Gestionnaire</option>
                    <option value="enseignant">Enseignant</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">S'inscrire</button>
        </form>
        
        <p class="text-center mt-4">
            Déjà un compte? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
        </p>
    </div>
</body>
</html>
