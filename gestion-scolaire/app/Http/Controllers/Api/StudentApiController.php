<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

/**
 * StudentApiController
 * 
 * Ce contrôleur gère les données des élèves via API REST.
 * Il fournit les endpoints pour le tableau de bord, les informations
 * de base et le rang des élèves pour l'application mobile.
 */
class StudentApiController extends Controller
{
    /**
     * Récupérer tous les enfants d'un parent connecté
     * 
     * GET /api/guardian/children
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChildren(Request $request)
    {
        $guardian = $request->user();
        $students = $guardian->students()->with('class')->get();
        
        $children = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'full_name' => $student->full_name,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                'class' => [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                    'level' => $student->class->level,
                ],
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'registration_date' => $student->registration_date,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $children,
        ], 200);
    }

    /**
     * Récupérer le tableau de bord d'un élève spécifique
     * 
     * GET /api/guardian/children/{id}/dashboard
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboard(Request $request, $id)
    {
        $guardian = $request->user();
        $student = $guardian->students()->with('class', 'grades')->find($id);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        // Récupérer les 5 dernières notes
        $recentGrades = $student->grades()->with('subject')->latest()->take(5)->get();
        
        // Calculer le rang dans la classe
        $classStudents = Student::where('class_id', $student->class_id)->get();
        $rank = $classStudents->sortByDesc('annual_average')->search(function ($item) use ($student) {
            return $item->id === $student->id;
        }) + 1;

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'full_name' => $student->full_name,
                    'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                    'class' => [
                        'id' => $student->class->id,
                        'name' => $student->class->name,
                        'level' => $student->class->level,
                    ],
                ],
                'averages' => [
                    'trimester1' => $student->trimester1_average,
                    'trimester2' => $student->trimester2_average,
                    'trimester3' => $student->trimester3_average,
                    'annual' => $student->annual_average,
                ],
                'rank' => [
                    'position' => $rank,
                    'total' => $classStudents->count(),
                ],
                'recent_grades' => $recentGrades->map(function ($grade) {
                    return [
                        'subject' => $grade->subject->name,
                        'grade' => $grade->grade,
                        'trimester' => $grade->trimester,
                        'date' => $grade->created_at->format('d/m/Y'),
                    ];
                }),
            ],
        ], 200);
    }

    /**
     * Récupérer les informations détaillées d'un élève
     * 
     * GET /api/guardian/children/{id}
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $guardian = $request->user();
        $student = $guardian->students()->with('class')->find($id);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'full_name' => $student->full_name,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'address' => $student->address,
                'registration_date' => $student->registration_date,
                'class' => [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                    'level' => $student->class->level,
                    'school_fees' => $student->class->school_fees,
                ],
            ],
        ], 200);
    }
}
