<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::all();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'school_fees' => 'required|numeric|min:0',
        ]);

        ClassRoom::create($validated);
        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès.');
    }

    public function show(ClassRoom $class)
    {
        $class->load('students');
        return view('classes.show', compact('class'));
    }

    public function edit(ClassRoom $class)
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'school_fees' => 'required|numeric|min:0',
        ]);

        $class->update($validated);
        return redirect()->route('classes.index')->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès.');
    }
}
