document.getElementById('loginFormData').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;
    localStorage.setItem('loggedInUser', email);
    alert(`Selamat datang, ${email}`);
    window.location.href = 'index.html'; // atau baju.html
});
