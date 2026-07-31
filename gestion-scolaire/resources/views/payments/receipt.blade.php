<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .receipt {
            border: 2px solid #333;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            color: #2563eb;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>REÇU DE PAIEMENT</h1>
            <p>Système de Gestion Scolaire</p>
        </div>

        <div class="info">
            <p><strong>Numéro de reçu:</strong> {{ $payment->receipt_number }}</p>
            <p><strong>Date:</strong> {{ $payment->payment_date->format('d/m/Y') }}</p>
        </div>

        <table>
            <tr>
                <td><strong>Élève:</strong></td>
                <td>{{ $payment->student->full_name }}</td>
            </tr>
            <tr>
                <td><strong>Classe:</strong></td>
                <td>{{ $payment->student->class->name }}</td>
            </tr>
            <tr>
                <td><strong>Parent:</strong></td>
                <td>{{ $payment->student->parent_name }}</td>
            </tr>
            <tr>
                <td><strong>Méthode de paiement:</strong></td>
                <td>{{ $payment->payment_method }}</td>
            </tr>
        </table>

        <div class="amount">
            {{ number_format($payment->amount, 2) }} FCFA
        </div>

        @if($payment->remarks)
        <div class="info">
            <p><strong>Remarques:</strong> {{ $payment->remarks }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Merci pour votre paiement!</p>
            <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
