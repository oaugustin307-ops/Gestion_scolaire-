<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('student.class')->latest()->get();
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $students = Student::with('class')->get();
        return view('attendances.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'reason' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        try {
            // Créer une nouvelle présence
            Attendance::create($validated);
            return redirect()->route('attendances.index')->with('success', 'Présence enregistrée avec succès.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Si l'erreur est une violation de contrainte unique
            if ($e->getCode() == '23000') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Une présence existe déjà pour cet élève à cette date. Veuillez la modifier via la page de liste.');
            }
            // Autre erreur
            throw $e;
        }
    }

    public function show($id)
    {
        $attendance = Attendance::with('student.class')->findOrFail($id);
        return view('attendances.show', compact('attendance'));
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $students = Student::with('class')->get();
        return view('attendances.edit', compact('attendance', 'students'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'reason' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($validated);
        return redirect()->route('attendances.index')->with('success', 'Présence mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Présence supprimée avec succès.');
    }
}
