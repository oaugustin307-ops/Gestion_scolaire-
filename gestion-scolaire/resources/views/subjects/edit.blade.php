@extends('layouts.app')

@section('title', 'Modifier Matière')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Modifier Matière</h1>
    
    <form action="{{ route('subjects.update', $subject) }}" method="POST" class="card p-6">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Classe</label>
            <select name="class_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner une classe</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $subject->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            @error('class_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nom de la matière</label>
            <input type="text" name="name" value="{{ old('name', $subject->name) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Code</label>
            <input type="text" name="code" value="{{ old('code', $subject->code) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Coefficient</label>
            <input type="number" name="coefficient" value="{{ old('coefficient', $subject->coefficient) }}" step="0.1" min="0.1" max="10" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('coefficient') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('subjects.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
@endsection
