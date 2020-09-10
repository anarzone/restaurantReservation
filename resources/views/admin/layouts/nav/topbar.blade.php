<header class="topbar" data-navbarbg="skin6">
  <nav class="navbar top-navbar navbar-expand-md">
    <div class="navbar-header" data-logobg="skin6">
      <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
        class="ti-menu ti-close"></i></a>
        <div class="navbar-brand">
          <!-- Logo icon -->
          <a href="/manage">
              <img width="150" src="{{asset('back/assets/images/logo.png')}}" alt="">
          </a>
        </div>
        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
        data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
        class="ti-more"></i></a>
      </div>
      <div class="navbar-collapse collapse" id="navbarSupportedContent">
        <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
        </ul>
        <ul class="navbar-nav float-right">

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
             <span
            class="text-dark">{{Auth::user()->name}}</span> <i data-feather="chevron-down"
            class="svg-icon"></i></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
            <a class="dropdown-item" href="{{route('manage.users.profile')}}"><i data-feather="user"
              class="svg-icon mr-2 ml-1"></i>
              Profil</a>

              <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"><i data-feather="power"
              class="svg-icon mr-2 ml-1"></i>
              Çıxış</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </li>
          <!-- ============================================================== -->
          <!-- User profile and search -->
          <!-- ============================================================== -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- ============================================================== -->
  <!-- End Topbar header -->
  <!-- ============================================================== -->
