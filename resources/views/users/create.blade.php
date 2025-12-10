@extends('layouts.app')

@section('content')
<div class="modern-modal-wrapper">
    <div class="modern-modal-header">
        <h3>AÑADIR USUARIO</h3>
    </div>
    
    <div class="modern-modal-body">
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
            <div class="modern-form-group">
                <label for="fullName">Nombres y Apellidos</label>
                <input class="modern-input" id="fullName" name="name" required="required" type="text" value="{{ old('name') }}" placeholder="Ingrese nombre completo">
            </div>

            <div class="modern-form-group">
                <label for="username">Correo Electrónico</label>
                <input class="modern-input" id="username" name="username" required="required" type="text" value="{{ old('username') }}" placeholder="usuario@ejemplo.com">
                <small class="form-text text-muted">Usuario para iniciar sesión</small>
            </div>

            <div class="modern-form-group">
                <label for="password">Contraseña</label>
                <div class="password-wrapper">
                    <input class="modern-input password1" id="password" name="password" required="required" type="password" placeholder="••••••••">
                    <span class="fa fa-fw fa-eye password-icon show-password"></span>
                </div>
            </div>

            <div class="modern-form-group">
                <label for="password_confirmation">Repetir Contraseña</label>
                <div class="password-wrapper">
                    <input class="modern-input password2" id="password_confirmation" name="password_confirmation" required="required" type="password" placeholder="••••••••">
                    <span class="fa fa-fw fa-eye password-icon show-password"></span>
                </div>
                <small class="form-text text-muted">Las claves deben coincidir</small>
            </div>

            <div class="modern-form-group">
                <label for="rol">Seleccione un Perfil</label>
                <div class="select-wrapper">
                    <select class="modern-select" id="rol" name="rol">
                        <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('rol') == 2 || old('rol') === null ? 'selected' : '' }}>Usuario</option>
                    </select>
                </div>
            </div>

            <div class="modern-form-group">
                <label for="level">Seleccione Nivel</label>
                <div class="select-wrapper">
                    <select class="modern-select" id="level" name="level">
                        @foreach($availableLevels as $value => $display)
                            <option value="{{ $value }}" {{ old('level') == $value ? 'selected' : ($value == 3 && old('level') === null ? 'selected' : '') }}>
                                {{ $display }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modern-form-actions">
                <input type="submit" value="Añadir Ahora" class="modern-btn" name="submit" />
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show or Hide Password
    window.addEventListener("load", function() {
        // Seleccionar todos los iconos de "ojo"
        var showPasswordIcons = document.querySelectorAll('.show-password');

        showPasswordIcons.forEach(function(icon) {
            icon.addEventListener('click', function() {
                // Encontrar el input hermano (dentro del mismo padre .password-wrapper)
                var wrapper = this.closest('.password-wrapper');
                var input = wrapper.querySelector('input');

                if (input.type === "password") {
                    input.type = "text";
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = "password";
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endpush