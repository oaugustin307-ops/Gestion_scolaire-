@extends('layouts.app')

@section('title', 'Détails Note')

@section('content')
<div class="mb-6">
    <a href="{{ route('grades.index') }}" class="text-blue-600 hover:text-blue-900">← Retour aux notes</a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Détails de la Note</h1>
    
    <div class="grid grid-cols-2 gap-6">
        <div>
            <p class="text-gray-600">Élève</p>
            <p class="font-semibold text-lg">{{ $grade->student->full_name }}</p>
        </div>
        <div>
            <p class="text-gray-600">Classe</p>
            <p class="font-semibold text-lg">{{ $grade->class->name }}</p>
        </div>
        <div>
            <p class="text-gray-600">Matière</p>
            <p class="font-semibold text-lg">{{ $grade->subject->name }}</p>
        </div>
        <div>
            <p class="text-gray-600">Trimestre</p>
            <p class="font-semibold text-lg">T{{ $grade->trimester }}</p>
        </div>
        <div>
            <p class="text-gray-600">Note</p>
            <p class="font-semibold text-2xl text-blue-600">{{ $grade->grade }}/20</p>
        </div>
        <div>
            <p class="text-gray-600">Remarques</p>
            <p class="font-semibold">{{ $grade->remarks ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-6 flex space-x-4">
        <a href="{{ route('grades.edit', $grade) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Modifier</a>
        <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
        </form>
    </div>
</div>
@endsection
