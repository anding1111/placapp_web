// script.js actualizado para Laravel 12

// Función para abrir URLs basadas en la navegación
function openURL(page) {
    // Detener el tracker antes de navegar para evitar peticiones canceladas
    if (window.onlineTrackerInstance) {
        window.onlineTrackerInstance.stop();
    }

    // Utilizar rutas nombradas de Laravel en lugar de URL directas
    switch (page) {
        case "home":
            window.location.href = laravelRoutes.home || "/home";
            break;
        case "upload_file":
            window.location.href = laravelRoutes.upload || "/upload";
            break;
        case "delete":
            window.location.href = laravelRoutes.delete || "/delete";
            break;
        case "exit_to_app":
        case "logout":
            window.location.href = laravelRoutes.logout || "/logout";
            break;
        case "manage_accounts":
            window.location.href = laravelRoutes.users || "/users";
            break;
        case "person":
            window.location.href = laravelRoutes.profile || "/profile";
            break;
        default:
            window.location.href = laravelRoutes.home || "/home";
            break;
    }
}

var navItems = document.querySelectorAll(".mobile-bottom-nav__item");
navItems.forEach(function (e, i) {
    e.addEventListener("click", function () {
        // Validación nativa: Si ya estamos en esta vista, no hacer nada
        if (this.classList.contains("mobile-bottom-nav__item--active")) {
            return;
        }

        navItems.forEach(function (e2) {
            e2.classList.remove("mobile-bottom-nav__item--active");
        });
        this.classList.add("mobile-bottom-nav__item--active");

        // Activar la navegación basada en el contenido del icono
        var iconElement = this.querySelector(".mobile-bottom-nav__item-content i");
        if (iconElement) {
            var page = iconElement.innerText.trim();
            openURL(page);
        }
    });
});

// Upload Excel a Laravel
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadedImg[0].style.backgroundImage = 'url(' + e.target.result + ')';
        };

        reader.readAsDataURL(input.files[0]);

        var file = input.files[0];
        var formData = new FormData();
        formData.append('excel', file); // Cambiado de 'archivo' a 'excel' según el nombre del campo en el controlador
        formData.append('_token', csrfToken || $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: laravelRoutes.uploadExcel || '/upload-excel', // Ruta de Laravel para subir Excel
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    console.log('Archivo subido correctamente');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al subir archivo:', error);
            }
        });
    }
}

// Delete Excel en Laravel
function readURLDel(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadedImg[0].style.backgroundImage = 'url(' + e.target.result + ')';
        };

        reader.readAsDataURL(input.files[0]);

        var file = input.files[0];
        var formData = new FormData();
        formData.append('excel', file); // Cambiado de 'archivo' a 'excel'
        formData.append('_token', csrfToken || $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: laravelRoutes.deleteExcel || '/delete-excel', // Ruta de Laravel para eliminar por Excel
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    console.log('Placas eliminadas correctamente');
                    // Recargar tabla si está presente
                    if (typeof platesTbl !== 'undefined' && platesTbl !== '') {
                        platesTbl.draw(false);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al procesar archivo:', error);
            }
        });
    }
}

var $form = $("#imageUploadForm"),
    $file = $("#file"),
    $fileDel = $("#file-delete"),
    $uploadedImg = $("#uploadedImg"),
    $helpText = $("#helpText");

$file.on('change', function () {
    readURL(this);
    $form.addClass('loading');
});

$fileDel.on('change', function () {
    readURLDel(this);
    $form.addClass('loading');
});

$uploadedImg.on('webkitAnimationEnd MSAnimationEnd oAnimationEnd animationend', function () {
    $form.addClass('loaded');
});

$helpText.on('webkitAnimationEnd MSAnimationEnd oAnimationEnd animationend', function () {
    setTimeout(function () {
        $file.val('');
        $form.removeClass('loading').removeClass('loaded');
    }, 5000);
});

// Los botones de navegación ya se manejan en el listener de .mobile-bottom-nav__item al inicio del archivo
// para evitar duplicidad de eventos y peticiones canceladas.

var $nav_bar = $(".mobile-bottom-nav"),
    $color_selector = $(".color-selector");

// Función que se ejecuta cuando cambia el estado del teclado (abierto o cerrado)
function onKeyboardOnOff(isOpen) {
    if (isOpen && isMobile()) {
        // El teclado está abierto
        $nav_bar.addClass('hidden-nav');
        $color_selector.addClass('set-colors');
        console.log("Teclado Abierto");
    } else {
        $nav_bar.removeClass('hidden-nav');
        $color_selector.removeClass('set-colors');
        console.log("Teclado Cerrado");
    }
}

// Variable que almacena el valor original de la suma de la anchura y altura de la ventana
var originalPotion = false;

// Cuando el documento esté listo
$(document).ready(function () {
    // Si originalPotion es false, calcula y asigna el valor
    if (originalPotion === false) originalPotion = $(window).width() + $(window).height();

    // Acción al dar clic en Botón Flotante
    $('.floatingButton').on('click', function (e) {
        $(this).toggleClass('open');
    });

    $(this).on('click', function (e) {
        var container = $(".floatingButton");
        if (!container.is(e.target) && $('.floatingButtonWrap').has(e.target).length === 0) {
            if (container.hasClass('open')) {
                container.removeClass('open');
            }
        }
    });

    // Acción al dar clic en Salir
    $('.close-container').on('click', function (e) {
        window.location.href = laravelRoutes.logout || '/logout';
    });

    // Acción al dar clic en usuarios en línea
    $('.online-container').on('click', function (e) {
        window.location.href = laravelRoutes.onlineUsers || '/online-users';
    });
});

// Función para determinar el sistema operativo móvil
function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // Priorizamos Windows Phone debido a que su UA también contiene "Android"
    if (/windows phone/i.test(userAgent)) {
        return "winphone";
    }

    if (/android/i.test(userAgent)) {
        return "android";
    }

    // Detectamos iOS utilizando una expresión regular
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "ios";
    }

    return "";
}

// Función para aplicar cambios después de un redimensionamiento de la ventana
function applyAfterResize() {
    // Si no estamos en iOS y originalPotion no es falso
    if (getMobileOperatingSystem() != 'ios') {
        if (originalPotion !== false) {
            var wasWithKeyboard = $('body').hasClass('view-withKeyboard');
            var nowWithKeyboard = false;

            // Calculamos la diferencia entre originalPotion y la suma actual
            var diff = Math.abs(originalPotion - ($(window).width() + $(window).height()));
            if (diff > 100) nowWithKeyboard = true;

            // Cambiamos la clase en el cuerpo según si el teclado está abierto o cerrado
            $('body').toggleClass('view-withKeyboard', nowWithKeyboard);

            // Si el estado del teclado cambió, llamamos a onKeyboardOnOff()
            if (wasWithKeyboard != nowWithKeyboard) {
                onKeyboardOnOff(nowWithKeyboard);
            }
        }
    }
}

// Evento para detectar el enfoque y desenfoque de ciertos elementos
$(document).on('focus blur', 'select, textarea, input[type=text], input[type=date], input[type=password], input[type=email], input[type=number]', function (e) {
    var $obj = $(this);
    var nowWithKeyboard = (e.type == 'focusin');
    // Evitar cambios si el teclado ya está en el estado correcto
    if ($('body').hasClass('view-withKeyboard') !== nowWithKeyboard) {
        $('body').toggleClass('view-withKeyboard', nowWithKeyboard);
        onKeyboardOnOff(nowWithKeyboard);
    }
});

// Evento para detectar cambios en el tamaño de la ventana u orientación
$(window).on('resize orientationchange', function () {
    applyAfterResize();
});

function isMobile() {
    if (window.matchMedia("(max-width: 767px)").matches) {
        return true;
    } else {
        return false;
    }
}

// OnlineTracker class actualizada para Laravel
class OnlineTracker {
    constructor(options = {}) {
        this.userId = options.userId;
        this.interval = options.interval || 30000;
        this.retryAttempts = options.retryAttempts || 3;
        this.retryDelay = options.retryDelay || 5000;
        this.endpoint = options.endpoint || '/online';
        this.currentRetry = 0;
        this.active = false;
        this.isUpdating = false;
        this.lastUpdateTime = 0;
        this.timeoutId = null;
    }

    // Método robusto para obtener el token CSRF actualizado
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               (typeof csrfToken !== 'undefined' ? csrfToken : '') || 
               $('meta[name="csrf-token"]').attr('content') || '';
    }

    start() {
        if (this.active) return;
        this.active = true;
        this.updateStatus();
        
        // Listeners globales (solo una vez)
        if (!this.initialized) {
            document.addEventListener('visibilitychange', () => this.handleVisibilityChange());
            window.addEventListener('beforeunload', () => this.handleUnload());
            this.initialized = true;
        }
    }

    stop() {
        this.active = false;
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
            this.timeoutId = null;
        }
    }

    async updateStatus() {
        if (!this.active || this.isUpdating) return;

        this.isUpdating = true;
        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: this.userId,
                    timestamp: new Date().toISOString()
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Reset retry counter on successful update
            this.currentRetry = 0;
            this.lastUpdateTime = Date.now();

        } catch (error) {
            console.error('Error updating online status:', error);
            await this.handleError();
        } finally {
            this.isUpdating = false;
            // Programar la siguiente actualización solo después de que esta terminó
            if (this.active) {
                this.timeoutId = setTimeout(() => this.updateStatus(), this.interval);
            }
        }
    }

    async handleError() {
        if (this.currentRetry < this.retryAttempts) {
            this.currentRetry++;
            await new Promise(resolve => setTimeout(resolve, this.retryDelay));
            return this.updateStatus();
        }
        // If all retries failed, stop the tracker
        this.stop();
    }

    handleVisibilityChange() {
        if (document.hidden) {
            this.stop();
        } else {
            this.start();
        }
    }

    handleUnload() {
        // Evitar múltiples actualizaciones si ya se envió una exitosa recientemente (ej: hace menos de 5 segundos)
        if (Date.now() - this.lastUpdateTime < 5000) return;

        // Intentar enviar una actualización final con navigator.sendBeacon
        if (navigator.sendBeacon) {
            // Usar URLSearchParams para que Laravel pueda validar el CSRF Token en el cuerpo de la petición
            const params = new URLSearchParams();
            params.append('user_id', this.userId);
            params.append('_token', this.getCsrfToken());
            params.append('status', 'offline');
            
            navigator.sendBeacon(this.endpoint, params);
        }
    }
}

// Usage example (se inicializa en app.blade.php):
// const tracker = new OnlineTracker({
//     userId: document.getElementById('my-id').value,
//     interval: 30000,
//     endpoint: '/online',
//     csrfToken: '{{ csrf_token() }}'
// });

// Funciones para mostrar/ocultar secciones
function funcShow() {
    $(".contFile").fadeIn("slow");
    $(".contTable").fadeOut("slow");
}

function funcHide() {
    $(".contFile").fadeOut("slow");
    $(".contTable").fadeIn("slow");
}

function funcToggle() {
    $(".cont").fadeToggle("slow");
}

function togglePasswordVisibility(inputId, iconElement) {
    const input = document.getElementById(inputId);
    if (!input || !iconElement) return;

    if (input.type === 'password') {
        input.type = 'text';
        iconElement.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        iconElement.classList.replace('fa-eye-slash', 'fa-eye');
    }
}