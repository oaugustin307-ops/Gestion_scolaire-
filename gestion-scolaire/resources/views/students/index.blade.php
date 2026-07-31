@extends('layouts.app')

@section('title', 'Gestion des Élèves')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gestion des Élèves</h1>
    <a href="{{ route('students.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouvel Élève</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Moyenne T1</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Moyenne T2</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Moyenne T3</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Moyenne Annuelle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($students as $student)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->full_name }}" class="w-12 h-12 rounded-full object-cover">
                    @else
                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 text-sm">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                    </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $student->full_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $student->class->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center font-semibold {{ $student->trimester1_average >= 10 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $student->trimester1_average > 0 ? $student->trimester1_average : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center font-semibold {{ $student->trimester2_average >= 10 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $student->trimester2_average > 0 ? $student->trimester2_average : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center font-semibold {{ $student->trimester3_average >= 10 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $student->trimester3_average > 0 ? $student->trimester3_average : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center font-bold {{ $student->annual_average >= 10 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $student->annual_average > 0 ? $student->annual_average : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $student->parent_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                    <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                    <a href="{{ route('students.edit', $student) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
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
