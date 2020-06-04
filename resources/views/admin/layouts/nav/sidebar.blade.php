<aside class="left-sidebar" data-sidebarbg="skin6">
  <!-- Sidebar scroll-->
  <div class="scroll-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
      <ul id="sidebarnav">
        <li class="sidebar-item"> <a class="sidebar-link sidebar-link active" href="{{route('admin.dashboard')}}"
          aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
          class="hide-menu">Dashboard</span></a></li>
          <li class="list-divider"></li>
          <li class="nav-small-cap"><span class="hide-menu">Rezervasiya</span></li>
        <li class="sidebar-item">
            <a class="sidebar-link" href="{{route('admin.reservations.index')}}"
                    aria-expanded="false"><i data-feather="tag" class="feather-icon"></i>
                <span class="hide-menu">Rezervasiyalar</span>
            </a>
        </li>
          <li class="sidebar-item">
              <a class="sidebar-link" href="{{route('admin.reservations.form')}}"
                 aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i>
                  <span class="hide-menu">Rezervasiya formu</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link" href="{{route('admin.customers.index')}}"
                 aria-expanded="false"><i data-feather="briefcase" class="feather-icon"></i>
                  <span class="hide-menu">Müştərilər</span>
              </a>
          </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{route('admin.reservations.archive')}}" aria-expanded="false">
            <i data-feather="archive" class="feather-icon"></i>
            <span
            class="hide-menu">Arxiv
          </span>
        </a>
      </li>
      <li class="list-divider"></li>
      <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
        aria-expanded="false"><i data-feather="list" class="feather-icon"></i><span
        class="hide-menu">Restoran & Zal</span></a>
        <ul aria-expanded="false" class="collapse  first-level base-level-line">
          <li class="sidebar-item"><a href="{{route('admin.restaurants.index')}}" class="sidebar-link"><span
            class="hide-menu"> Masalar
          </span></a>
        </li>
        <li class="sidebar-item"><a href="{{route('admin.restaurants.list')}}" class="sidebar-link"><span
          class="hide-menu"> Restoranlar
        </span></a>
        </li>
      <li class="sidebar-item"><a href="{{route('admin.halls.index')}}" class="sidebar-link"><span
        class="hide-menu"> Zallar
      </span></a>
    </li>
    @hasanyrole('manager|supervisor|super-admin')
    <li class="sidebar-item"><a href="{{route('admin.plan.images.new')}}" class="sidebar-link"><span
      class="hide-menu"> Plan
    </span></a>
    </li>
@endhasanyrole
</ul>
</li>
@hasanyrole('manager|supervisor|super-admin')
<li class="list-divider"></li>
<li class="nav-small-cap"><span class="hide-menu">İstifadəçi paneli</span></li>

<li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('admin.users.create') }}"
  aria-expanded="false"><i data-feather="user-plus" class="feather-icon"></i><span
  class="hide-menu">Yeni İstifadəçi Yarat
</span></a>
</li>
<li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('admin.users.index')}}"
  aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
  class="hide-menu">İstifadəçilər
</span></a>
</li>

<li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
  aria-expanded="false"><i data-feather="sliders" class="feather-icon"></i><span
  class="hide-menu">Qrup & Rol</span></a>
  <ul aria-expanded="false" class="collapse  first-level base-level-line">
    <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('admin.groups.index')}}"
      aria-expanded="false"><i data-feather="layers" class="feather-icon"></i><span
      class="hide-menu">Qruplar
    </span></a>
  </li>
  <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('admin.groups.create')}}"
    aria-expanded="false"><i data-feather="plus-square" class="feather-icon"></i><span
    class="hide-menu">Yeni Qrup
  </span></a>
</li>
<li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('admin.roles.index')}}"
  aria-expanded="false"><i data-feather="key" class="feather-icon"></i><span
  class="hide-menu">Rollar
</span></a>
</li>
{{--                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{route('admin.roles.create')}}"--}}
{{--                                                     aria-expanded="false"><i data-feather="plus" class="feather-icon"></i><span--}}
{{--                                    class="hide-menu">Yeni Rol--}}
{{--                                        </span></a>--}}
</li>
</ul>
</li>
@endhasanyrole
</ul>
</nav>
<!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
