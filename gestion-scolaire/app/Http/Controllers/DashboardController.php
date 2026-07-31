<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalClasses = ClassRoom::count();
        $totalCollected = Payment::sum('amount');
        
        $totalExpected = 0;
        foreach (ClassRoom::with('students')->get() as $class) {
            $totalExpected += $class->school_fees * $class->students->count();
        }
        
        $remainingBalance = $totalExpected - $totalCollected;
        
        $overdueStudents = Student::with('class')
            ->get()
            ->filter(function ($student) use ($totalCollected) {
                return $student->remaining_balance > 0;
            });

        $recentPayments = Payment::with('student.class')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'totalClasses',
            'totalCollected',
            'totalExpected',
            'remainingBalance',
            'overdueStudents',
            'recentPayments'
        ));
    }
}
