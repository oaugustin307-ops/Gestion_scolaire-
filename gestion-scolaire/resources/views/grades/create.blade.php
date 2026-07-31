@extends('layouts.app')

@section('title', 'Nouvelle Note')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Nouvelle Note</h1>
    
    <form action="{{ route('grades.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Classe</label>
            <select name="class_id" id="class_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner une classe</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
            @error('class_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Élève</label>
            <select name="student_id" id="student_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner d'abord une classe</option>
            </select>
            @error('student_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Hidden data for JavaScript -->
        <script>
            const studentsData = @json($students);
            const subjectsData = @json($subjects);
        </script>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Matière</label>
            <select name="subject_id" id="subject_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner d'abord une classe</option>
            </select>
            @error('subject_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Trimestre</label>
            <select name="trimester" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner</option>
                <option value="1">1er Trimestre</option>
                <option value="2">2ème Trimestre</option>
                <option value="3">3ème Trimestre</option>
            </select>
            @error('trimester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Note (sur 20)</label>
            <input type="number" name="grade" value="{{ old('grade') }}" step="0.01" min="0" max="20" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('grade') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Remarques</label>
            <textarea name="remarks" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remarks') }}</textarea>
            @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            <a href="{{ route('grades.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>

<script>
document.getElementById('class_id').addEventListener('change', function() {
    const classId = this.value;
    const studentSelect = document.getElementById('student_id');
    const subjectSelect = document.getElementById('subject_id');
    
    if (!classId) {
        studentSelect.innerHTML = '<option value="">Sélectionner d\'abord une classe</option>';
        subjectSelect.innerHTML = '<option value="">Sélectionner d\'abord une classe</option>';
        return;
    }
    
    // Filtrer les élèves par classe
    const filteredStudents = studentsData.filter(student => student.class_id == classId);
    
    studentSelect.innerHTML = '<option value="">Sélectionner un élève</option>';
    
    if (filteredStudents.length === 0) {
        studentSelect.innerHTML += '<option value="" disabled>Aucun élève dans cette classe</option>';
    } else {
        filteredStudents.forEach(student => {
            studentSelect.innerHTML += `<option value="${student.id}">${student.first_name} ${student.last_name}</option>`;
        });
    }
    
    // Filtrer les matières par classe
    const filteredSubjects = subjectsData.filter(subject => subject.class_id == classId);
    
    subjectSelect.innerHTML = '<option value="">Sélectionner une matière</option>';
    
    if (filteredSubjects.length === 0) {
        subjectSelect.innerHTML += '<option value="" disabled>Aucune matière pour cette classe</option>';
    } else {
        filteredSubjects.forEach(subject => {
            subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name} (${subject.code})</option>`;
        });
    }
});
</script>
@endsection
