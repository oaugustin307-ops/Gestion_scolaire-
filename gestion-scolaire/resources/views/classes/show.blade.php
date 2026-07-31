@extends('layouts.app')

@section('title', 'Détails Classe')

@section('content')
<div class="mb-6">
    <a href="{{ route('classes.index') }}" class="text-blue-600 hover:text-blue-900">← Retour aux classes</a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h1 class="text-2xl font-bold mb-4">{{ $class->name }}</h1>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600">Niveau</p>
            <p class="font-semibold">{{ $class->level }}</p>
        </div>
        <div>
            <p class="text-gray-600">Frais de scolarité</p>
            <p class="font-semibold">{{ number_format($class->school_fees, 2) }} FCFA</p>
        </div>
        <div>
            <p class="text-gray-600">Nombre d'élèves</p>
            <p class="font-semibold">{{ $class->students->count() }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Élèves de la classe</h2>
    @if($class->students->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date de naissance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($class->students as $student)
                    <tr>
                        <td class="px-6 py-4">{{ $student->full_name }}</td>
                        <td class="px-6 py-4">{{ $student->date_of_birth->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $student->parent_name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">Aucun élève dans cette classe</p>
    @endif
</div>
@endsection
