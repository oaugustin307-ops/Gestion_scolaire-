<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notifications - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Notifications</h1>
            <a href="{{ route('notifications.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Ajouter une Notification
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($notifications as $notification)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $notification->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $notification->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($notification->priority == 'high') bg-red-100 text-red-800
                                @elseif($notification->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $notification->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $notification->date ? $notification->date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($notification->is_read) bg-gray-100 text-gray-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $notification->is_read ? 'Lue' : 'Non lue' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('notifications.show', $notification->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                            <a href="{{ route('notifications.edit', $notification->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                            @if(!$notification->is_read)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline mr-3">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">Marquer lue</button>
                            </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucune notification enregistrée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-900">← Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
