@extends('layouts.app')

@section('title', 'Saisie des Notes en Masse')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">📝 Saisie des Notes en Masse</h1>
    
    <div class="card p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Sélectionnez la classe et le trimestre</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Classe</label>
                <select id="class_id" name="class_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Trimestre</label>
                <select id="trimester" name="trimester" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Choisir le trimestre --</option>
                    <option value="1" {{ $selectedTrimester == 1 ? 'selected' : '' }}>1er Trimestre</option>
                    <option value="2" {{ $selectedTrimester == 2 ? 'selected' : '' }}>2ème Trimestre</option>
                    <option value="3" {{ $selectedTrimester == 3 ? 'selected' : '' }}>3ème Trimestre</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" id="load-btn" onclick="loadGrades()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">📊 Charger les données</button>
            </div>
        </div>
        <p class="text-gray-500 text-sm mt-2">💡 Sélectionnez une classe et un trimestre pour voir tous les élèves et leurs matières</p>
    </div>

    @if($selectedClassId && $selectedTrimester)
    <div class="card p-6 mb-4">
        <h2 class="text-lg font-semibold mb-2">📋 Saisie des notes pour {{ $classes->find($selectedClassId)->name ?? '' }} - {{ $selectedTrimester == 1 ? '1er Trimestre' : ($selectedTrimester == 2 ? '2ème Trimestre' : '3ème Trimestre') }}</h2>
        <p class="text-gray-500 text-sm">Remplissez les notes pour chaque élève et chaque matière. Laissez vide pour ne pas enregistrer.</p>
    </div>
    
    <form action="{{ route('grades.store-bulk') }}" method="POST" class="card p-6">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="trimester" value="{{ $selectedTrimester }}">
        
        @if($students->count() == 0)
        <p class="text-center text-gray-500 py-8">📭 Aucun élève dans cette classe</p>
        @elseif($subjects->count() == 0)
        <p class="text-center text-gray-500 py-8">📭 Aucune matière pour cette classe</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="table-header">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold bg-gray-100">Élève</th>
                        @foreach($subjects as $subject)
                        <th class="px-4 py-3 text-center font-semibold bg-gray-100">{{ $subject->name }}<br><span class="text-xs text-gray-500">{{ $subject->code }}</span></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $studentIndex => $student)
                    <tr class="table-row">
                        <td class="px-4 py-3 font-medium bg-gray-50">{{ $student->first_name }} {{ $student->last_name }}</td>
                        @foreach($subjects as $subject)
                        <td class="px-4 py-3 text-center">
                            <input type="hidden" 
                                   name="grades[{{ $studentIndex * $subjects->count() + $loop->index }}][student_id]" 
                                   value="{{ $student->id }}">
                            <input type="hidden" 
                                   name="grades[{{ $studentIndex * $subjects->count() + $loop->index }}][subject_id]" 
                                   value="{{ $subject->id }}">
                            <input type="number" 
                                   name="grades[{{ $studentIndex * $subjects->count() + $loop->index }}][grade]" 
                                   step="0.01" 
                                   min="0" 
                                   max="20" 
                                   placeholder="-"
                                   class="w-20 px-2 py-1 border rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <p class="text-blue-800 text-sm">💡 <strong>Instructions :</strong> Remplissez les notes sur 20 pour chaque élève et chaque matière. Laissez vide pour ne pas enregistrer la note. Les notes existantes seront mises à jour.</p>
        </div>
        @endif
        
        <div class="flex space-x-4 mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">💾 Enregistrer toutes les notes</button>
            <a href="{{ route('grades.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">❌ Annuler</a>
        </div>
    </form>
    @endif
</div>

<script>
function loadGrades() {
    const classId = document.getElementById('class_id').value;
    const trimester = document.getElementById('trimester').value;
    
    if (!classId || !trimester) {
        alert('⚠️ Veuillez sélectionner une classe et un trimestre');
        return;
    }
    
    // Recharger la page avec les paramètres
    window.location.href = '{{ route('grades.create-bulk') }}?class_id=' + classId + '&trimester=' + trimester;
}
</script>
@endsection
