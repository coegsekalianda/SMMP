<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/penjamin-mutu/dashboard">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#soal" aria-expanded="false" aria-controls="soal">
                <i class="menu-icon mdi mdi-comment-question-outline"></i>
                <span class="menu-title">Soal Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="soal">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="/penjamin-mutu/list-soal"> List Soal </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#profil" aria-expanded="false" aria-controls="profil">
                <i class="menu-icon mdi mdi-card-text-outline"></i>
                <span class="menu-title">Profil Lulusan</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="profil">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('indexListProfil')}}">List Profil Lulusan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('indexListProfilCpl')}}">List Profil Kompetensi</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>