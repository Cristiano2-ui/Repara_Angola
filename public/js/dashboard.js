// Tab switching functionality
function switchTab(tabName) {
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tab => tab.classList.remove('active'));

    const tabButtons = document.querySelectorAll('.nav-tab');
    tabButtons.forEach(btn => btn.classList.remove('active'));

    document.getElementById(tabName).classList.add('active');

    const activeButtons = document.querySelectorAll(`button[onclick="switchTab('${tabName}')"]`);
    activeButtons.forEach(btn => btn.classList.add('active'));
}

// Responsive navigation
function handleResize() {
    const desktopNav = document.getElementById('desktop-nav');
    const mobileNav = document.getElementById('mobile-nav');

    if (window.innerWidth >= 768) {
        desktopNav.style.display = 'flex';
        mobileNav.style.display = 'none';
    } else {
        desktopNav.style.display = 'none';
        mobileNav.style.display = 'flex';
    }
}

