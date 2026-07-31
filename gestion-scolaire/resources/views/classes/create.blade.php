@extends('layouts.app')

@section('title', 'Nouvelle Classe')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Nouvelle Classe</h1>
    
    <form action="{{ route('classes.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nom de la classe</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Niveau</label>
            <input type="text" name="level" value="{{ old('level') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Frais de scolarité (FCFA)</label>
            <input type="number" name="school_fees" value="{{ old('school_fees') }}" step="0.01" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('school_fees') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
            <a href="{{ route('classes.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
@endsection
