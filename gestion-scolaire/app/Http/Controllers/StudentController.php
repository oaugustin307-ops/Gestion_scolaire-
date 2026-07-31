<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('class')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        $guardians = Guardian::all();
        return view('students.create', compact('classes', 'guardians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:M,F',
            'parent_id' => 'required|exists:parents,id',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'registration_date' => 'required|date',
        ]);

        // Récupérer les informations du parent pour remplir les champs parent_name et parent_phone
        $guardian = Guardian::find($validated['parent_id']);
        $validated['parent_name'] = $guardian->first_name . ' ' . $guardian->last_name;
        $validated['parent_phone'] = $guardian->phone;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Student::create($validated);
        return redirect()->route('students.index')->with('success', 'Élève inscrit avec succès.');
    }

    public function show(Student $student)
    {
        $student->load(['class', 'payments', 'grades' => function($query) {
            $query->with('subject')->orderBy('trimester');
        }]);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::all();
        $guardians = Guardian::all();
        return view('students.edit', compact('student', 'classes', 'guardians'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:M,F',
            'parent_id' => 'required|exists:parents,id',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'registration_date' => 'required|date',
        ]);

        // Récupérer les informations du parent pour remplir les champs parent_name et parent_phone
        $guardian = Guardian::find($validated['parent_id']);
        $validated['parent_name'] = $guardian->first_name . ' ' . $guardian->last_name;
        $validated['parent_phone'] = $guardian->phone;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $student->update($validated);
        return redirect()->route('students.index')->with('success', 'Élève mis à jour avec succès.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Élève supprimé avec succès.');
    }

    public function getByClass($classId)
    {
        try {
            $students = Student::where('class_id', $classId)->get(['id', 'first_name', 'last_name']);
            $students->each(function($student) {
                $student->full_name = $student->first_name . ' ' . $student->last_name;
            });
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
