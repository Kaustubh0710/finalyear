document.getElementById('loginForm').addEventListener('submit', function(event) {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === '' || password === '') {
        alert('Please fill in all fields.');
        event.preventDefault(); // Prevent form submission
    }
});

// Additional JavaScript for other forms can be added here