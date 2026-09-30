<!-- Sidebar -->
<style>
    .sidebar-menu ul li a.active {
        background-color: rgba(13, 110, 253, 0.15) !important;
        color: #0d6efd !important;
        font-weight: 600;
        border-left: 3px solid #0d6efd;
    }
    .sidebar-menu ul li a.active i {
        color: #0d6efd !important;
    }
</style>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('home-admission') }}" class="d-flex align-items-center border bg-white rounded p-2 mb-4">
                        <img src="{{ URL::asset('logo.jpg') }}" class="avatar avatar-md img-fluid rounded" alt="ScholarApp">
                        <span class="text-dark ms-2 fw-normal">Admission IESA</span>
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <h6 class="submenu-hdr"><span>Menu</span></h6>
                    <ul>
                        <li>
                            <a href="{{ route('home-admission') }}" class="{{ request()->routeIs('home-admission') ? 'active' : '' }}">
                                <i class="ti ti-layout-dashboard"></i>
                                <span>Home admission</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admission?step=1&new=1') }}" class="{{ request()->routeIs('admission') ? 'active' : '' }}">
                                <i class="ti ti-plus"></i>
                                <span>New admission</span>
                            </a>
                        </li>
                         <!--<li>
                            <a href="#">
                                <i class="ti ti-help"></i>
                                <span>Help</span>
                            </a>
                        </li> -->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
