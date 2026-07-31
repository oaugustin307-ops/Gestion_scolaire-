<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Parent - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Détails du Parent</h1>
                <a href="{{ route('guardians.index') }}" class="text-blue-600 hover:text-blue-900">← Retour</a>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informations Personnelles</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600">Prénom:</span>
                        <p class="font-bold">{{ $guardian->first_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Nom:</span>
                        <p class="font-bold">{{ $guardian->last_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <p class="font-bold">{{ $guardian->email }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Téléphone:</span>
                        <p class="font-bold">{{ $guardian->phone }}</p>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('guardians.edit', $guardian->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Modifier</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Enfants ({{ $guardian->students->count() }})</h2>
                
                @if($guardian->students->isEmpty())
                <p class="text-gray-500">Aucun enfant associé à ce parent.</p>
                @else
                <div class="space-y-4">
                    @foreach($guardian->students as $student)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-lg">{{ $student->full_name }}</h3>
                                <p class="text-gray-600">Classe: {{ $student->class->name }}</p>
                            </div>
                            <a href="{{ route('students.show', $student->id) }}" class="text-blue-600 hover:text-blue-900">Voir détails</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
