// JavaScript for real-time form validation
document.getElementById('resumeForm').addEventListener('submit', function (e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const bio = document.getElementById('bio').value.trim();
    const resume = document.getElementById('resume').files[0];

    // Validation rules
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9]{10}$/;

    if (name === '' || email === '' || phone === '' || bio === '') {
        alert('All fields are required.');
        e.preventDefault();
        return;
    }

    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        e.preventDefault();
        return;
    }

    if (!phoneRegex.test(phone)) {
        alert('Please enter a valid 10-digit phone number.');
        e.preventDefault();
        return;
    }

    if (!resume || !resume.name.endsWith('.pdf')) {
        alert('Please upload a valid PDF resume.');
        e.preventDefault();
        return;
    }

    alert('Form submitted successfully!');
});
