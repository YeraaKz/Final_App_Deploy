document.addEventListener('DOMContentLoaded', function () {
    console.log('DOMContentLoaded event fired');
    const themeStylesheet = document.getElementById('theme-stylesheet');
    const currentTheme = localStorage.getItem('theme') || 'light';
    themeStylesheet.href = currentTheme === 'light' ? '/css/light.css' : '/css/dark.css';

    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }

    document.getElementById('light-mode').addEventListener('click', function () {
        console.log('light-mode button clicked');
        themeStylesheet.href = '/css/light.css';
        localStorage.setItem('theme', 'light');
        document.body.classList.remove('dark-mode');
        updateChartTheme('light');
    });

    document.getElementById('dark-mode').addEventListener('click', function () {
        console.log('dark-mode button clicked');
        themeStylesheet.href = '/css/dark.css';
        localStorage.setItem('theme', 'dark');
        document.body.classList.add('dark-mode');
        updateChartTheme('dark');
    });

    function updateChartTheme(theme) {
        const labels = document.querySelectorAll('#chartdiv text');
        labels.forEach(label => {
            label.style.fill = theme === 'dark' ? '#ffffff' : '#000000';
        });
    }

});
