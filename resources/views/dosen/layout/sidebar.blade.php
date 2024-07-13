<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/dosen/dashboard">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#rps" aria-expanded="false" aria-controls="rps">
                <i class="menu-icon mdi mdi-view-headline"></i>
                <span class="menu-title">RPS</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="rps">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="/dosen/rps/add-rps">Tambah RPS Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="/dosen/rps/list-rps">Lihat Daftar RPS</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#cpl" aria-expanded="false" aria-controls="cpl">
                <i class="menu-icon mdi mdi-checkbox-multiple-blank"></i>
                <span class="menu-title">CPLMK</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="cpl">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('cplmk-add')}}">Tambah CPLMK Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('cplmk-list')}}">Lihat Daftar CPLMK</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#cpmk" aria-expanded="false" aria-controls="cpmk">
                <i class="menu-icon mdi mdi-message-text"></i>
                <span class="menu-title">CPMK</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="cpmk">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('cpmk-add')}}">Tambah CPMK Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="/dosen/cpmk/list-cpmk">Lihat Daftar CPMK</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#activities" aria-expanded="false" aria-controls="activities">
                <i class="menu-icon mdi mdi-checkbox-multiple-blank-outline"></i>
                <span class="menu-title">Activities</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="activities">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('activities-add')}}">Tambah Activities Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('activities-list')}}">Lihat Daftar Activities</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#komponen" aria-expanded="false" aria-controls="komponen">
                <i class="menu-icon mdi mdi-message-text-outline"></i>
                <span class="menu-title">Komponen</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="komponen">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('Jenis-add')}}">Tambah Jenis Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('Jenis-list')}}">Lihat Komponen</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#soal" aria-expanded="false" aria-controls="soal">
                <i class="menu-icon mdi mdi-pen"></i>
                <span class="menu-title">Soal</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="soal">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('soal-addRaw')}}">Tambah Soal Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('soal-list')}}">Lihat Daftar Soal</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#excel" aria-expanded="false" aria-controls="excel">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Penilaian Dengan Soal</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="excel">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('add-mutu')}}">Download Template</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('import-mutu')}}">Import Nilai</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#excel1" aria-expanded="false" aria-controls="excel1">
                <i class="menu-icon mdi mdi-file-document-box"></i>
                <span class="menu-title">Penilaian Tanpa Soal</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="excel1">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('add-TanpaSoal')}}">Download Template</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('TanpaSoal')}}">Import Nilai</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#visualisasi" aria-expanded="false" aria-controls="visualisasi">
                <i class="menu-icon mdi mdi-card-text-outline"></i>
                <span class="menu-title">Visualisasi</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="visualisasi">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{route('visual-mahasiswa')}}">Per Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('visual-mahasiswaAngkatan')}}">Per Angkatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('visual-mahasiswaMataKuliah')}}">Per Mata Kuliah</a></li>
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
