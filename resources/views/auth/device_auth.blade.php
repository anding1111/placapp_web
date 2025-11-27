
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Autorizar Dispositivo - Sistema Buscador de Placas</title>
    
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #f44336;
            color: white;
            font-weight: bold;
        }
        .device-id {
            background: #f5f5f5;
            padding: 10px;
            font-family: monospace;
            border-radius: 4px;
            word-break: break-all;
        }
        .btn-authorize {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-deny {
            background-color: #f44336;
            border-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <i class="fas fa-shield-alt mr-2"></i> Autorización de Dispositivo
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Un nuevo dispositivo está intentando acceder a su cuenta. Revise los detalles antes de autorizar.
                </div>
                
                <h5 class="card-title">ID del Dispositivo:</h5>
                <div class="device-id mb-4">{{ $uuid }}</div>
                
                <form action="{{ route('device.authorize') }}" method="post">
                    @csrf
                    <input type="hidden" name="uuid" value="{{ $uuid }}">
                    <input type="hidden" name="user_id" value="{{ $userId }}">
                    
                    <p>¿Desea autorizar este dispositivo para acceder a su cuenta?</p>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('login') }}" class="btn btn-deny text-white">
                            <i class="fas fa-times mr-2"></i> Denegar
                        </a>
                        <button type="submit" class="btn btn-authorize text-white">
                            <i class="fas fa-check mr-2"></i> Autorizar Dispositivo
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                <small>Si no intentó iniciar sesión desde un nuevo dispositivo, ignore esta solicitud.</small>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
