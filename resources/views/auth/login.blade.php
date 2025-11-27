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
        
        /* Estilos para modal de dispositivo no autorizado */
        .device-auth-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .device-auth-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 360px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .device-auth-header {
            background-color: #ffebee;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ffcdd2;
        }
        
        .device-auth-header h2 {
            color: #e53935;
            font-size: 16px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .device-auth-header h2 i {
            margin-right: 8px;
        }
        
        .device-auth-body {
            padding: 12px;
        }
        
        .device-auth-body p {
            color: #333333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .device-auth-id {
            background-color: #f5f5f5;
            padding: 8px;
            border-radius: 5px;
            font-family: monospace;
            word-break: break-all;
            margin: 12px 0;
            text-align: center;
            color: #222222;
            font-size: 13px;
        }
        
        .device-auth-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 12px;
        }
        
        .device-auth-buttons button {
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #222222;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .device-auth-buttons button i {
            margin-right: 5px;
        }
        
        .device-auth-buttons button.share {
            background: #2196f3;
            color: white;
            border-color: #0b7dda;
        }
        
        .device-auth-buttons button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .device-auth-qr {
            text-align: center;
            margin-top: 12px;
        }
        
        .device-auth-qr img,
        .device-auth-qr canvas {
            max-width: 160px;
            margin: 0 auto;
            height: auto;
        }
        
        .device-auth-qr .qr-label {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 6px;
            font-size: 12px;
            color: #444444;
        }
        
        .device-auth-qr .qr-label i {
            margin-right: 5px;
        }
        
        .device-auth-footer {
            padding: 12px;
            text-align: center;
        }
        
        .device-auth-footer button {
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px;
            width: 100%;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .device-auth-footer button:hover {
            background: #43a047;
        }
        
        /* Toast notification styles */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333333;
            color: white;
            padding: 10px 16px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 2000;
            display: flex;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        
        .toast-notification.show {
            opacity: 1;
            visibility: visible;
        }
        
        .toast-notification i {
            margin-right: 8px;
        }
        
        /* Optimized for mobile */
        @media (max-width: 480px) {
            .device-auth-content {
                width: 95%;
                max-width: 320px;
            }
            
            .device-auth-body {
                padding: 10px;
            }
            
            .device-auth-qr img,
            .device-auth-qr canvas {
                max-width: 140px;
            }
            
            .device-auth-id {
                font-size: 12px;
                padding: 6px;
            }
            
            .device-auth-buttons button {
                font-size: 12px;
                padding: 6px;
            }
        }
        
        *, ::after, ::before {
            box-sizing: border-box !important;
        }
        
        /* Small text improvements */
        .text-muted {
            color: #444444 !important;
        }
        
        .small {
            font-size: 85%;
        }
        
        h3.text-center {
            color: #333333;
            font-size: 15px;
            margin: 10px 0 6px;
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

            <form action="{{ route('login') }}" method="post" id="login-form">
                @csrf
                <h3 style="text-align: center;">Iniciar Sesión</h3>

                <div class="form-item login">
                    <input type="text" name="username" placeholder="Usuario" autocomplete="username" autocorrect="off" autocapitalize="none" spellcheck="false" required value="{{ old('username') }}">
                </div>

                <div class="form-item login">
                    <input type="password" name="password" placeholder="Contraseña" autocomplete="current-password" autocorrect="off" autocapitalize="none" spellcheck="false">
                    <input type="hidden" name="uuid" id="uuid-field">
                </div>

                <div class="button-panel login">
                    <button type="submit" class="button" id="login-button">Iniciar</button>
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
                <p><a href="#">¿Has olvidado tu contraseña?</a></p>
            </div>
        </div>
    </div>
    
    <!-- Toast notification -->
    <div id="toast-notification" class="toast-notification">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">ID copiado al portapapeles</span>
    </div>
    
    <!-- Modal de dispositivo no autorizado -->
    <div id="device-auth-modal" class="device-auth-modal">
        <div class="device-auth-content">
            <div class="device-auth-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Dispositivo no autorizado</h2>
            </div>
            <div class="device-auth-body">
                <p>Este dispositivo no está registrado en el sistema</p>
                <p class="text-muted small">Por favor, solicita autorización a través de una de las siguientes opciones.</p>
                
                <h3 class="text-center mt-3 mb-2">ID del dispositivo</h3>
                <div class="device-auth-id" id="device-id-display"></div>
                
                <div class="device-auth-buttons">
                    <button onclick="copyDeviceId()" class="copy-btn">
                        <i class="fas fa-copy"></i> Copiar
                    </button>
                    <button onclick="shareDeviceId()" class="share">
                        <i class="fas fa-share-alt"></i> Compartir
                    </button>
                </div>
                
                <div class="device-auth-qr">
                    <h3 class="text-center mt-3 mb-2">Escanea este código</h3>
                    <div id="qrcode-container"></div>
                    <div class="qr-label">
                        <i class="fas fa-qrcode"></i> Dispositivo no autorizado detectado
                    </div>
                </div>
            </div>
            
            <div class="device-auth-footer">
                <button onclick="retryLogin()">
                    Intentar nuevamente
                </button>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <!-- QR Code Generator -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    
    <script>
        // Generar UUID basado en la información del dispositivo
        function generateDeviceId() {
            const platform = navigator.platform || 'Unknown';
            const userAgent = navigator.userAgent || 'Unknown';
            
            // Crear una cadena única de la info del dispositivo
            let deviceInfo = platform + userAgent;
            
            // Convertir a un hash más corto
            let hash = 0;
            for (let i = 0; i < deviceInfo.length; i++) {
                const char = deviceInfo.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash;
            }
            
            // Formatear el ID del dispositivo
            const baseInfo = platform.replace(/\s+/g, '_');
            const hashStr = Math.abs(hash).toString(16).substring(0, 8);
            const randomPart = Math.random().toString(36).substring(2, 10);
            const mia = 'MIA';
            const deviceId = `${mia}-${baseInfo}-${hashStr}-${randomPart}`;
            
            return deviceId;
        }
        
        // Almacenar y obtener el UUID del dispositivo
        function getDeviceId() {
            let deviceId = localStorage.getItem('device_uuid');
            
            if (!deviceId) {
                deviceId = generateDeviceId();
                localStorage.setItem('device_uuid', deviceId);
            }
            
            return deviceId;
        }
        
        // Mostrar notificación toast
        function showToast(message, duration = 2000) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, duration);
        }
        
        // Al cargar la página, configurar el UUID y detectar si hay mensaje de error
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer el UUID en el campo oculto
            const deviceId = getDeviceId();
            document.getElementById('uuid-field').value = deviceId;
            
            // Verificar si hay un error de dispositivo no autorizado
            const errorElements = document.querySelectorAll('.reminder div[style*="color: #dc3545"]');
            let deviceUnauthorized = false;
            
            errorElements.forEach(element => {
                if (element.textContent.trim().includes('Dispositivo no autorizado')) {
                    deviceUnauthorized = true;
                }
            });
            
            // Si el dispositivo no está autorizado, mostrar el modal
            if (deviceUnauthorized) {
                showDeviceAuthModal();
            }
            
            // Manejo específico para iOS para asegurarnos que el formulario se envía correctamente
            document.getElementById('login-button').addEventListener('click', function(event) {
                event.preventDefault();
                const form = document.getElementById('login-form');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect || '/home';
                    } else if (data.unauthorized) {
                        showDeviceAuthModal();
                    } else if (data.error) {
                        const reminderDiv = document.querySelector('.reminder');
                        reminderDiv.innerHTML = `<div style="color: #dc3545;">${data.error}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                    const reminderDiv = document.querySelector('.reminder');
                    reminderDiv.innerHTML = `<div style="color: #dc3545;">Error al iniciar sesión. Por favor, intenta nuevamente.</div>`;
                });
            });
            // Enfocar el campo de usuario después de un breve retraso
            setTimeout(() => {
                document.querySelector('input[name="username"]').focus();
            }, 500);
        });
        
        // Mostrar el modal de dispositivo no autorizado
        function showDeviceAuthModal() {
            const deviceId = getDeviceId();
            const modal = document.getElementById('device-auth-modal');
            const deviceIdDisplay = document.getElementById('device-id-display');
            
            // Mostrar el ID del dispositivo
            deviceIdDisplay.textContent = deviceId;
            
            // Generar código QR
            const qrcodeContainer = document.getElementById('qrcode-container');
            qrcodeContainer.innerHTML = '';
            
            QRCode.toCanvas(
                qrcodeContainer.appendChild(document.createElement('canvas')),
                deviceId,
                {
                    width: 160,
                    margin: 1,
                    color: {
                        dark: '#000000',
                        light: '#ffffff'
                    }
                }
            );
            
            // Mostrar el modal
            modal.style.display = 'flex';
        }
        
        // Copiar ID del dispositivo al portapapeles - MEJORADO
        function copyDeviceId() {
            const deviceId = document.getElementById('device-id-display').textContent;
            
            // Verificar primero si estamos en un contexto Capacitor
            if (window.Capacitor && window.Capacitor.isPluginAvailable('Clipboard')) {
                // Usar el plugin de Capacitor directamente
                window.Capacitor.Plugins.Clipboard.write({
                    string: deviceId
                }).then(() => {
                    // Si también está disponible Toast, usarlo
                    if (window.Capacitor.isPluginAvailable('Toast')) {
                        window.Capacitor.Plugins.Toast.show({
                            text: 'ID del dispositivo copiado al portapapeles',
                            duration: 'short',
                            position: 'bottom'
                        });
                    } else {
                        showToast('ID del dispositivo copiado al portapapeles');
                    }
                }).catch((err) => {
                    console.error('Error al usar el Clipboard de Capacitor:', err);
                    // Intentar con el método alternativo si falla
                    copyWithFallback();
                });
            } else {
                // Método alternativo para navegadores
                copyWithFallback();
            }
            
            // Función de respaldo para copiar
            function copyWithFallback() {
                // Intenta primero con la API moderna
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(deviceId)
                        .then(() => {
                            showToast('ID del dispositivo copiado al portapapeles');
                        })
                        .catch((err) => {
                            console.error('Error al copiar con clipboard API: ', err);
                            copyDeviceIdFallback(deviceId);
                        });
                } else {
                    // Fallback para casos donde la API moderna no está disponible
                    copyDeviceIdFallback(deviceId);
                }
            }
        }
        
        // Método alternativo para copiar al portapapeles
        function copyDeviceIdFallback(text) {
            try {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.top = '0';
                textArea.style.left = '0';
                textArea.style.opacity = '0';
                textArea.style.pointerEvents = 'none';
                textArea.style.zIndex = '-1';
                document.body.appendChild(textArea);
                
                if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
                    // Para iOS
                    const range = document.createRange();
                    range.selectNodeContents(textArea);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                    textArea.setSelectionRange(0, 999999);
                } else {
                    // Para otros dispositivos
                    textArea.select();
                }
                
                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);
                
                if (successful) {
                    showToast('ID del dispositivo copiado al portapapeles');
                } else {
                    showToast('No se pudo copiar automáticamente. Por favor, copia manualmente.');
                }
            } catch (err) {
                console.error('Error al copiar con fallback: ', err);
                showToast('Error al copiar. Intenta seleccionar y copiar manualmente.');
            }
        }
        
        // Compartir ID del dispositivo - MEJORADO
        function shareDeviceId() {
            const deviceId = document.getElementById('device-id-display').textContent;
            const shareTitle = 'ID de dispositivo para RAPTOR';
            const shareText = `Mi ID de dispositivo es: ${deviceId}`;
            
            // Verificar si estamos en un contexto Capacitor
            if (window.Capacitor && window.Capacitor.isPluginAvailable('Share')) {
                // Usar el plugin de Capacitor directamente
                window.Capacitor.Plugins.Share.share({
                    title: shareTitle,
                    text: shareText,
                    dialogTitle: 'Compartir ID de dispositivo'
                }).catch(error => {
                    console.log('Error al usar Share de Capacitor:', error);
                    shareWithFallback();
                });
            } else {
                // Intentar con Web Share API o método alternativo
                shareWithFallback();
            }
            
            // Función de respaldo para compartir
            function shareWithFallback() {
                // Verificar si el navegador admite la API Web Share
                if (navigator.share) {
                    navigator.share({
                        title: shareTitle,
                        text: shareText
                    }).catch(error => {
                        console.log('Error al compartir:', error);
                        // Si falla, copiar al portapapeles como último recurso
                        copyDeviceId();
                        showToast('No se pudo compartir. ID copiado al portapapeles.');
                    });
                } else {
                    // En navegadores que no soportan compartir
                    const userAgent = navigator.userAgent.toLowerCase();
                    
                    if (/iphone|ipad|ipod/.test(userAgent)) {
                        // iOS sin soporte de compartir
                        copyDeviceId();
                        setTimeout(() => {
                            alert('ID copiado al portapapeles. Puedes pegarlo en cualquier app para compartirlo.');
                        }, 300);
                    } else if (/android/.test(userAgent)) {
                        // Android sin soporte de compartir
                        copyDeviceId();
                        setTimeout(() => {
                            alert('ID copiado al portapapeles. Puedes pegarlo en cualquier app para compartirlo.');
                        }, 300);
                    } else {
                        // Navegadores de escritorio
                        copyDeviceId();
                    }
                }
            }
        }
        
        // Recargar la página para reintentar
        function retryLogin() {
            window.location.reload();
        }
    </script>
</body>

</html>