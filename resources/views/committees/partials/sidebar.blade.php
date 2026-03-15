<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">BarangayConnect</div>
        <div class="text-center">Committee Member</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            @can('view committee_dashboard')
            <li class="nav-item">
                <a href="{{ route('committee.dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="nav-text">DASHBOARD</span>
                </a>
            </li>
            @endcan

            @can('view rbi')
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
                            <a href="{{ route('resident.index') }}" class="nav-link">
                                <i class="fa-solid fa-users"></i>
                                <span class="nav-text">RESIDENTS</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('household.index') }}" class="nav-link">
                                <i class="fa-solid fa-house"></i>
                                <span class="nav-text">HOUSEHOLDS</span>
                            </a>
                        </li>
                    </ul>
                </div>
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

            @can('manage user_permissions')
            <li class="nav-item">
                <a href="{{ route('admin.roles-permissions.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-shield"></i>
                    <span class="nav-text">ROLES PERMISSIONS</span>
                </a>
            </li>
            @endcan

            @can('view health_sanitation')
            <li class="nav-item">
                <a href="{{ route('committee.health.dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="nav-text">HEALTH DASHBOARD</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('committee.immunization.index') }}" class="nav-link">
                    <i class="fa-solid fa-syringe"></i>
                    <span class="nav-text">IMMUNIZATION FOR INFANTS</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('committee.medicine.index') }}" class="nav-link">
                    <i class="fa-solid fa-tablets"></i>
                    <span class="nav-text">MEDICINE INVENTORY</span>
                </a>
            </li>
            @endcan

            @can('view budget_finance')
            <li class="nav-item">
                <a href="{{ route('committee.budget.index') }}" class="nav-link">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span class="nav-text">Budget Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('committee.disbursements.index') }}" class="nav-link">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    <span class="nav-text">Disbursements</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('committee.fund-sources.index') }}" class="nav-link">
                    <i class="fa-solid fa-piggy-bank"></i>
                    <span class="nav-text">Fund Sources</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('committee.reports.index') }}" class="nav-link">
                    <i class="fa-solid fa-file-lines"></i>
                    <span class="nav-text">Financial Reports</span>
                </a>
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