<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

/**
 * GradeApiController
 * 
 * Ce contrôleur gère les notes et moyennes via API REST.
 * Il fournit les endpoints pour la consultation des notes par matière
 * et le calcul des moyennes trimestrielles pour l'application mobile.
 */
class GradeApiController extends Controller
{
    /**
     * Récupérer toutes les matières d'un élève
     * 
     * GET /api/guardian/children/{id}/subjects
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubjects(Request $request, $studentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $subjects = Subject::where('class_id', $student->class_id)->get();
        
        $subjectsData = $subjects->map(function ($subject) use ($student) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'coefficient' => $subject->coefficient,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $subjectsData,
        ], 200);
    }

    /**
     * Récupérer les notes d'un élève par matière
     * 
     * GET /api/guardian/children/{id}/grades/{subjectId}
     * 
     * @param Request $request
     * @param int $studentId
     * @param int $subjectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGradesBySubject(Request $request, $studentId, $subjectId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $grades = Grade::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->with('subject')
            ->orderBy('trimester')
            ->get();
        
        $gradesData = $grades->map(function ($grade) {
            return [
                'id' => $grade->id,
                'grade' => $grade->grade,
                'trimester' => $grade->trimester,
                'remarks' => $grade->remarks,
                'subject' => [
                    'name' => $grade->subject->name,
                    'coefficient' => $grade->subject->coefficient,
                ],
                'date' => $grade->created_at->format('d/m/Y'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $gradesData,
        ], 200);
    }

    /**
     * Récupérer toutes les notes d'un élève
     * 
     * GET /api/guardian/children/{id}/grades
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllGrades(Request $request, $studentId)
    {
        $guardian = $request->user();
        $student = $guardian->students()->with('class')->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        $grades = Grade::where('student_id', $studentId)
            ->with('subject')
            ->orderBy('trimester')
            ->orderBy('subject_id')
            ->get();
        
        // Grouper par trimestre
        $gradesByTrimester = [];
        for ($i = 1; $i <= 3; $i++) {
            $trimesterGrades = $grades->where('trimester', $i);
            
            $gradesByTrimester["trimester$i"] = [
                'average' => $student->{"trimester{$i}_average"},
                'grades' => $trimesterGrades->map(function ($grade) {
                    return [
                        'subject' => $grade->subject->name,
                        'coefficient' => $grade->subject->coefficient,
                        'grade' => $grade->grade,
                        'remarks' => $grade->remarks,
                    ];
                })->values(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                    'class' => $student->class->name,
                ],
                'annual_average' => $student->annual_average,
                'trimesters' => $gradesByTrimester,
            ],
        ], 200);
    }

    /**
     * Récupérer les notes d'un élève par trimestre
     * 
     * GET /api/guardian/children/{id}/grades/trimester/{trimester}
     * 
     * @param Request $request
     * @param int $studentId
     * @param int $trimester
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGradesByTrimester(Request $request, $studentId, $trimester)
    {
        $guardian = $request->user();
        $student = $guardian->students()->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        if ($trimester < 1 || $trimester > 3) {
            return response()->json([
                'success' => false,
                'message' => 'Trimestre invalide (doit être 1, 2 ou 3)',
            ], 400);
        }

        $grades = Grade::where('student_id', $studentId)
            ->where('trimester', $trimester)
            ->with('subject')
            ->get();
        
        $gradesData = $grades->map(function ($grade) {
            return [
                'subject' => $grade->subject->name,
                'coefficient' => $grade->subject->coefficient,
                'grade' => $grade->grade,
                'remarks' => $grade->remarks,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'trimester' => $trimester,
                'average' => $student->{"trimester{$trimester}_average"},
                'grades' => $gradesData,
            ],
        ], 200);
    }
}
