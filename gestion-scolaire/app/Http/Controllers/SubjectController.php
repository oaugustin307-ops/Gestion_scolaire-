<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->query('class_id');
        $classes = ClassRoom::all();
        
        if ($classId) {
            $subjects = Subject::with('class')->where('class_id', $classId)->get();
        } else {
            $subjects = collect();
        }
        
        return view('subjects.index', compact('subjects', 'classes', 'classId'));
    }

    public function create(Request $request)
    {
        $classes = ClassRoom::all();
        $selectedClassId = $request->query('class_id');
        return view('subjects.create', compact('classes', 'selectedClassId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'coefficient' => 'required|numeric|min:0.1|max:10',
        ]);

        Subject::create($validated);
        return redirect()->route('subjects.index')->with('success', 'Matière créée avec succès.');
    }

    public function show(Subject $subject)
    {
        $subject->load('grades.student');
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $classes = ClassRoom::all();
        return view('subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'coefficient' => 'required|numeric|min:0.1|max:10',
        ]);

        $subject->update($validated);
        return redirect()->route('subjects.index')->with('success', 'Matière mise à jour avec succès.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Matière supprimée avec succès.');
    }

    public function getByClass($classId)
    {
        $subjects = Subject::where('class_id', $classId)->get(['id', 'name', 'code']);
        return response()->json($subjects);
    }
}
