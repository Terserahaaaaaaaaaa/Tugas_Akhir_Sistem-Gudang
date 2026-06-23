<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.html" aria-label="adminHMD dashboard">
          <div class="sidebar-header">
              <a class="brand-mark" href="/admin" aria-label="Dashboard">

                  <img src="{{ asset('template/assets/img/logo-perusahaan.png') }}"
                      alt="Logo"
                      width="45"
                      height="45">

                  <span class="brand-copy">
                      <span class="brand-title">SISTEM GUDANG</span>
                      <span class="brand-subtitle">PT Muara Kayu Sengon</span>
                  </span>

              </a>
          </div>
      <nav class="sidebar-nav">
        <a class="nav-link active" href="{{ route('home') }}" aria-current="page">
          <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(Auth::user()->role == 'admin') --}}
        <li class="nav-item">
            <a href="{{ route('user.index') }}" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Data User</span>
            </a>
        </li>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(Auth::user()->role == 'admin') --}}
        <a class="nav-link {{ request()->routeIs('kategori-barang.*') ? 'active' : '' }}"
        href="{{ route('kategori-barang.index') }}">
            <span class="nav-icon">
                <i class="bi bi-tags"></i>
            </span>
            <span class="nav-text">Kategori Barang</span>
        </a>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(in_array(Auth::user()->role, ['admin', 'logistik', 'keuangan'])) --}}
        <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
            <span class="nav-icon">
                <i class="bi bi-box-seam"></i>
            </span>
            <span class="nav-text">Barang</span>
        </a>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        @if(in_array(Auth::user()->role, ['admin', 'keuangan', 'logistik']))
        <a class="nav-link {{ request()->routeIs('stok-barang.*') ? 'active' : '' }}" href="{{ route('stok-barang.index') }}">
            <span class="nav-icon">
                <i class="bi bi-boxes"></i>
            </span>
            <span class="nav-text">Stok Barang</span>
        </a>
        @endif

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(Auth::user()->role == 'logistik') --}}
        <a class="nav-link {{ request()->routeIs('stok-opname.*') ? 'active' : '' }}"
        href="{{ route('stok-opname.index') }}">
            <span class="nav-icon">
                <i class="bi bi-clipboard-data"></i>
            </span>
            <span class="nav-text">Stok Opname</span>
        </a>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(in_array(Auth::user()->role, ['logistik', 'admin'])) --}}
        <a class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}" href="{{ route('barang-masuk.index') }}">
            <span class="nav-icon">
                <i class="bi bi-box-arrow-in-down"></i>
            </span>
            <span class="nav-text">Barang Masuk</span>
        </a>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(in_array(Auth::user()->role, ['logistik', 'admin'])) --}}
        <a class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}" href="{{ route('barang-keluar.index') }}">
            <span class="nav-icon">
                <i class="bi bi-box-arrow-up"></i>
            </span>
            <span class="nav-text">Barang Keluar</span>
        </a>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(in_array(Auth::user()->role, ['logistik', 'admin'])) --}}
        <a class="nav-link {{ request()->routeIs('permintaan-barang.*') ? 'active' : '' }} "href="{{ route('permintaan-barang.index') }}">
            <span class="nav-icon">
                <i class="bi bi-clipboard-check"></i>
            </span>
            <span class="nav-text">Permintaan Barang</span>
        </a>
        {{-- @endif --}}

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(in_array(Auth::user()->role, ['admin', 'keuangan'])) --}}
        <a class="nav-link {{ request()->routeIs('pengajuan-po.*') ? 'active' : '' }}" href="{{ route('pengajuan-po.index') }}">
            <span class="nav-icon">
                <i class="bi bi-file-earmark-text"></i>
            </span>
            <span class="nav-text">Pengajuan PO</span>
        </a>
        {{-- @endif --}}

        {{-- dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih
        @if(Auth::user()->role == 'keuangan')
        <a class="nav-link {{ request()->routeIs('approval_po.*') ? 'active' : '' }}" href="{{ route('approval_po.index') }}">
            <span class="nav-icon">
                <i class="bi bi-file-earmark-text"></i>
            </span>
            <span class="nav-text">Approval PO</span>
        </a>
        @endif --}}
        

        {{--dibungkus @if untuk pemisah role sebenernya untuk menyembunyikan fitur barang dari role selain yang dipilih--}}
        {{-- @if(in_array(Auth::user()->role, ['admin', 'keuangan', 'pimpinan'])) --}}
        <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.menu') }}">
            <span class="nav-icon">
                <i class="bi bi-file-bar-graph"></i>
            </span>
            <span class="nav-text">Laporan</span>
        </a>
        {{-- @endif --}}
      </nav>

      <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="../template/assets/images/avatar/avatar.jpg" alt="Admin Hasan">
        <strong>{{ Auth::user()->name }}</strong>
        <small>{{ ucfirst(Auth::user()->role) }}</small>
      </div>
    </aside>