<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">BarangayConnect</div>
    </div>

    <nav class="sidebar-nav">


        <ul class="nav-menu">
            @if(Auth::user()->hasRole('admin'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle no-arrow d-flex justify-content-between align-items-center" data-bs-target="#dashboardSubmenu" aria-controls="dashboardSubmenu" href="#" role="button" data-bs-toggle="collapse"  aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span class="nav-text">DASHBOARD</span>
                        </div>
                        <i class="fa-solid fa-chevron-down custom-arrow float-end"></i>
                    </a>
                    <div class="collapse" id="dashboardSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                    <span class="nav-text">ADMIN DASHBOARD</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('committee.dashboard') }}" class="nav-link">
                                    <span class="nav-text">COMMITTEE DASHBOARD</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @else
                @can('view committee_dashboard')
                <li class="nav-item">
                    <a href="{{ route('committee.dashboard') }}" class="nav-link">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span class="nav-text">COMMITTEE DASHBOARD</span>
                    </a>
                </li>
                @endcan
            @endif

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-arrow d-flex justify-content-between align-items-center" data-bs-target="#rbiSubmenu" aria-controls="rbiSubmenu" href="#" role="button" data-bs-toggle="collapse"  aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-rectangle-list"></i>
                        <span class="nav-text">REGISTRY OF BRGY. INHABITANTS (RBI)</span>
                    </div>
                    <i class="fa-solid fa-chevron-down custom-arrow"></i>
                </a>
                <div class="collapse" id="rbiSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.resident.index') }}" class="nav-link">
                                <i class="fa-solid fa-users"></i>
                                <span class="nav-text">RESIDENTS</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.household.index') }}" class="nav-link">
                                <i class="fa-solid fa-house"></i>
                                <span class="nav-text">HOUSEHOLDS</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-arrow d-flex justify-content-between align-items-center" data-bs-target="#certificateSubmenu" aria-controls="certificateSubmenu" href="#" role="button" data-bs-toggle="collapse" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-file-lines"></i>
                        <span class="nav-text">CERTIFICATE REQUESTS</span>
                    </div>
                    <i class="fa-solid fa-chevron-down custom-arrow"></i>
                </a>
                <div class="collapse" id="certificateSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.certificate-requests.index') }}" class="nav-link">
                                <span class="nav-text">View requests</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.certificates.indigency.create') }}" class="nav-link">
                                <span class="nav-text">Generate Certificate of Indigency</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @can('view officials')
            <li class="nav-item">
                <a href="{{ route('admin.official.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="nav-text">OFFICIALS</span>
                </a>
            </li>
            @endcan

            @can('view committees')
            <li class="nav-item">
                <a href="{{ route('admin.committee.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="nav-text">COMMITTEES</span>
                </a>
            </li>
            @endcan

            @can('manage users')
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="nav-text">USER ACCOUNTS</span>
                </a>
            </li>
            @endcan

            @can('manage users')
            <li class="nav-item">
                <a href="{{ route('admin.verification-requests.index') }}" class="nav-link">
                    <i class="fa-solid fa-id-card"></i>
                    <span class="nav-text">LINK VERIFICATION REQUESTS</span>
                </a>
            </li>
            @endcan

            @can('manage users')
            <li class="nav-item">
                <a href="{{ route('admin.announcements.index') }}" class="nav-link">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span class="nav-text">ANNOUNCEMENTS</span>
                </a>
            </li>
            @endcan

            @can('manage user_permissions')
            <li class="nav-item">
                <a href="{{ route('admin.roles-permissions.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-shield"></i>
                    <span class="nav-text">ROLES PERMISSIONS</span>
                </a>
            </li>
            @endcan

            @can('view health_sanitation')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-arrow d-flex justify-content-between align-items-center" data-bs-target="#healthSubmenu" aria-controls="healthSubmenu" href="#" role="button" data-bs-toggle="collapse"  aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <span class="nav-text">HEALTH</span>
                    </div>
                    <i class="fa-solid fa-chevron-down custom-arrow"></i>
                </a>
                <div class="collapse" id="healthSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fa-solid fa-shield-virus"></i>
                                <span class="nav-text">Immunization for Infants</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fa-solid fa-tablets"></i>
                                <span class="nav-text">Medicine Inventory</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endcan
        </ul>
    </nav>

    <nav class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout-btn btn btn-danger" type="submit">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>
    </nav>
</aside>
