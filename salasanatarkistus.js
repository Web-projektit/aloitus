const lomake = document.querySelector('.salasanat');

const poista_invalid_message = event => {
  /* 
  Oma kuuntelijafunktio, jotta kuuntelijan voi myös poistaa. 
  Tässä poistetaan Javascriptillä asetetut virheilmoitukset.
  */  
      const input = event.target;
      input.classList.remove('is-invalid');
      input.setCustomValidity('');
      input.removeEventListener('input', poista_invalid_message)
    }
    
lomake.addEventListener('submit', event => {
  var password = document.querySelector('#password')
  var confirmPassword = document.querySelector('#password2')
  confirmPassword.classList.remove('is-invalid')

  if (password.value && password.value !== confirmPassword.value) {
    event.preventDefault(); // Estä lomakkeen lähetys
    confirmPassword.classList.add('is-invalid')
    confirmPassword.setCustomValidity('Salasanat eivät täsmää.')
    confirmPassword.addEventListener('input',poista_invalid_message)
    }
   })
  


