<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->query('class_id');
        $studentId = $request->query('student_id');
        $trimester = $request->query('trimester');
        
        $classes = ClassRoom::all();
        $students = [];
        if ($classId) {
            $students = Student::where('class_id', $classId)->get();
        }
        
        $query = Grade::with(['student', 'subject', 'class']);
        
        if ($classId) {
            $query->where('class_id', $classId);
        }
        
        if ($studentId) {
            $query->where('student_id', $studentId);
        }
        
        if ($trimester) {
            $query->where('trimester', $trimester);
        }
        
        $grades = $query->latest()->get();
        
        // Regrouper les notes par élève
        $gradesByStudent = $grades->groupBy('student_id');
        
        return view('grades.index', compact('grades', 'gradesByStudent', 'classes', 'students', 'classId', 'studentId', 'trimester'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        $subjects = Subject::with('class')->get();
        $students = Student::with('class')->get();
        return view('grades.create', compact('classes', 'subjects', 'students'));
    }

    public function createBulk(Request $request)
    {
        $classes = ClassRoom::all();
        $selectedClassId = $request->query('class_id');
        $selectedTrimester = $request->query('trimester');
        
        $students = [];
        $subjects = [];
        
        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)->get(['id', 'first_name', 'last_name']);
            $subjects = Subject::where('class_id', $selectedClassId)->get(['id', 'name', 'code']);
        }
        
        return view('grades.create-bulk', compact('classes', 'selectedClassId', 'selectedTrimester', 'students', 'subjects'));
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'trimester' => 'required|integer|min:1|max:3',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.subject_id' => 'required|exists:subjects,id',
            'grades.*.grade' => 'nullable|numeric|min:0|max:20',
            'grades.*.remarks' => 'nullable|string',
        ]);

        $classId = $validated['class_id'];
        $trimester = $validated['trimester'];

        foreach ($validated['grades'] as $gradeData) {
            if ($gradeData['grade'] !== null && $gradeData['grade'] !== '') {
                Grade::updateOrCreate(
                    [
                        'student_id' => $gradeData['student_id'],
                        'subject_id' => $gradeData['subject_id'],
                        'class_id' => $classId,
                        'trimester' => $trimester,
                    ],
                    [
                        'grade' => $gradeData['grade'],
                        'remarks' => $gradeData['remarks'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('grades.index')->with('success', 'Notes enregistrées avec succès.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'trimester' => 'required|integer|min:1|max:3',
            'grade' => 'required|numeric|min:0|max:20',
            'remarks' => 'nullable|string',
        ]);

        Grade::create($validated);
        return redirect()->route('grades.index')->with('success', 'Note enregistrée avec succès.');
    }

    public function show(Grade $grade)
    {
        $grade->load(['student', 'subject', 'class']);
        return view('grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $students = Student::all();
        return view('grades.edit', compact('grade', 'classes', 'subjects', 'students'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'trimester' => 'required|integer|min:1|max:3',
            'grade' => 'required|numeric|min:0|max:20',
            'remarks' => 'nullable|string',
        ]);

        $grade->update($validated);
        return redirect()->route('grades.index')->with('success', 'Note mise à jour avec succès.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Note supprimée avec succès.');
    }

    public function byClass($classId, $trimester)
    {
        $class = ClassRoom::with('students')->findOrFail($classId);
        $subjects = Subject::all();
        
        $grades = Grade::where('class_id', $classId)
            ->where('trimester', $trimester)
            ->with(['student', 'subject'])
            ->get()
            ->groupBy('student_id');

        return view('grades.by-class', compact('class', 'subjects', 'grades', 'trimester'));
    }
}
