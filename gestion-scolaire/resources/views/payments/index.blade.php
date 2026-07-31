@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gestion des Paiements</h1>
    <a href="{{ route('payments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouveau Paiement</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reçu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($payments as $payment)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $payment->receipt_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $payment->student->full_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600">{{ number_format($payment->amount, 2) }} FCFA</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_method }}</td>
                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                    <a href="{{ route('payments.receipt', $payment) }}" class="text-blue-600 hover:text-blue-900">Reçu</a>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline">
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
