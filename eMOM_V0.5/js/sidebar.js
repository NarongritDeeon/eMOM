document.addEventListener('DOMContentLoaded', function () {
    const settingsMenu = document.querySelector('.settings');
    const settingsSubMenu = document.querySelector('.settings-menu');
    const meetingsMenu = document.querySelector('.meetings');
    const meetingsSubMenu = document.querySelector('.meetings-menu');

    if (settingsMenu) {
        settingsMenu.addEventListener('click', function () {
            settingsMenu.classList.toggle('active');
            if (settingsMenu.classList.contains('active')) {
                settingsSubMenu.style.maxHeight = settingsSubMenu.scrollHeight + 'px';
            } else {
                settingsSubMenu.style.maxHeight = '0';
            }
        });
    } else {
        console.error('Element with class "settings" not found.');
    }

    if (meetingsMenu) {
        meetingsMenu.addEventListener('click', function () {
            meetingsMenu.classList.toggle('active');
            if (meetingsMenu.classList.contains('active')) {
                meetingsSubMenu.style.maxHeight = meetingsSubMenu.scrollHeight + 'px';
            } else {
                meetingsSubMenu.style.maxHeight = '0';
            }
        });
    } else {
        console.error('Element with class "meetings" not found.');
    }
});
