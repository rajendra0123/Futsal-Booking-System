<script>
btnSubmit = document.getElementById('btnSubmit');
btnSubmit.addEventListener('click', (event) => {

  const fullname = document.getElementById("fullname").value;
  const email = document.getElementById("email").value;
  const number = document.getElementById("number").value;
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  //fullname validation
  const fullnameError = document.getElementById("fullnameError");
  if (fullname === "") {
    event.preventDefault();
    fullnameError.textContent = "*required";
  }
  else if (fullname.length < 4) {
    event.preventDefault();
    fullnameError.textContent = "Fullname must contain more than 5 characters";
  }
  else if (/\d/.test(fullname)) {
    event.preventDefault();
    fullnameError.textContent = "Fullname cannot contain numbers";
  }
  else {
    fullnameError.textContent = "";
  }

  //email validation
  const emailError = document.getElementById("emailError");
  if (email === "") {
    event.preventDefault();
    emailError.textContent = "*required";
  }
  else if (email.length < 5) {
    event.preventDefault();
    emailError.textContent = "Email must be more than 5 characters";
  }
  else {
    emailError.textContent = "";
  }

  //number vlidation
  const numError = document.getElementById("numError");
  var regexPattern = (/^\d+$/);
  if (number === "") {
    event.preventDefault();
    numError.textContent = "*required";
  } else if (!regexPattern.test(number)) {
    event.preventDefault();
    numError.textContent = "Phone number must contain only digits";
  }
  else if (number.length != 10) {
    event.preventDefault();
    numError.textContent = "Phone number must be 10 digits";
  }
  else {
    numError.textContent = "";
  }

  //password validation
  const pwdError = document.getElementById("pwdError");
  var regexPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*()\-_=+{}[\]|;:'",.<>/?\\]+$/;
  if (password === "") {
    event.preventDefault();
    pwdError.textContent = "*required";
  } else if (password.length < 8) {
    event.preventDefault();
    pwdError.textContent = "Password must be at least 8 characters";
  } else if (!regexPattern.test(password)) {
    event.preventDefault();
    pwdError.textContent = "Password must contain at least one lowercase letter, one uppercase letter, and one digit";
  } else {
    pwdError.textContent = ""; // Add a message here to indicate password validity
  }



  //confirm pssword validation
  const cpwdError = document.getElementById("cpwdError");
  if (password !== confirmPassword) {
    event.preventDefault();
    cpwdError.textContent = "Passwords do not match";
  }
  else {
    cpwdError.textContent = "";
  }
})
</script>