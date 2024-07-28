<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmare Programare</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #28a745;
            color: white;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-footer {
            background-color: #f8f9fa;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .container {
            max-width: 600px;
        }
        .text-red {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1>Confirmare Programare</h1>
            </div>
            <div class="card-body">
                <p>{{ $appointment->user->name }},</p>
                <p>Suntem bucuroși să vă anunțăm că programarea dumneavoastră a fost confirmată.</p>
                <h4>Detaliile programării:</h4>
                <p class="details">
                    Doctor: <span>{{ $appointment->doctor->name }}</span><br>
                    Procedura: <span>{{ $appointment->consult->service }}</span><br>
                    Data și Ora: <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d-m-Y H:i') }}</span><br>
                    Preț<span class="text-red">*</span>: <span>{{ $appointment->consult->price }} Lei</span>
                </p>
                <p><span class="text-red">*</span>Prețul poate varia în funcție de complexitatea situației</p>
                <p>Dacă nu ați efectuat această programare, vă rugăm să răspundeți la acest email cu problema pentru a o putea remedia cât mai curând posibil.</p>
            </div>
            <div class="card-footer text-center">
                <p>Vă mulțumim pentru încrederea acordată!</p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
