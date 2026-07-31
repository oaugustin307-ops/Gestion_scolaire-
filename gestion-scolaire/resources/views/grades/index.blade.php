@extends('layouts.app')

@section('title', 'Gestion des Notes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gestion des Notes</h1>
    <div class="space-x-2">
        <a href="{{ route('grades.create-bulk') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">📝 Saisie en Masse</a>
        <a href="{{ route('grades.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouvelle Note</a>
    </div>
</div>

<div class="card p-6 mb-6">
    <h2 class="text-lg font-semibold mb-4">Filtrer les notes</h2>
    <form action="{{ route('grades.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Classe</label>
            <select name="class_id" id="class_id" onchange="loadStudents()" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Toutes les classes --</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Élève</label>
            <select name="student_id" id="student_id" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Tous les élèves --</option>
                @foreach($students as $student)
                <option value="{{ $student->id }}" {{ $studentId == $student->id ? 'selected' : '' }}>{{ $student->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Trimestre</label>
            <select name="trimester" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Tous les trimestres --</option>
                <option value="1" {{ $trimester == 1 ? 'selected' : '' }}>1er Trimestre</option>
                <option value="2" {{ $trimester == 2 ? 'selected' : '' }}>2ème Trimestre</option>
                <option value="3" {{ $trimester == 3 ? 'selected' : '' }}>3ème Trimestre</option>
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex-1">Filtrer</button>
            <a href="{{ route('grades.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Réinitialiser</a>
        </div>
    </form>
</div>

@if($grades->count() > 0)
    @foreach($gradesByStudent as $studentId => $studentGrades)
        @php
            $student = $studentGrades->first()->student;
        @endphp
        <div class="card p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">{{ $student->full_name }}</h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $student->class->name }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Matière</th>
                            <th class="px-4 py-2 text-left">Trimestre</th>
                            <th class="px-4 py-2 text-left">Note</th>
                            <th class="px-4 py-2 text-left">Remarques</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($studentGrades as $grade)
                        <tr>
                            <td class="px-4 py-2">{{ $grade->subject->name }}</td>
                            <td class="px-4 py-2">T{{ $grade->trimester }}</td>
                            <td class="px-4 py-2 font-semibold {{ $grade->grade >= 10 ? 'text-green-600' : 'text-red-600' }}">{{ $grade->grade }}/20</td>
                            <td class="px-4 py-2">{{ $grade->remarks ?? '-' }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('grades.edit', $grade) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                                <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@else
    <div class="card p-6 text-center">
        <p class="text-gray-500">Aucune note trouvée</p>
    </div>
@endif

<script>
function loadStudents() {
    const classId = document.getElementById('class_id').value;
    const studentSelect = document.getElementById('student_id');
    
    // Réinitialiser le select d'élèves
    studentSelect.innerHTML = '<option value="">-- Tous les élèves --</option>';
    
    if (classId) {
        fetch(`/api/students/by-class/${classId}`)
            .then(response => response.json())
            .then(students => {
                students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.full_name;
                    studentSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
}
</script>
@endsection
