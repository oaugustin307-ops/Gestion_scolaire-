<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * PaymentApiController
 * 
 * Ce contrôleur gère la consultation des paiements via API REST.
 * Il fournit uniquement les endpoints de lecture pour l'historique des versements,
 * le montant total payé, le montant restant et les reçus.
 * 
 * IMPORTANT: Les parents ne peuvent PAS créer/modifier des paiements depuis l'application mobile.
 * Seul le personnel de l'école peut enregistrer les paiements via le système web.
 */
class PaymentApiController extends Controller
{
    /**
     * Récupérer l'historique des paiements d'un élève
     * 
     * GET /api/guardian/children/{id}/payments
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPayments(Request $request, $studentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->with('class')->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $payments = Payment::where('student_id', $studentId)
            ->latest()
            ->get();
        
        $paymentsData = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date->format('d/m/Y'),
                'payment_method' => $payment->payment_method,
                'receipt_number' => $payment->receipt_number,
                'remarks' => $payment->remarks,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $paymentsData,
        ], 200);
    }

    /**
     * Récupérer le résumé des paiements d'un élève
     * 
     * GET /api/guardian/children/{id}/payments/summary
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentSummary(Request $request, $studentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->with('class')->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $totalPaid = $student->total_payments;
        $schoolFees = $student->class->school_fees;
        $remainingBalance = $student->remaining_balance;

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                    'class' => $student->class->name,
                ],
                'school_fees' => $schoolFees,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance,
                'payment_percentage' => $schoolFees > 0 ? round(($totalPaid / $schoolFees) * 100, 2) : 0,
            ],
        ], 200);
    }

    /**
     * Récupérer les détails d'un paiement spécifique
     * 
     * GET /api/guardian/children/{id}/payments/{paymentId}
     * 
     * @param Request $request
     * @param int $studentId
     * @param int $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentDetails(Request $request, $studentId, $paymentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $payment = Payment::where('id', $paymentId)
            ->where('student_id', $studentId)
            ->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date->format('d/m/Y'),
                'payment_method' => $payment->payment_method,
                'receipt_number' => $payment->receipt_number,
                'remarks' => $payment->remarks,
                'student' => [
                    'full_name' => $student->full_name,
                ],
            ],
        ], 200);
    }

    /**
     * Récupérer l'URL du reçu d'un paiement
     * 
     * GET /api/guardian/children/{id}/payments/{paymentId}/receipt
     * 
     * @param Request $request
     * @param int $studentId
     * @param int $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceiptUrl(Request $request, $studentId, $paymentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $payment = Payment::where('id', $paymentId)
            ->where('student_id', $studentId)
            ->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'receipt_url' => route('payments.receipt', $payment->id),
                'receipt_number' => $payment->receipt_number,
            ],
        ], 200);
    }
}
