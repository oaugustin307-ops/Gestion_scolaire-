@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="mb-6 flex space-x-4 flex-wrap">
    @if(auth()->user()->role === 'gestionnaire')
    <a href="{{ route('guardians.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 inline-flex items-center">
        👨‍👩‍👧‍👦 Gestion des Parents
    </a>
    @endif
    <a href="{{ route('notifications.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 inline-flex items-center">
        🔔 Gestion des Notifications
    </a>
    <a href="{{ route('attendances.index') }}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 inline-flex items-center">
        📅 Gestion des Présences
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card rounded-lg shadow-lg p-6">
        <h3 class="text-sm font-semibold opacity-90">Total Élèves</h3>
        <p class="text-4xl font-bold mt-2">{{ $totalStudents }}</p>
        <p class="text-sm opacity-75 mt-1">👨‍🎓 Inscrits</p>
    </div>
    <div class="stat-card-2 rounded-lg shadow-lg p-6">
        <h3 class="text-sm font-semibold opacity-90">Total Classes</h3>
        <p class="text-4xl font-bold mt-2">{{ $totalClasses }}</p>
        <p class="text-sm opacity-75 mt-1">🏫 Actives</p>
    </div>
    <div class="stat-card-3 rounded-lg shadow-lg p-6">
        <h3 class="text-sm font-semibold opacity-90">Frais Collectés</h3>
        <p class="text-4xl font-bold mt-2">{{ number_format($totalCollected, 2) }} FCFA</p>
        <p class="text-sm opacity-75 mt-1">💰 Encaissés</p>
    </div>
    <div class="stat-card-4 rounded-lg shadow-lg p-6">
        <h3 class="text-sm font-semibold opacity-90">Solde Restant</h3>
        <p class="text-4xl font-bold mt-2">{{ number_format($remainingBalance, 2) }} FCFA</p>
        <p class="text-sm opacity-75 mt-1">⚠️ À percevoir</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <span class="mr-2">⚠️</span> Élèves avec retard de paiement
        </h2>
        @if($overdueStudents->count() > 0)
            <div class="table-container">
                <table class="min-w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Nom</th>
                            <th class="px-4 py-3 text-left font-semibold">Classe</th>
                            <th class="px-4 py-3 text-left font-semibold">Reste à payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueStudents as $student)
                        <tr class="table-row border-b">
                            <td class="px-4 py-3">{{ $student->full_name }}</td>
                            <td class="px-4 py-3">{{ $student->class->name }}</td>
                            <td class="px-4 py-3 text-red-600 font-semibold">{{ number_format($student->remaining_balance, 2) }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">✅ Aucun élève avec retard de paiement</p>
        @endif
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <span class="mr-2">💳</span> Paiements récents
        </h2>
        @if($recentPayments->count() > 0)
            <div class="table-container">
                <table class="min-w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Élève</th>
                            <th class="px-4 py-3 text-left font-semibold">Montant</th>
                            <th class="px-4 py-3 text-left font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $payment)
                        <tr class="table-row border-b">
                            <td class="px-4 py-3">{{ $payment->student->full_name }}</td>
                            <td class="px-4 py-3 text-green-600 font-semibold">{{ number_format($payment->amount, 2) }} FCFA</td>
                            <td class="px-4 py-3">{{ $payment->payment_date->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">📭 Aucun paiement récent</p>
        @endif
    </div>
</div>
@endsection
