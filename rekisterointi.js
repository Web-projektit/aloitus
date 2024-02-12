
document.querySelector('#rekisterointilomake').addEventListener('submit', event => {
  var password = document.querySelector('#password').value;
  var confirmPassword = document.querySelector('#password2').value;

  if (password !== confirmPassword) {
    event.preventDefault(); // Estä lomakkeen lähetys
    confirmPassword.classList.add('is-invalid');
  }
});

