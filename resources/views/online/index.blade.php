@extends('layouts.app')

@section('content')
<!-- Botón de Regreso iOS -->
<a href="{{ route('users.index') }}" class="ios-back-btn" title="Volver a Usuarios">
    <i class="fas fa-chevron-left"></i>
    <span>Usuarios</span>
</a>

<div class="row online-main-row">
    <div class="col-lg-8 col-md-10 col-sm-12 mx-auto">
        <div class="online-header-ios">
            <h1 class="ios-title">Conectados</h1>
            <p class="ios-subtitle">Usuarios activos en tiempo real</p>
        </div>

        <div class="online-list-wrapper" id="leaderboard__profiles">
            <!-- Cargando... -->
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #000 !important;
    }

    .online-main-row {
        margin-top: 60px;
        padding-bottom: 100px;
    }

    /* Botón Back Estilo iOS */
    .ios-back-btn {
        position: fixed;
        top: 25px;
        left: 20px;
        display: flex;
        align-items: center;
        gap: 5px;
        color: #007aff !important;
        text-decoration: none !important;
        font-size: 17px;
        font-weight: 400;
        z-index: 1000;
        transition: opacity 0.2s;
    }

    .ios-back-btn:active {
        opacity: 0.5;
    }

    .ios-back-btn i {
        font-size: 20px;
    }

    /* Cabecera iOS */
    .online-header-ios {
        padding: 0 15px 25px 15px;
        text-align: left;
    }

    .ios-title {
        color: #fff;
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 5px;
        letter-spacing: -0.5px;
    }

    .ios-subtitle {
        color: rgba(255, 255, 255, 0.5);
        font-size: 15px;
    }

    /* Lista de Usuarios */
    .online-list-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1px; /* Separación mínima para simular divisiones */
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Tarjeta de Usuario iOS */
    .ios-user-card {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: rgba(30,30,30, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: background 0.2s;
        text-decoration: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .ios-user-card:last-child {
        border-bottom: none;
    }

    .ios-user-card:active {
        background: rgba(255, 255, 255, 0.1);
    }

    .ios-avatar-wrapper {
        position: relative;
        margin-right: 15px;
    }

    .ios-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        background: rgba(255, 255, 255, 0.1);
        padding: 2px;
    }

    .ios-status-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #34c759;
        border: 2px solid #1c1c1e;
        border-radius: 50%;
    }

    .ios-user-info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .ios-user-name {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .ios-user-role {
        color: rgba(255, 255, 255, 0.5);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ios-role-badge {
        font-size: 10px;
        text-transform: uppercase;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
    }

    .badge-admin { background: rgba(255, 59, 48, 0.2); color: #ff3b30; }
    .badge-user { background: rgba(0, 122, 255, 0.2); color: #007aff; }

    .ios-chevron-right {
        color: rgba(255, 255, 255, 0.2);
        font-size: 14px;
    }
</style>
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
        const html = users.map((user) => {
            const isAdmin = user.role == 1;
            const roleText = isAdmin ? 'Administrador' : 'Usuario';
            const badgeClass = isAdmin ? 'badge-admin' : 'badge-user';

            return `
                <div class="ios-user-card">
                    <div class="ios-avatar-wrapper">
                        <img src="${this.defaultAvatar}" class="ios-avatar" alt="${this.escapeHtml(user.name)}">
                        <span class="ios-status-indicator"></span>
                    </div>
                    <div class="ios-user-info">
                        <span class="ios-user-name">${this.escapeHtml(user.name)}</span>
                        <div class="ios-user-role">
                            <span class="ios-role-badge ${badgeClass}">${isAdmin ? 'A' : 'U'}</span>
                            <span>${roleText} • @${this.escapeHtml(user.username || 'usuario')}</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right ios-chevron-right"></i>
                </div>
            `;
        }).join('');

        document.querySelector(this.container).innerHTML = users.length > 0 ? 
            html : '<div class="p-5 text-center text-muted"><p>No hay usuarios conectados</p></div>';
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
        this.endpoint = options.endpoint || '{{ route("online.update") }}';
        this.active = false;
    }

    start() {
        if (this.active) return;
        this.active = true;
        this.updateStatus();
        this.intervalId = setInterval(() => this.updateStatus(), this.interval);
    }

    stop() {
        this.active = false;
        if (this.intervalId) clearInterval(this.intervalId);
    }

    async updateStatus() {
        if (!this.active) return;
        try {
            await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ timestamp: new Date().toISOString() })
            });
        } catch (error) { console.error('Error updating status:', error); }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tracker = new OnlineTracker({ interval: 3000 });
    const usersList = new OnlineUsersList({ interval: 3000 });
    tracker.start();
    usersList.start();
});
</script>
@endpush