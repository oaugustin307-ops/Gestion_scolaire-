<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Parent - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Ajouter un Parent</h1>

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('guardians.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <div class="mb-4">
                    <label for="first_name" class="block text-gray-700 font-bold mb-2">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="last_name" class="block text-gray-700 font-bold mb-2">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Téléphone</label>
                    <input type="text" name="phone" id="phone" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Mot de passe</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('guardians.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
