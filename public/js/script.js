function openURL(page) {
	var re = new RegExp(/^.*\//);
	var urlBASE = re.exec(window.location.href)[0];
	switch (page) {
		case "upload_file":
			window.open(urlBASE + "upload.php", "_self");
			break;
		case "delete":
			window.open(urlBASE + "delete.php", "_self");
			break;
		case "exit_to_app":
			window.open(urlBASE + "logout.php", "_self");
			break;
		case "manage_accounts":
			window.open(urlBASE + "users.php", "_self");
			break;
		default:
			window.open(urlBASE + "home.php", "_self");
			break;
	}
}

var navItems = document.querySelectorAll(".mobile-bottom-nav__item");
navItems.forEach(function (e, i) {
	e.addEventListener("click", function (e) {
		navItems.forEach(function (e2, i2) {
			e2.classList.remove("mobile-bottom-nav__item--active");
		})
		this.classList.add("mobile-bottom-nav__item--active");
	});
});


// Upload Excel to SQL
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$uploadedImg[0].style.backgroundImage = 'url(' + e.target.result + ')';
		};

		reader.readAsDataURL(input.files[0]);

		var file = input.files[0];
		var formData = new FormData();
		formData.append('archivo', file);

		$.ajax({
			url: 'upload_excel.php', // Cambia esto al archivo PHP en tu servidor
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				// $('#mensaje').html(response); // Actualiza el contenido del elemento con el mensaje de respuesta
			},
			error: function (xhr, status, error) {
				console.error(error);
			}
		});
	}
}

// Delete Excel to SQL
function readURLDel(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$uploadedImg[0].style.backgroundImage = 'url(' + e.target.result + ')';
		};

		reader.readAsDataURL(input.files[0]);

		var file = input.files[0];
		var formData = new FormData();
		formData.append('archivo', file);

		$.ajax({
			url: 'delete_excel.php', // Cambia esto al archivo PHP en tu servidor
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				// $('#mensaje').html(response); // Actualiza el contenido del elemento con el mensaje de respuesta
			},
			error: function (xhr, status, error) {
				console.error(error);
			}
		});
	}
}
var $form = $("#imageUploadForm"),
	$file = $("#file"),
	$fileDel = $("#file-delete"),
	$uploadedImg = $("#uploadedImg"),
	$helpText = $("#helpText")
	;
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
		$file.val(''); $form.removeClass('loading').removeClass('loaded');
	}, 5000);
});

var $url_button = $(".url_button");
$url_button.on('click', function () {
	var firstChildText = $(this).find('i:first-child').text();
	openURL(firstChildText);
});

var $nav_bar = $(".mobile-bottom-nav"),
	$color_selector = $(".color-selector");
// Función que se ejecuta cuando cambia el estado del teclado (abierto o cerrado)
function onKeyboardOnOff(isOpen) {
	// Aquí puedes agregar tu lógica para manejar el estado del teclado
	if (isOpen && isMobile()) {
		// El teclado está abierto
		$nav_bar.addClass('hidden-nav');
		$color_selector.addClass('set-colors');
		console.log("Teclado Abierto");
	} else {
		$nav_bar.removeClass('hidden-nav');
		$color_selector.removeClass('set-colors');
		console.log("Teclado Cerrado");
		// El teclado está cerrado
	}
}

// Variable que almacena el valor original de la suma de la anchura y altura de la ventana
var originalPotion = false;

// Cuando el documento esté listo
$(document).ready(function () {
	// Si originalPotion es false, calcula y asigna el valor de la suma de la anchura y altura de la ventana
	if (originalPotion === false) originalPotion = $(window).width() + $(window).height();

	//Acción al dar clic en Boton Flotante
	$('.floatingButton').on('click',
		function (e) {
			// e.preventDefault();
			$(this).toggleClass('open');
		}
	);
	$(this).on('click', function (e) {

		var container = $(".floatingButton");
		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && $('.floatingButtonWrap').has(e.target).length === 0) {
			if (container.hasClass('open')) {
				container.removeClass('open');
			}
		}
		// if the target of the click isn't the container and a descendant of the menu
		// if(!container.is(e.target) && ($('.floatingMenu').has(e.target).length > 0)) 
		// {
		// 	$('.floatingButton').removeClass('open');
		// } 
	});

	//Acción al dar clic en Salir
	$('.close-container').on('click',
		function (e) {
			var re = new RegExp(/^.*\//);
			var urlBASE = re.exec(window.location.href)[0];
			window.open(urlBASE + "logout.php", "_self");
		}
	);

	//Acción al dar clic en usuarios en linea
	$('.online-container').on('click',
		function (e) {
			var re = new RegExp(/^.*\//);
			var urlBASE = re.exec(window.location.href)[0];
			window.open(urlBASE + "online-users.php", "_self");
		}
	);
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

			// Calculamos la diferencia entre originalPotion y la suma actual de la anchura y altura de la ventana
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

	// Cambiamos la clase en el cuerpo y llamamos a onKeyboardOnOff()
	$('body').toggleClass('view-withKeyboard', nowWithKeyboard);
	onKeyboardOnOff(nowWithKeyboard);
});

// Evento para detectar cambios en el tamaño de la ventana u orientación
$(window).on('resize orientationchange', function () {
	applyAfterResize();
});
// var $isMobile = false;
// $(document).ready(function(){
function isMobile() {
	if (window.matchMedia("(max-width: 767px)").matches) {
		// The viewport is less than 768 pixels wide
		// $isMobile = true;
		return true;
	} else {
		// The viewport is at least 768 pixels wide
		// $isMobile = false;
		return false;
	}
}

//Funtion to report online users
// function online() {
// 	// I am online
// 	var val_id = $('#my-id').val();
// 	$.ajax({
// 		type: 'POST',
// 		url: 'online.php',
// 		data: {
// 			val_id
// 		}
// 	});
// }

// Frontend JavaScript
// OnlineTracker class
class OnlineTracker {
    constructor(options = {}) {
        this.userId = options.userId;
        this.interval = options.interval || 30000; // 30 seconds default
        this.retryAttempts = options.retryAttempts || 3;
        this.retryDelay = options.retryDelay || 5000;
        this.endpoint = options.endpoint || 'online.php';
        this.currentRetry = 0;
        this.active = false;
    }

    start() {
        if (this.active) return;
        this.active = true;
        this.updateStatus();
        this.intervalId = setInterval(() => this.updateStatus(), this.interval);
        
        // Add event listeners for tab visibility and page unload
        document.addEventListener('visibilitychange', () => this.handleVisibilityChange());
        window.addEventListener('beforeunload', () => this.handleUnload());
    }

    stop() {
        this.active = false;
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    async updateStatus() {
        if (!this.active) return;

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
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

        } catch (error) {
            console.error('Error updating online status:', error);
            await this.handleError();
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
        // Attempt to send a final status update
        navigator.sendBeacon(this.endpoint, JSON.stringify({
            user_id: this.userId,
            status: 'offline'
        }));
    }
}

// Usage example:
const tracker = new OnlineTracker({
    userId: document.getElementById('my-id').value,
    interval: 3000, // 30 seconds
    retryAttempts: 3,
    endpoint: 'online.php'
});

// tracker.start();

//End of OnlineTracker class

//Hide and Show options to delete plates
function funcShow() {
	// $(".contFile.hidden").removeClass('hidden');
	$(".contFile").fadeIn("slow")
	$(".contTable").fadeOut("slow")
};
function funcHide() {
	// $(".cont").addClass('hidden');
	$(".contFile").fadeOut("slow")
	$(".contTable").fadeIn("slow")
};
function funcToggle() {
	// $(".cont").toggleClass('hidden');
	$(".cont").fadeToggle("slow");
};

