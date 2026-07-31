@extends('layouts.app')

@section('title', 'Modifier Élève')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Modifier Élève</h1>
    
    <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Classe</label>
            <select name="class_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner une classe</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $class->id == $student->class_id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            @error('class_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Prénom</label>
                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Date de naissance</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Sexe</label>
            <select name="gender" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner</option>
                <option value="M" {{ $student->gender == 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ $student->gender == 'F' ? 'selected' : '' }}>Féminin</option>
            </select>
            @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Parent / Tuteur</label>
            <select name="parent_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner un parent existant</option>
                @foreach($guardians as $guardian)
                <option value="{{ $guardian->id }}" {{ $guardian->id == $student->parent_id ? 'selected' : '' }}>{{ $guardian->first_name }} {{ $guardian->last_name }} ({{ $guardian->email }})</option>
                @endforeach
            </select>
            @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <p class="text-xs text-gray-500 mt-1">Si le parent n'existe pas, <a href="{{ route('guardians.create') }}" class="text-blue-600 hover:underline">créez-le d'abord</a></p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Adresse</label>
            <input type="text" name="address" value="{{ old('address', $student->address) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Photo</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($student->photo)
                <p class="text-sm text-gray-600 mt-1">Photo actuelle: {{ $student->photo }}</p>
            @endif
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Date d'inscription</label>
            <input type="date" name="registration_date" value="{{ old('registration_date', $student->registration_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('registration_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('students.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
@endsection
