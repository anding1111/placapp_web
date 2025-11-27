@extends('layouts.app')

@section('content')
<div class="form-wrapper-table col-lg-4 col-md-6 col-sm-8 col-xs-8">
    <div class="panel w3-card-4">
        <div class="row justify-content-center">
            EDITAR USUARIO
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

            <form role="form" method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="fullName">Nombres y Apellidos</label>
                    <input class="form-control" id="fullName" name="name" required="required" type="text" value="{{ old('name', $user->name) }}">
                </div>

                <div class="form-group">
                    <label for="username">Correo Electrónico</label>
                    <input class="form-control" id="username" disabled value="{{ $user->username }}">
                    <small id="emailHelp" class="form-text text-muted">Usuario para iniciar sesión</small>
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña </label>
                    <input class="form-control password1" id="password" name="password" type="password" />
                    <span class="fa fa-fw fa-eye password-icon show-password"></span>
                    <small id="passwordHelp" class="form-text text-muted">Dejar en blanco para mantener la contraseña actual</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Repetir Contraseña</label>
                    <input class="form-control password2" id="password_confirmation" name="password_confirmation" type="password">
                    <small id="passwordConfirmHelp" class="form-text text-muted">Las claves deben coincidir</small>
                </div>

                <div class="form-group">
                    <label for="rol">Perfíl</label>
                    <input class="form-control" type="text" disabled value="{{ $user->role <= 1 ? 'Administrador' : 'Usuario' }}">
                </div>

                <div class="form-group">
                    <label for="level">Seleccione Nivel</label>
                    <select class="form-control" id="level" name="level">
                        @foreach($availableLevels as $value => $display)
                            <option value="{{ $value }}" {{ old('level', $user->level) == $value ? 'selected' : '' }}>
                                {{ $display }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <input type="submit" value="Guardar Cambios" class="btn btn-info btn-large" name="submit" />
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
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