const form = document.getElementById('newsletterForm');
  form.addEventListener('submit', function(event) {
    event.preventDefault(); // cegah form submit default supaya tidak reload halaman

const email = document.getElementById('emailInput').value.trim();
    if (email) {
      alert('Thank you for subscribing!');
      form.reset(); // reset form setelah submit
    } else {
      alert('Please enter a valid email.');
    }
  });