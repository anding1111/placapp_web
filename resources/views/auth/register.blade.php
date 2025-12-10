<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('img/Logo_Placapp.png') }}" />
    <!--===============================================================================================-->

    <title>Registro - Sistema Buscador de Placas</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <style>
        .body_display {
            background: url({{ asset('img/bg-update.jpg') }}) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        
        .text-danger {
            color: #dc3545!important;
        }
        
        .text-success {
            color: #28a745!important;
        }
        
        *, ::after, ::before {
            box-sizing: border-box !important;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 15px;
        }
        
        .back-to-login a {
            color: #fff;
            text-decoration: none;
            font-size: 0.9em;
            transition: color 0.3s;
        }
        
        .back-to-login a:hover {
            color: #00b6df;
        }

        .modern-modal {
            top: 50%;
        }
    </style>
</head>

<body class="body_display">
    <div id="displayed">
        <div class="form form-wrapper w3-card-4w padding modern-modal">
            <div align="center">
                <div class="avatar">
                    <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
                </div>
            </div>

            <form action="{{ route('register') }}" method="post" id="register-form">
                @csrf
                <h3 style="text-align: center;">Registro Usuarios</h3>

                @if($errors->any())
                    <div class="reminder">
                        <div class="text-danger">
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="reminder">
                        <div class="text-success">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="form-item login">
                    <input type="text" name="name" placeholder="Nombre completo" autocomplete="name" required value="{{ old('name') }}">
                </div>

                <div class="form-item login">
                    <input type="email" name="email" placeholder="Correo electrónico" autocomplete="email" required value="{{ old('email') }}">
                </div>

                <div class="form-item login">
                    <input type="password" name="password" placeholder="Contraseña" autocomplete="new-password" required>
                </div>

                <div class="form-item login">
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" autocomplete="new-password" required>
                </div>

                <div class="button-panel login">
                    <button type="submit" class="button">Registrarse</button>
                </div>
            </form>
            
            <div class="back-to-login">
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
