<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">BarangayConnect</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('resident.dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="nav-text">DASHBOARD</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('resident.profile') }}" class="nav-link">
                    <i class="fa-solid fa-user"></i>
                    <span class="nav-text">MY PROFILE</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('resident.request-document') }}" class="nav-link">
                    <i class="fa-solid fa-file-lines"></i>
                    <span class="nav-text">REQUEST DOCUMENT</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('resident.my-requests') }}" class="nav-link">
                    <i class="fa-solid fa-list-check"></i>
                    <span class="nav-text">MY REQUESTS</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('resident.announcements') }}" class="nav-link">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span class="nav-text">ANNOUNCEMENTS</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('resident.contact') }}" class="nav-link">
                    <i class="fa-solid fa-address-book"></i>
                    <span class="nav-text">CONTACT</span>
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
