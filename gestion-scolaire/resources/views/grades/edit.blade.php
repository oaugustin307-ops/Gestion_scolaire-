@extends('layouts.app')

@section('title', 'Modifier Note')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Modifier Note</h1>
    
    <form action="{{ route('grades.update', $grade) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Élève</label>
            <select name="student_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner un élève</option>
                @foreach($students as $student)
                <option value="{{ $student->id }}" {{ $student->id == $grade->student_id ? 'selected' : '' }}>{{ $student->full_name }}</option>
                @endforeach
            </select>
            @error('student_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Matière</label>
            <select name="subject_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner une matière</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $subject->id == $grade->subject_id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                @endforeach
            </select>
            @error('subject_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Classe</label>
            <select name="class_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner une classe</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $class->id == $grade->class_id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            @error('class_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Trimestre</label>
            <select name="trimester" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner</option>
                <option value="1" {{ $grade->trimester == 1 ? 'selected' : '' }}>1er Trimestre</option>
                <option value="2" {{ $grade->trimester == 2 ? 'selected' : '' }}>2ème Trimestre</option>
                <option value="3" {{ $grade->trimester == 3 ? 'selected' : '' }}>3ème Trimestre</option>
            </select>
            @error('trimester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Note (sur 20)</label>
            <input type="number" name="grade" value="{{ old('grade', $grade->grade) }}" step="0.01" min="0" max="20" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('grade') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Remarques</label>
            <textarea name="remarks" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remarks', $grade->remarks) }}</textarea>
            @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('grades.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
@endsection
