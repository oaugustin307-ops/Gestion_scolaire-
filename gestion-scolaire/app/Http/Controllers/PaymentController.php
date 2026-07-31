<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('student')->latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::with('class')->get();
        return view('payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $validated['receipt_number'] = 'REC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        Payment::create($validated);
        return redirect()->route('payments.index')->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Payment $payment)
    {
        $payment->load('student.class');
        return view('payments.show', compact('payment'));
    }

    public function receipt(Payment $payment)
    {
        $payment->load('student.class');
        return view('payments.receipt', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Paiement supprimé avec succès.');
    }
}
