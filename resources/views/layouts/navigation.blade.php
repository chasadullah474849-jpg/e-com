<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

        <!-- DISPLAY UPLOADED PROFILE IMAGE HERE -->
        @if(Auth::check() && Auth::user()->image)
            <img src="{{ asset('storage/' . Auth::user()->image) }}"
                 alt="{{ Auth::user()->name }}"
                 class="rounded-circle"
                 style="width: 32px; height: 32px; object-fit: cover;">
        @else
            <i class="fa-regular fa-user"></i>
        @endif

        <span>{{ Auth::check() ? Auth::user()->name : 'ACCOUNT' }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        @auth
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                </form>
            </li>
        @else
            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
            <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
        @endauth
    </ul>
</li>
