<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Notification - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Ajouter une Notification</h1>

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('notifications.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-bold mb-2">Titre</label>
                    <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-gray-700 font-bold mb-2">Message</label>
                    <textarea name="message" id="message" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 font-bold mb-2">Type</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner un type</option>
                        <option value="meeting">Réunion</option>
                        <option value="exam">Examen</option>
                        <option value="payment">Paiement</option>
                        <option value="general">Général</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="priority" class="block text-gray-700 font-bold mb-2">Priorité</label>
                    <select name="priority" id="priority" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner une priorité</option>
                        <option value="low">Faible</option>
                        <option value="medium">Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-gray-700 font-bold mb-2">Date (optionnel)</label>
                    <input type="date" name="date" id="date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('notifications.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
