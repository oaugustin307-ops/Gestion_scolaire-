<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Présence - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Détails de la Présence</h1>
                <a href="{{ route('attendances.index') }}" class="text-blue-600 hover:text-blue-900">← Retour</a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <span class="text-gray-600">Élève:</span>
                        <p class="font-bold">{{ $attendance->student->full_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Classe:</span>
                        <p class="font-bold">{{ $attendance->student->class->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Date:</span>
                        <p class="font-bold">{{ $attendance->date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Statut:</span>
                        <p class="font-bold">
                            <span class="px-2 py-1 rounded-full text-sm font-semibold 
                                @if($attendance->status == 'present') bg-green-100 text-green-800
                                @elseif($attendance->status == 'absent') bg-red-100 text-red-800
                                @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600">Raison:</span>
                        <p class="font-bold">{{ $attendance->reason ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Remarques:</span>
                        <p class="font-bold">{{ $attendance->remarks ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex space-x-4 border-t pt-6">
                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Modifier</a>
                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette présence ?');">
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
