<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa-solid fa-futbol"></i>VISCA BARCA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('map') }}">Map</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}">Table</a>
                </li>



                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Data
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('api.points') }}" target="_blank">Points</a></li>
                        <li><a class="dropdown-item" href="{{ route('api.polylines') }}" target="_blank">Polylines</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('api.polygons') }}" target="_blank">Polygons</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
            @auth
                <li class="nav-item">
                    <a href="{{ route('login') }}">

                        <span class="nav-link">
                            Dashboard
                        </span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                   <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                             </a>
                    </form>
                </li> --}}
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        Log in
                    </a>
                </li>
            @endauth
        </ul>
        </div>
    </div>
</nav>
