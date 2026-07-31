@extends('layouts.app')

@section('title', 'Gestion des Classes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gestion des Classes</h1>
    <a href="{{ route('classes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouvelle Classe</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frais de scolarité</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élèves</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($classes as $class)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $class->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $class->level }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($class->school_fees, 2) }} FCFA</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $class->students->count() }}</td>
                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                    <a href="{{ route('classes.show', $class) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                    <a href="{{ route('classes.edit', $class) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                    <form action="{{ route('classes.destroy', $class) }}" method="POST" class="inline">
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
@endsection
