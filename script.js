document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIconSun = document.getElementById('theme-icon-sun');
    const themeIconMoon = document.getElementById('theme-icon-moon');
    const root = window.document.documentElement;

    const applyTheme = (theme) => {
        if (theme === 'dark') {
            root.classList.add('dark');
            themeIconSun.classList.remove('hidden');
            themeIconMoon.classList.add('hidden');
        } else {
            root.classList.remove('dark');
            themeIconSun.classList.add('hidden');
            themeIconMoon.classList.remove('hidden');
        }
    };

    let currentTheme = localStorage.getItem('theme') || 'dark';
    applyTheme(currentTheme);

    themeToggle.addEventListener('click', () => {
        currentTheme = root.classList.contains('dark') ? 'light' : 'dark';
        localStorage.setItem('theme', currentTheme);
        applyTheme(currentTheme);
    });
});
