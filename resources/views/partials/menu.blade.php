<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">Admin Panel</div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="nav-text">DASHBOARD</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.rbi.index') }}" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    <span class="nav-text">REGISTRY OF BRGY. INHABITANTS (RBI)</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.hh.index') }}" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav-text">HOUSEHOLD LIST</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.ua.index') }}" class="nav-link">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="nav-text">USER ACCOUNTS</span>
                </a>
            </li>
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