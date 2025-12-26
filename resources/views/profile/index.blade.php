@extends('layouts.app')

@section('content')
    <div align="center">
        <div class="uploadWrapper" style="padding-bottom: 10px; padding-top: 10px;">
            <!-- Logo removed as requested -->
            
            <h4 class="text-white font-weight-bold mb-3">Mi Perfil</h4>

            <div class="px-3 text-left text-white" style="width: 100%;">
                
                <div class="row mb-2">
                    <div class="col-12">
                        <label class="font-weight-bold mb-0" style="font-size: 0.85em; opacity: 0.8;">Nombre</label>
                        <div class="px-2 py-1 rounded font-weight-bold border border-secondary" 
                             style="background: rgba(0,0,0,0.2); font-size: 1em;">
                            {{ $user->name }}
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        <label class="font-weight-bold mb-0" style="font-size: 0.85em; opacity: 0.8;">Usuario</label>
                        <div class="px-2 py-1 rounded font-weight-bold border border-secondary" 
                             style="background: rgba(0,0,0,0.2); font-size: 1em;">
                            {{ $user->username }}
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        <label class="font-weight-bold mb-0" style="font-size: 0.85em; opacity: 0.8;">Rol</label>
                        <div class="px-2 py-1 rounded font-weight-bold border border-secondary" 
                             style="background: rgba(0,0,0,0.2); font-size: 1em;">
                            {{ $user->role == 1 ? 'Administrador' : 'Usuario' }}
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 15px; margin-bottom: 15px;">
                
                <!-- Change Password Form -->
                <h6 class="font-weight-bold mb-2" style="color: #fff; opacity: 0.9;">Cambiar Contraseña</h6>
                <form action="{{ route('profile.updatePassword') }}" method="POST" class="mb-2">
                    @csrf
                    @method('PUT')
                    
                    @if(session('success'))
                        <div class="alert alert-success p-1 mb-2" style="font-size: 0.85em; background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; color: white;">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="form-group mb-1">
                        <label class="font-weight-bold small mb-0" style="opacity: 0.8;">Contraseña Actual</label>
                        <input type="password" name="current_password" class="form-control form-control-sm" required
                            style="background: rgba(0,0,0,0.2); border: 1px solid #6c757d; color: white; height: 30px;">
                         @error('current_password')
                            <span class="text-danger small" style="line-height:1;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-1">
                        <label class="font-weight-bold small mb-0" style="opacity: 0.8;">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control form-control-sm" required minlength="8"
                            style="background: rgba(0,0,0,0.2); border: 1px solid #6c757d; color: white; height: 30px;">
                         @error('password')
                            <span class="text-danger small" style="line-height:1;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold small mb-0" style="opacity: 0.8;">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-sm" required minlength="8"
                            style="background: rgba(0,0,0,0.2); border: 1px solid #6c757d; color: white; height: 30px;">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-sm font-weight-bold mt-2" style="background-color: #007bff; border: none; padding: 5px;">
                        Actualizar
                    </button>
                </form>

                <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 15px; margin-bottom: 15px;">

                <div class="text-center mb-2">
                    <button type="button" class="btn btn-block py-1 btn-sm" 
                        style="background-color: #FC2947; color: white; border: none; font-weight: 700; font-size: 1em; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" 
                        data-toggle="modal" data-target="#deleteAccountModal">
                        <i class="fas fa-trash-alt mr-2"></i> ELIMINAR CUENTA
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <!-- Delete Account Modal -->
    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #2b2b28; border: none; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.5);">
                <div class="modal-body p-4 text-center">
                    <!-- Icon -->
                    <div class="mb-3 text-danger">
                        <i class="fas fa-exclamation-circle fa-4x" style="color: #FC2947; opacity: 0.9;"></i>
                    </div>

                    <!-- Title -->
                    <h4 class="font-weight-bold mb-2 text-white" style="letter-spacing: 0.5px;">¿Eliminar Cuenta?</h4>

                    <!-- Text -->
                    <p class="text-white-50 mb-4" style="font-size: 0.95em;">
                        Esta acción no se puede deshacer.<br>Todos sus datos se perderán permanentemente.
                    </p>

                    <form action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <!-- Input -->
                        <div class="text-left mb-4 px-2">
                            <label for="password" class="text-white-50 small font-weight-bold text-uppercase" style="letter-spacing: 1px;">Confirmar con contraseña</label>
                            <input type="password" class="form-control" name="password" id="password" required 
                                style="background: #1a1a1a; border: 1px solid #444; color: white; border-radius: 8px; padding: 20px 15px;"
                                placeholder="****************">
                            @error('password')
                                <span class="text-danger small mt-2 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="row mx-0">
                            <div class="col-6 pl-1 pr-2">
                                <button type="button" class="btn btn-outline-light btn-block font-weight-bold py-2" data-dismiss="modal" style="border-radius: 8px; border-color: #555; color: #ccc;">
                                    Cancelar
                                </button>
                            </div>
                            <div class="col-6 pl-2 pr-1">
                                <button type="submit" class="btn btn-block font-weight-bold py-2" style="background-color: #FC2947; color: white; border: none; border-radius: 8px;">
                                    Sí, Eliminar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection