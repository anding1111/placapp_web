@extends('layouts.app')

@section('content')
<div class="form-wrapper-table col-lg-4 col-md-6 col-sm-8 col-xs-8">
    <div class="panel w3-card-4">
        <div class="row justify-content-center">
            AÑADIR USUARIO
        </div>
        <!-- /.panel-heading -->
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form role="form" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="form-group">
                    <label for="fullName">Nombres y Apellidos</label>
                    <input class="form-control" id="fullName" name="name" required="required" type="text" value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="username">Correo Electrónico</label>
                    <input class="form-control" id="username" name="username" required="required" type="text" value="{{ old('username') }}">
                    <small id="emailHelp" class="form-text text-muted">Usuario para iniciar sesión</small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña </label>
                    <input class="form-control password1" id="password" name="password" required="required" type="password" />
                    <span class="fa fa-fw fa-eye password-icon show-password"></span>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Repetir Contraseña</label>
                    <input class="form-control password2" id="password_confirmation" name="password_confirmation" required="required" type="password">
                    <small id="passwordHelp" class="form-text text-muted">Las claves deben coincidir</small>
                </div>

                <div class="form-group">
                    <label for="rol">Seleccione un Perfíl</label>
                    <select class="form-control" id="rol" name="rol">
                        <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('rol') == 2 || old('rol') === null ? 'selected' : '' }}>Usuario</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="level">Seleccione Nivel</label>
                    <select class="form-control" id="level" name="level">
                        @foreach($availableLevels as $value => $display)
                            <option value="{{ $value }}" {{ old('level') == $value ? 'selected' : ($value == 3 && old('level') === null ? 'selected' : '') }}>
                                {{ $display }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" value="Añadir Ahora" class="btn btn-info btn-large" name="submit" />
            </form>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
@endsection

@push('scripts')
<script>
    // Show or Hide Password
    window.addEventListener("load", function() {
        // icono para poder interaccionar con el elemento
        var showPassword = document.querySelector('.show-password');
        if (showPassword) {
            showPassword.addEventListener('click', function() {
                // elementos input de tipo password
                var password1 = document.querySelector('.password1');
                var password2 = document.querySelector('.password2');

                if (password1.type === "text") {
                    password1.type = "password";
                    password2.type = "password";
                    showPassword.classList.remove('fa-eye-slash');
                } else {
                    password1.type = "text";
                    password2.type = "text";
                    showPassword.classList.toggle("fa-eye-slash");
                }
            });
        }
    });
</script>
@endpush