@extends('layouts.app')

@section('content')
<div class="form-wrapper-table col-lg-8 col-md-10 col-sm-12 col-xs-12">
    <div class="col-lg-12">
        <div>
            <div class="row justify-content-center" style="color: black; text-shadow: 0 0 2px #FFF, 0 0 50px #000;">
                <h4>CONECTADOS</h4>
            </div>
            <div class="panel-body scroll-div">
                <main class="leaderboard__profiles" id="leaderboard__profiles">
                    <!-- Aquí se cargarán dinámicamente los usuarios -->
                </main>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
class OnlineUsersList {
    constructor(options = {}) {
        this.container = options.container || '#leaderboard__profiles';
        this.interval = options.interval || 3000;
        this.endpoint = options.endpoint || '{{ route("online.fetch") }}';
        this.defaultAvatar = options.defaultAvatar || '{{ asset("img/usuario.png") }}';
        this.active = false;
    }

    start() {
        if (this.active) return;
        this.active = true;
        this.fetchUsers();
        this.intervalId = setInterval(() => this.fetchUsers(), this.interval);
    }

    stop() {
        this.active = false;
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    async fetchUsers() {
        if (!this.active) return;

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const users = await response.json();
            this.renderUsers(users);

        } catch (error) {
            console.error('Error fetching online users:', error);
        }
    }

    renderUsers(users) {
        const html = users.map((user, index) => {
            const role = user.role > 1 ? 'U' : 'A';
            return `
                <article class="leaderboard__profile">
                    <img src="${this.defaultAvatar}" 
                        alt="${this.escapeHtml(user.name)}" 
                        class="leaderboard__picture">
                    <span class="leaderboard__name">${this.escapeHtml(user.name)}</span>
                    <span class="leaderboard__value">
                        ${index + 1}<span>${role}</span>
                    </span>
                </article>
            `;
        }).join('');

        document.querySelector(this.container).innerHTML = users.length > 0 ? 
            html : '<p class="text-center">No hay usuarios conectados actualmente</p>';
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

class OnlineTracker {
    constructor(options = {}) {
        this.interval = options.interval || 30000;
        this.retryAttempts = options.retryAttempts || 3;
        this.retryDelay = options.retryDelay || 5000;
        this.endpoint = options.endpoint || '{{ route("online.update") }}';
        this.currentRetry = 0;
        this.active = false;
    }

    start() {
        if (this.active) return;
        this.active = true;
        this.updateStatus();
        this.intervalId = setInterval(() => this.updateStatus(), this.interval);
        
        // Agregar event listeners para visibilidad de la pestaña y cierre de la página
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
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    timestamp: new Date().toISOString()
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Reiniciar contador de reintentos en caso de éxito
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
        // Si todos los reintentos fallan, detener el tracker
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
        // Intentar enviar una actualización final de estado
        navigator.sendBeacon(this.endpoint, JSON.stringify({
            status: 0,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }));
    }
}

// Inicializar ambos componentes cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el rastreador de estado en línea
    const tracker = new OnlineTracker({
        interval: 3000
    });
    
    // Inicializar la lista de usuarios en línea
    const usersList = new OnlineUsersList({
        container: '#leaderboard__profiles',
        interval: 3000
    });
    
    // Iniciar ambos componentes
    tracker.start();
    usersList.start();
});
</script>
@endpush