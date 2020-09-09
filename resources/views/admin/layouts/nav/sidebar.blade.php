<aside class="left-sidebar" data-sidebarbg="skin6">
  <!-- Sidebar scroll-->
  <div class="scroll-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
      <ul id="sidebarnav">
        <li class="sidebar-item"> <a class="sidebar-link sidebar-link active" href="{{route('manage.dashboard')}}"
          aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
          class="hide-menu">Dashboard</span></a></li>
         <li class="list-divider"></li>
        <li class="sidebar-item">
            <a class="sidebar-link" href="{{route('manage.reservations.index')}}"
                    aria-expanded="false"><i data-feather="tag" class="feather-icon"></i>
                <span class="hide-menu">Rezervasiyalar</span>
            </a>
        </li>
          <li class="sidebar-item">
              <a class="sidebar-link" href="{{route('manage.reservations.form')}}"
                 aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i>
                  <span class="hide-menu">Rezervasiya formu</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link" href="{{route('manage.customers.index')}}"
                 aria-expanded="false"><i data-feather="briefcase" class="feather-icon"></i>
                  <span class="hide-menu">Müştərilər</span>
              </a>
          </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{route('manage.reservations.archive')}}" aria-expanded="false">
            <i data-feather="archive" class="feather-icon"></i>
            <span
            class="hide-menu">Arxiv
          </span>
        </a>
      </li>
      @role('super-admin')
      <li class="list-divider"></li>
      <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
        aria-expanded="false"><i data-feather="list" class="feather-icon"></i><span
        class="hide-menu">Restoran & Zal</span></a>
        <ul aria-expanded="false" class="collapse  first-level base-level-line">
          <li class="sidebar-item"><a href="{{route('manage.restaurants.index')}}" class="sidebar-link"><span
            class="hide-menu"> Masalar
          </span></a>
        </li>
        <li class="sidebar-item"><a href="{{route('manage.restaurants.list')}}" class="sidebar-link"><span
          class="hide-menu"> Restoranlar
        </span></a>
        </li>
      <li class="sidebar-item"><a href="{{route('manage.halls.index')}}" class="sidebar-link"><span
        class="hide-menu"> Zallar
      </span></a>
    </li>

    <li class="sidebar-item"><a href="{{route('manage.plan.images.new')}}" class="sidebar-link"><span
      class="hide-menu"> Plan
    </span></a>
    </li>
</ul>
</li>
<li class="list-divider"></li>
<li class="nav-small-cap"><span class="hide-menu">İstifadəçi paneli</span></li>

<li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('manage.users.create') }}"
  aria-expanded="false"><i data-feather="user-plus" class="feather-icon"></i><span
  class="hide-menu">Yeni İstifadəçi Yarat
</span></a>
</li>
<li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('manage.users.index')}}"
  aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
  class="hide-menu">İstifadəçilər
</span></a>
</li>

<li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
  aria-expanded="false"><i data-feather="sliders" class="feather-icon"></i><span
  class="hide-menu">Qrup & Rol</span></a>
  <ul aria-expanded="false" class="collapse  first-level base-level-line">
    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('manage.groups.index')}}"
      aria-expanded="false"><i data-feather="layers" class="feather-icon"></i><span
      class="hide-menu">Qruplar
    </span></a>
  </li>
  <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('manage.groups.create')}}"
    aria-expanded="false"><i data-feather="plus-square" class="feather-icon"></i><span
    class="hide-menu">Yeni Qrup
  </span></a>
</li>
    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('manage.roles.index')}}"
      aria-expanded="false"><i data-feather="key" class="feather-icon"></i><span
      class="hide-menu">Rollar
    </span></a>
    </li>
</li>
</ul>
</li>
@endrole
</ul>
</nav>
<!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
