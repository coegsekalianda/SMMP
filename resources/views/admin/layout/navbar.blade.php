<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="/admin/dashboard">
                <img src="{{ asset('/assets/img/logo_unila.png')}}" alt="logo" /> SMMP
            </a>
            <a class="navbar-brand brand-logo-mini" href="/dashboard">
                <img style="max-height:39px; height: auto" src="{{ asset('/assets/img/logo_unila.png')}}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold text-capitalize">
                        @php
                        $user = DB::table('users')
                        ->where('id', '=', Auth::user()->id)
                        ->first();
                        $nama = $user->name;
                        $email = $user->email;
                        $img = $user->img;
                        @endphp
                        {{$nama}}
                    </span></h1>
                <h3 class="welcome-sub-text">Apa kabar hari ini?</h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-lg-block">
                <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                    <span class="input-group-addon input-group-prepend border-right">
                        <span class="icon-calendar input-group-text calendar-icon"></span>
                    </span>
                    <input style="background-color:white" disabled="disabled" type="text" class="form-control">
                </div>
            </li>
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="{{ asset('/assets/img/pp/' . $img)}}" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="{{ asset('/assets/img/pp/' . $img)}}" alt="Profile image" style="width:41px; height:40px;" alt="Profile image">
                        <p class="mb-1 mt-3 font-weight-semibold">{{$nama}}</p>
                        <p class="fw-light text-muted mb-0">{{$email}}</p>
                    </div>
                    <a href="/profile" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <dropdown-link :href="route('logout')" onclick="event.preventDefault();
                            this.closest('form').submit();" style="color:black">
                            <div class=" dropdown-item">
                                <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>
                                {{ __('Log Out') }}
                            </div>
                        </dropdown-link>

                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>