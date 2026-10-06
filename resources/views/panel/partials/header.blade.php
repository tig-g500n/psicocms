@php
    $usuaria = auth()->user();
    $iniciales = strtoupper(mb_substr($usuaria->nombre ?? 'P', 0, 1).mb_substr($usuaria->apellidos ?? '', 0, 1));
@endphp

<header class="dash__header">
    <button type="button" class="head__burger" data-sidebar-toggle aria-label="Abrir menú">
        <i class="fa-solid fa-bars"></i>
    </button>

    <form class="head__search" method="GET" action="{{ route('panel.buscar') }}" role="search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" name="q" value="{{ request()->routeIs('panel.buscar') ? request('q') : '' }}" placeholder="Buscar pacientes, citas, artículos..." aria-label="Buscar">
    </form>

    <div class="head__actions">
        <div class="head__dropdown-wrap" data-dropdown>
            <button type="button" class="head__icon-btn" data-dropdown-toggle aria-label="Notificaciones">
                <i class="fa-regular fa-bell"></i>
                <span class="head__badge-dot"></span>
            </button>
            <div class="head__dropdown" data-dropdown-menu>
                <div class="head__dropdown-header">
                    <span class="head__dropdown-title">Notificaciones</span>
                </div>
                <div class="head__dropdown-empty">
                    <i class="fa-regular fa-bell-slash" style="font-size:2.4rem;display:block;margin-bottom:1rem;"></i>
                    No tienes notificaciones nuevas.
                </div>
            </div>
        </div>

        <a href="{{ route('panel.ayuda') }}" class="head__icon-btn" aria-label="Ayuda" title="Centro de ayuda">
            <i class="fa-regular fa-circle-question"></i>
        </a>

        <div class="head__dropdown-wrap" data-dropdown>
            <button type="button" class="head__user" data-dropdown-toggle>
                <span class="head__avatar">
                    @if ($usuaria->avatar)
                        <img src="{{ asset($usuaria->avatar) }}" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                    @else
                        {{ $iniciales }}
                    @endif
                </span>
                <span class="head__user-info">
                    <span class="head__user-name">{{ $usuaria->nombre }} {{ $usuaria->apellidos }}</span>
                    <span class="head__user-role">Psicóloga</span>
                </span>
                <i class="fa-solid fa-chevron-down" style="font-size:1.1rem;color:var(--dash-text-soft);"></i>
            </button>
            <div class="head__dropdown" data-dropdown-menu>
                <div class="head__dropdown-header">
                    <span class="head__dropdown-title">{{ $usuaria->nombre }} {{ $usuaria->apellidos }}</span>
                </div>
                <a href="{{ route('panel.perfil.edit') }}" class="head__dropdown-item">
                    <i class="fa-solid fa-user"></i> Mi perfil
                </a>
                <button type="button" class="head__dropdown-item" data-apariencia-open>
                    <i class="fa-solid fa-circle-half-stroke"></i> Apariencia del panel
                </button>
                <form method="POST" action="{{ route('acceso.logout') }}">
                    @csrf
                    <button type="submit" class="head__dropdown-item head__dropdown-item--danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
