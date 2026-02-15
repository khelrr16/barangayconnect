<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">Admin Panel</div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-dashboard"></i>
                    <span class="nav-text">DASHBOARD</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.rbi') }}" class="nav-link">
                    <i class="fas fa-dashboard"></i>
                    <span class="nav-text">REGISTRY OF BRGY. INHABITANTS (RBI)</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.ua') }}" class="nav-link">
                    <i class="fas fa-dashboard"></i>
                    <span class="nav-text">USER ACCOUNTS</span>
                </a>
            </li>

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input hidden type="submit" id="logout">
                    <div class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <label class="nav-text" for="logout">LOGOUT</label>
                    </div>
                </form>
            </li>
        </ul>
    </nav>
</aside>