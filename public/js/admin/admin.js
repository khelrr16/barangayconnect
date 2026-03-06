document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const toggleIcon = sidebarToggle.querySelector('i');
    
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent.classList.remove('with-sidebar');
        toggleIcon.className = 'fa-solid fa-bars';
    } else {
        mainContent.classList.add('with-sidebar');
        toggleIcon.className = 'fa-solid fa-xmark';
    }
    
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        
        if (sidebar.classList.contains('collapsed')) {
            mainContent.classList.remove('with-sidebar');
            localStorage.setItem('sidebarCollapsed', 'true');
            toggleIcon.className = 'fa-solid fa-bars';
        } else {
            mainContent.classList.add('with-sidebar');
            localStorage.setItem('sidebarCollapsed', 'false');
            toggleIcon.className = 'fa-solid fa-xmark';
        }
        
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-visible');
        }
    });
    
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const navItem = this.closest('.nav-item');
            navItem.classList.toggle('open');
        });
    });
    
    if (window.innerWidth <= 768) {
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('mobile-visible');
            }
        });
    }
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-visible');
        }
    });
    
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link, .submenu-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            
            const parentNavItem = link.closest('.nav-item');
            if (parentNavItem) {
                parentNavItem.classList.add('open');
            }
        }
    });
});