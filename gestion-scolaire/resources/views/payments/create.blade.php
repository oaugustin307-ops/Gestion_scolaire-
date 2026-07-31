@extends('layouts.app')

@section('title', 'Nouveau Paiement')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Nouveau Paiement</h1>
    
    <form action="{{ route('payments.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Élève</label>
            <select name="student_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner un élève</option>
                @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->full_name }} - {{ $student->class->name }} (Reste: {{ number_format($student->remaining_balance, 2) }} FCFA)</option>
                @endforeach
            </select>
            @error('student_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Montant (FCFA)</label>
            <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Date de paiement</label>
            <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('payment_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Méthode de paiement</label>
            <select name="payment_method" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionner</option>
                <option value="Espèces">Espèces</option>
                <option value="Mobile Money">Mobile Money</option>
                <option value="Virement bancaire">Virement bancaire</option>
                <option value="Chèque">Chèque</option>
            </select>
            @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Remarques</label>
            <textarea name="remarks" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remarks') }}</textarea>
            @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            <a href="{{ route('payments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
@endsection
