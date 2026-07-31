<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Notification - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Détails de la Notification</h1>
                <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-900">← Retour</a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $notification->title }}</h2>
                    <div class="flex space-x-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold 
                            @if($notification->type == 'meeting') bg-blue-100 text-blue-800
                            @elseif($notification->type == 'exam') bg-purple-100 text-purple-800
                            @elseif($notification->type == 'payment') bg-green-100 text-green-800
                            @elseif($notification->type == 'urgent') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($notification->type) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold 
                            @if($notification->priority == 'high') bg-red-100 text-red-800
                            @elseif($notification->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($notification->priority) }}
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700 text-lg leading-relaxed">{{ $notification->message }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <span class="text-gray-600">Date:</span>
                        <p class="font-bold">{{ $notification->date ? $notification->date->format('d/m/Y') : 'Non définie' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Statut:</span>
                        <p class="font-bold">
                            @if($notification->is_read)
                                <span class="text-green-600">Lue</span>
                            @else
                                <span class="text-red-600">Non lue</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex space-x-4 border-t pt-6">
                    <a href="{{ route('notifications.edit', $notification->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Modifier</a>
                    @if(!$notification->is_read)
                    <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Marquer comme lue</a>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
