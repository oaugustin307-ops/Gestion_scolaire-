<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Scolaire')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        
        .dark-mode {
            background-color: #1a1a2e;
            color: #eaeaea;
        }
        
        .dark-mode .sidebar {
            background-color: #16213e;
        }
        
        .dark-mode .main-content {
            background-color: #0f0f23;
        }
        
        .dark-mode .card {
            background-color: #1a1a2e;
            border-color: #2d2d44;
        }
        
        .dark-mode .table-header {
            background-color: #16213e;
        }
        
        .dark-mode .table-row {
            border-color: #2d2d44;
        }
        
        .dark-mode .footer {
            background-color: #16213e;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: background 0.3s;
        }
        
        .nav-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 4px 12px;
        }
        
        .nav-item:hover {
            transform: translateX(5px);
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .table-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .stat-card-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .stat-card-4 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <button class="theme-toggle" onclick="toggleTheme()" title="Basculer mode sombre/clair">
        🌙
    </button>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar w-64 text-white shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold">🎓 Gestion Scolaire</h1>
                <p class="text-sm opacity-75 mt-1">Système complet</p>
            </div>
            
            @auth
            <div class="px-6 py-3 border-b border-white/20">
                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-xs opacity-75 capitalize">{{ auth()->user()->role }}</p>
            </div>
            @endauth
            
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="nav-item block px-6 py-3">
                    📊 Tableau de bord
                </a>
                @auth
                @if(auth()->user()->role === 'gestionnaire')
                <a href="{{ route('classes.index') }}" class="nav-item block px-6 py-3">
                    🏫 Classes
                </a>
                <a href="{{ route('students.index') }}" class="nav-item block px-6 py-3">
                    👨‍🎓 Élèves
                </a>
                <a href="{{ route('subjects.index') }}" class="nav-item block px-6 py-3">
                    📚 Matières
                </a>
                <a href="{{ route('payments.index') }}" class="nav-item block px-6 py-3">
                    � Paiements
                </a>
                @endif
                <a href="{{ route('grades.index') }}" class="nav-item block px-6 py-3">
                    � Notes
                </a>
                @endauth
                
                @auth
                <form action="{{ route('logout') }}" method="POST" class="mt-6 px-6">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition">
                        🚪 Déconnexion
                    </button>
                </form>
                @endauth
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1 p-8 bg-gray-100">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 shadow-md">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 shadow-md">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <footer class="footer bg-gray-800 text-white py-4 mt-8">
        <div class="text-center px-8">
            <p>&copy; 2026 Système de Gestion Scolaire - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const button = document.querySelector('.theme-toggle');
            if (document.body.classList.contains('dark-mode')) {
                button.textContent = '☀️';
                localStorage.setItem('theme', 'dark');
            } else {
                button.textContent = '🌙';
                localStorage.setItem('theme', 'light');
            }
        }
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            document.querySelector('.theme-toggle').textContent = '☀️';
        }
    </script>
</body>
</html>
