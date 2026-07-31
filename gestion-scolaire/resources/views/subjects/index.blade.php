@extends('layouts.app')

@section('title', 'Gestion des Matières')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gestion des Matières</h1>
    @if($classId)
    <a href="{{ route('subjects.create') }}?class_id={{ $classId }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouvelle Matière</a>
    @else
    <a href="{{ route('subjects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouvelle Matière</a>
    @endif
</div>

<div class="card p-6 mb-6">
    <div class="flex items-center space-x-4">
        <label class="text-gray-700 font-bold">Sélectionner une classe :</label>
        <select id="class_filter" onchange="filterByClass()" class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Choisir une classe --</option>
            @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
</div>

@if($classId)
<div class="table-container">
    <table class="min-w-full">
        <thead class="table-header">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coefficient</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @if($subjects->count() > 0)
                @foreach($subjects as $subject)
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $subject->code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subject->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subject->coefficient }}</td>
                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                        <a href="{{ route('subjects.edit', $subject) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                        <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucune matière pour cette classe</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@else
<div class="card p-8 text-center">
    <p class="text-gray-500 text-lg">📚 Veuillez sélectionner une classe pour voir ses matières</p>
</div>
@endif

<script>
function filterByClass() {
    const classId = document.getElementById('class_filter').value;
    if (classId) {
        window.location.href = '{{ route('subjects.index') }}?class_id=' + classId;
    } else {
        window.location.href = '{{ route('subjects.index') }}';
    }
}
</script>
@endsection
