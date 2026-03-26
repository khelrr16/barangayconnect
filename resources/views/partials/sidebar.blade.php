<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">BarangayConnect</div>
        <div class="text-center">{{ auth()->user()->name }} (Admin)</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            @if(Auth::user()->hasRole('admin'))
            <li class="nav-item">
                <a href="{{ route('admin.index') }}" class="nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            @endif

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-arrow d-flex justify-content-between align-items-center" data-bs-target="#rbiSubmenu" aria-controls="rbiSubmenu" href="#" role="button" data-bs-toggle="collapse"  aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-rectangle-list"></i>
                        <span class="nav-text">Registry of Brgy. Inhabitants (RBI)</span>
                    </div>
                    <i class="fa-solid fa-chevron-down custom-arrow"></i>
                </a>
                <div class="collapse" id="rbiSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.resident.index') }}" class="nav-link">
                                <i class="fa-solid fa-user"></i>
                                <span class="nav-text">Residents</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.household.index') }}" class="nav-link">
                                <i class="fa-solid fa-house"></i>
                                <span class="nav-text">Households</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-arrow d-flex justify-content-between align-items-center" data-bs-target="#certificateSubmenu" aria-controls="certificateSubmenu" href="#" role="button" data-bs-toggle="collapse" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-file-lines"></i>
                        <span class="nav-text">Certificate Requests</span>
                    </div>
                    <i class="fa-solid fa-chevron-down custom-arrow"></i>
                </a>
                <div class="collapse" id="certificateSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.certificate-requests.index') }}" class="nav-link">
                                <i class="fa-solid fa-file-circle-exclamation"></i>
                                <span class="nav-text">View requests</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.certificates.indigency.create') }}" class="nav-link">
                                <i class="fa-solid fa-file-lines"></i>
                                <span class="nav-text">Generate Certificate of Indigency</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @can('view officials')
            <li class="nav-item">
                <a href="{{ route('admin.official.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-group"></i>
                    <span class="nav-text">Officials</span>
                </a>
            </li>
            @endcan

            @can('view committees')
            <li class="nav-item">
                <a href="{{ route('admin.committee.index') }}" class="nav-link">
                    <i class="fa-solid fa-users-line"></i>
                    <span class="nav-text">Committees</span>
                </a>
            </li>
            @endcan

            @can('manage users')
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="nav-text">User Accounts</span>
                </a>
            </li>
            @endcan

            @can('manage users')
            <li class="nav-item">
                <a href="{{ route('admin.verification-requests.index') }}" class="nav-link">
                    <i class="fa-solid fa-id-card"></i>
                    <span class="nav-text">Link Verification Requests</span>
                </a>
            </li>
            @endcan

            @can('manage users')
            <li class="nav-item">
                <a href="{{ route('admin.announcements.index') }}" class="nav-link">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span class="nav-text">Announcements</span>
                </a>
            </li>
            @endcan

            {{-- @can('manage user_permissions')
            <li class="nav-item">
                <a href="{{ route('admin.roles-permissions.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-shield"></i>
                    <span class="nav-text">ROLES PERMISSIONS</span>
                </a>
            </li>
            @endcan --}}
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
