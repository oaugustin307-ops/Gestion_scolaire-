<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer une Présence - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Enregistrer une Présence</h1>

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('attendances.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <div class="mb-4">
                    <label for="student_id" class="block text-gray-700 font-bold mb-2">Élève</label>
                    <select name="student_id" id="student_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un élève</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }} - {{ $student->class->name }}</option>
                        @endforeach
                    </select>
                    @error('student_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-gray-700 font-bold mb-2">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-bold mb-2">Statut</label>
                    <select name="status" id="status" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un statut</option>
                        <option value="present">Présent</option>
                        <option value="absent">Absent</option>
                        <option value="late">Retard</option>
                        <option value="excused">Excusé</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="reason" class="block text-gray-700 font-bold mb-2">Raison (optionnel)</label>
                    <input type="text" name="reason" id="reason" value="{{ old('reason') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="remarks" class="block text-gray-700 font-bold mb-2">Remarques (optionnel)</label>
                    <textarea name="remarks" id="remarks" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remarks') }}</textarea>
                    @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('attendances.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
