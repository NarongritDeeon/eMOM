// header.js
function closeLogoutPopup() {
    document.getElementById('logout-popup').style.display = 'none';
}

function showProfilePopup() {
    document.getElementById('profile-popup').style.display = 'block';
}

function closeProfilePopup() {
    document.getElementById('profile-popup').style.display = 'none';
}

function closeSuccessPopup() {
    document.getElementById('success-popup').style.display = 'none';
    reloadUserInfo();  // Reload user information without refreshing the whole page
}

function logout() {
    window.location.href = 'logout.php';
}

document.getElementById('profile-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('edit_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'Success') {
            closeProfilePopup();
            document.getElementById('success-popup').style.display = 'block';
        } else {
            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

function reloadUserInfo() {
    fetch('header.php')
        .then(response => response.text())
        .then(data => {
            // Parse the returned HTML and update the user info
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const userNameElement = doc.getElementById('user-name');
            if (userNameElement) {
                document.getElementById('user-name').textContent = userNameElement.textContent;
            }
        })
        .catch(error => {
            console.error('Error reloading user info:', error);
        });
}