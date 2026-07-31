<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

/**
 * AttendanceApiController
 * 
 * Ce contrôleur gère le suivi des absences via API REST.
 * Il fournit les endpoints pour la liste des absences et les motifs.
 */
class AttendanceApiController extends Controller
{
    /**
     * Récupérer les absences d'un élève
     * 
     * GET /api/guardian/children/{id}/attendances
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendances(Request $request, $studentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $attendances = Attendance::where('student_id', $studentId)
            ->where('status', '!=', 'present')
            ->latest('date')
            ->get();
        
        $attendancesData = $attendances->map(function ($attendance) {
            return [
                'id' => $attendance->id,
                'date' => $attendance->date->format('d/m/Y'),
                'status' => $attendance->status,
                'reason' => $attendance->reason,
                'remarks' => $attendance->remarks,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $attendancesData,
        ], 200);
    }

    /**
     * Récupérer le résumé des absences d'un élève
     * 
     * GET /api/guardian/children/{id}/attendances/summary
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceSummary(Request $request, $studentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $attendances = Attendance::where('student_id', $studentId)->get();
        
        $summary = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                ],
                'summary' => $summary,
            ],
        ], 200);
    }
}
