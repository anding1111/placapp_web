@extends('layouts.app')

@section('content')
<div class="modern-modal-wrapper">
    <div class="modern-modal-header">
        <h3>EDITAR USUARIO</h3>
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

        <form role="form" method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="modern-form-group">
                <label for="fullName">Nombres y Apellidos</label>
                <input class="modern-input" id="fullName" name="name" required="required" type="text" value="{{ old('name', $user->name) }}" placeholder="Ingrese nombre completo">
            </div>

            <div class="modern-form-group">
                <label for="username">Correo Electrónico</label>
                <input class="modern-input" id="username" disabled value="{{ $user->username }}" style="background-color: #e9ecef;">
                <small class="form-text text-muted">Usuario para iniciar sesión</small>
            </div>

            <div class="modern-form-group">
                <label for="password">Nueva Contraseña</label>
                <div class="password-wrapper">
                    <input class="modern-input password1" id="password" name="password" type="password" placeholder="••••••••">
                    <span class="fa fa-fw fa-eye password-icon show-password"></span>
                </div>
                <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual</small>
            </div>

            <div class="modern-form-group">
                <label for="password_confirmation">Repetir Contraseña</label>
                <div class="password-wrapper">
                    <input class="modern-input password2" id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••">
                    <span class="fa fa-fw fa-eye password-icon show-password"></span>
                </div>
                <small class="form-text text-muted">Las claves deben coincidir</small>
            </div>

            <div class="modern-form-group">
                <label for="rol">Perfil</label>
                <input class="modern-input" type="text" disabled value="{{ $user->role <= 1 ? 'Administrador' : 'Usuario' }}" style="background-color: #e9ecef;">
            </div>

            <div class="modern-form-group">
                <label for="level">Seleccione Nivel</label>
                <div class="select-wrapper">
                    <select class="modern-select" id="level" name="level">
                        @foreach($availableLevels as $value => $display)
                            <option value="{{ $value }}" {{ old('level', $user->level) == $value ? 'selected' : '' }}>
                                {{ $display }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modern-form-actions">
                <input type="submit" value="Guardar Cambios" class="modern-btn" name="submit" />
                <a href="{{ route('users.index') }}" class="modern-btn-secondary">Cancelar</a>
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