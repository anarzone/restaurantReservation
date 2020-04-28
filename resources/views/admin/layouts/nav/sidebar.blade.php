<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
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
                <li class="nav-small-cap"><span class="hide-menu">Applications</span></li>

                <li class="sidebar-item"> <a class="sidebar-link" href="{{route('admin.reservations.index')}}"
                                             aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                            class="hide-menu">Rezervasiyalar
                                </span></a>
                </li>
                <li class="list-divider"></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                             aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i><span
                            class="hide-menu">Restoran & Zal</span></a>
                    <ul aria-expanded="false" class="collapse  first-level base-level-line">
                        <li class="sidebar-item"><a href="{{route('admin.restaurants.index')}}" class="sidebar-link"><span
                                    class="hide-menu"> Zallar
                                        </span></a>
                        </li>
                        <li class="sidebar-item"><a href="{{route('admin.halls.create')}}" class="sidebar-link"><span
                                    class="hide-menu"> Yeni zal
                                        </span></a>
                        </li>
{{--                        <li class="sidebar-item"><a href="form-input-grid.html" class="sidebar-link"><span--}}
{{--                                    class="hide-menu"> Yeni restoran--}}
{{--                                        </span></a>--}}
{{--                        </li>--}}
                    </ul>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">İstifadəçi paneli</span></li>

{{--                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="authentication-login1.html"--}}
{{--                                             aria-expanded="false"><i data-feather="lock" class="feather-icon"></i><span--}}
{{--                            class="hide-menu">Login--}}
{{--                                </span></a>--}}
{{--                </li>--}}
{{--                <li class="sidebar-item"> <a class="sidebar-link sidebar-link"--}}
{{--                                             href="authentication-register1.html" aria-expanded="false"><i data-feather="lock"--}}
{{--                                                                                                           class="feather-icon"></i><span class="hide-menu">Register--}}
{{--                                </span></a>--}}
{{--                </li>--}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
