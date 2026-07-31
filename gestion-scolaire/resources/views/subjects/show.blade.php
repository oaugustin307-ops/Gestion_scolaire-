@extends('layouts.app')

@section('title', 'Détails Matière')

@section('content')
<div class="mb-6">
    <a href="{{ route('subjects.index') }}" class="text-blue-600 hover:text-blue-900">← Retour aux matières</a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h1 class="text-2xl font-bold mb-4">{{ $subject->name }}</h1>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600">Code</p>
            <p class="font-semibold">{{ $subject->code }}</p>
        </div>
        <div>
            <p class="text-gray-600">Coefficient</p>
            <p class="font-semibold">{{ $subject->coefficient }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Notes enregistrées</h2>
    @if($subject->grades->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Élève</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trimestre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($subject->grades as $grade)
                    <tr>
                        <td class="px-6 py-4">{{ $grade->student->full_name }}</td>
                        <td class="px-6 py-4">{{ $grade->class->name }}</td>
                        <td class="px-6 py-4">T{{ $grade->trimester }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $grade->grade }}/20</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">Aucune note enregistrée pour cette matière</p>
    @endif
</div>
@endsection
