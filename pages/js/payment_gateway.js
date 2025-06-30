const form = document.getElementById('payment-form');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    const form_data = new FormData(form);
    const action = form.getAttribute('action');

    fetch(action, {
        method: 'POST',
        body: form_data
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Payment successful! Thank you for shopping with us.');
            window.location.href = "../index.php";
        } else {
            alert('Payment failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your payment.');
    });
});