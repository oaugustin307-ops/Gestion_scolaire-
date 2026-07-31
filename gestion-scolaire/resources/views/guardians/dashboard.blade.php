<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Parent - Gestion Scolaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Espace Parent</h1>
            <div class="flex items-center space-x-4">
                <span>Bonjour, {{ $guardian->first_name }} {{ $guardian->last_name }}</span>
                <form method="POST" action="{{ route('guardian.logout') }}">
                    @csrf
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <h2 class="text-2xl font-bold mb-6">Mes Enfants</h2>

        @if($students->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            Aucun enfant enregistré dans le système.
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($students as $student)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->full_name }}" class="w-16 h-16 rounded-full object-cover mr-4">
                    @else
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                        <span class="text-gray-600 text-xl">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                    </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-bold">{{ $student->full_name }}</h3>
                        <p class="text-gray-600">{{ $student->class->name }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Moyenne T1:</span>
                        <span class="font-bold">{{ number_format($student->trimester1_average, 2) }}/20</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Moyenne T2:</span>
                        <span class="font-bold">{{ number_format($student->trimester2_average, 2) }}/20</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Moyenne T3:</span>
                        <span class="font-bold">{{ number_format($student->trimester3_average, 2) }}/20</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-800 font-bold">Moyenne Annuelle:</span>
                        <span class="font-bold text-blue-600">{{ number_format($student->annual_average, 2) }}/20</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-bold text-gray-700 mb-2">Présences récentes</h4>
                    @php
                        $recentAttendances = $student->attendances()->orderBy('date', 'desc')->take(3)->get();
                    @endphp
                    @if($recentAttendances->isEmpty())
                    <p class="text-gray-500 text-sm">Aucune présence enregistrée</p>
                    @else
                    <div class="space-y-1">
                        @foreach($recentAttendances as $attendance)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $attendance->date->format('d/m/Y') }}</span>
                            <span class="px-2 py-0.5 rounded text-xs font-semibold
                                @if($attendance->status == 'present') bg-green-100 text-green-800
                                @elseif($attendance->status == 'absent') bg-red-100 text-red-800
                                @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>
