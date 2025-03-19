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

    <title>Sistema Buscador de Placas</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <style>
        .none_display {
            display: none;
        }

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
    </style>
</head>

<body>
    <div id="displayed">
        <div class="form form-wrapper w3-card-4w padding">
            <div align="center">
                <div class="avatar">
                    <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
                </div>
            </div>

            <form action="{{ route('login') }}" method="post">
                @csrf
                <h3 style="text-align: center;">Iniciar Sesión</h3>

                <div class="form-item login">
                    <input type="text" name="username" placeholder="Usuario" autocomplete="on" autofocus required value="{{ old('username') }}">
                </div>

                <div class="form-item login">
                    <input type="password" name="password" placeholder="Contraseña" autocomplete>
                    <input type="hidden" name="uuid" id="uuid" value="Diana">
                </div>

                <div class="button-panel login">
                    <input type="submit" class="button" title="Iniciar" name="login" value="Iniciar">
                </div>
            </form>
            <div class="reminder">
                @if($errors->has('error'))
                    <div style="color: #dc3545;">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="color: #dc3545;">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- <p>¿No es un miembro? <a href="#">Regístrate ahora</a></p> -->
                <p><a href="#">¿Has olvidado tu contraseña?</a></p>
            </div>

        </div>
    </div>
</body>

</html>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<!-- Bootstrap 4 JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        if (window?.AppInventor?.getWebViewString() === undefined || window?.AppInventor?.getWebViewString() == " ") {
            console.log("outdated");
            // var element = document.getElementById("displayed");
            // element.classList.add("none_display");
            // var body = document.body;
            // body.classList.add("body_display");
            // document.write("<div><h1 style='text-align: center; margin-top: 60px; color: white; font-size: 30px; text-shadow: -1px 1px 0 #000, 1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;'>¡Actualiza la App para poder usarla!</h1></div>");
        } else {
            document.getElementById("uuid").value = window.AppInventor.getWebViewString();
            // window.AppInventor.setWebViewString(window.AppInventor.getWebViewString());
        }
    });
</script>