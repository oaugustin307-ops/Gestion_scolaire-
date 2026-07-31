@extends('layouts.app')

@section('title', 'Détails Élève')

@section('content')
<div class="mb-6">
    <a href="{{ route('students.index') }}" class="text-blue-600 hover:text-blue-900">← Retour aux élèves</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center mb-4">
                @if($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->full_name }}" class="w-20 h-20 rounded-full object-cover mr-4">
                @else
                <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                    <span class="text-gray-600 text-2xl">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                </div>
                @endif
                <h1 class="text-2xl font-bold">{{ $student->full_name }}</h1>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Classe</p>
                    <p class="font-semibold">{{ $student->class->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Date de naissance</p>
                    <p class="font-semibold">{{ $student->date_of_birth->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Sexe</p>
                    <p class="font-semibold">{{ $student->gender == 'M' ? 'Masculin' : 'Féminin' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Date d'inscription</p>
                    <p class="font-semibold">{{ $student->registration_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Parent</p>
                    <p class="font-semibold">{{ $student->parent_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Téléphone</p>
                    <p class="font-semibold">{{ $student->parent_phone }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600">Adresse</p>
                    <p class="font-semibold">{{ $student->address ?? 'Non renseignée' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">📊 Moyennes</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">1er Trimestre</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $student->trimester1_average > 0 ? $student->trimester1_average . '/20' : '-' }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">2ème Trimestre</p>
                    <p class="text-2xl font-bold text-green-600">{{ $student->trimester2_average > 0 ? $student->trimester2_average . '/20' : '-' }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">3ème Trimestre</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $student->trimester3_average > 0 ? $student->trimester3_average . '/20' : '-' }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Moyenne Annuelle</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $student->annual_average > 0 ? $student->annual_average . '/20' : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Notes</h2>
            @if($student->grades->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Matière</th>
                                <th class="px-4 py-2 text-left">Trimestre</th>
                                <th class="px-4 py-2 text-left">Note</th>
                                <th class="px-4 py-2 text-left">Remarques</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($student->grades as $grade)
                            <tr>
                                <td class="px-4 py-2">{{ $grade->subject->name }}</td>
                                <td class="px-4 py-2">{{ $grade->trimester }}</td>
                                <td class="px-4 py-2 font-semibold">{{ $grade->grade }}/20</td>
                                <td class="px-4 py-2">{{ $grade->remarks ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Aucune note enregistrée</p>
            @endif
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Paiements</h2>
            <div class="mb-4">
                <p class="text-gray-600">Frais de scolarité</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($student->class->school_fees, 2) }} FCFA</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600">Total payé</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($student->total_payments, 2) }} FCFA</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600">Reste à payer</p>
                <p class="text-2xl font-bold {{ $student->remaining_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format($student->remaining_balance, 2) }} FCFA
                </p>
            </div>
            @if($student->payments->count() > 0)
                <div class="mt-4">
                    <h3 class="font-semibold mb-2">Historique</h3>
                    @foreach($student->payments as $payment)
                    <div class="border-b py-2">
                        <p class="text-sm">{{ $payment->payment_date->format('d/m/Y') }} - {{ number_format($payment->amount, 2) }} FCFA</p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('students.edit', $student) }}" class="block w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">Modifier</a>
                <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="block w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-center">Ajouter paiement</a>
                <form action="{{ route('students.destroy', $student) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
