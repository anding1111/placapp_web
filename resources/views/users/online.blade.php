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
                    <!-- Los usuarios se cargarán mediante AJAX -->
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
            this.csrfToken = '{{ csrf_token() }}';
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
                        'X-CSRF-TOKEN': this.csrfToken
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

            document.querySelector(this.container).innerHTML = html || '<p>No hay usuarios en línea</p>';
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

    document.addEventListener('DOMContentLoaded', () => {
        const usersList = new OnlineUsersList({
            container: '#leaderboard__profiles',
            interval: 3000,
            endpoint: '{{ route("online.fetch") }}'
        });
        
        usersList.start();
    });
</script>
@endpush