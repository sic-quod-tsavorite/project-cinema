// account.php
document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("psw-check-crnt")
    .addEventListener("change", function () {
      var passwordField = document.getElementById("current_password");
      if (this.checked) {
        passwordField.type = "text";
        document.getElementById("toggle-password-crnt").innerHTML =
          '<i class="bi bi-eye-slash-fill"></i>';
      } else {
        passwordField.type = "password";
        document.getElementById("toggle-password-crnt").innerHTML =
          '<i class="bi bi-eye-fill"></i>';
      }
    });
  document
    .getElementById("psw-check-new")
    .addEventListener("change", function () {
      var passwordField = document.getElementById("new_password");
      if (this.checked) {
        passwordField.type = "text";
        document.getElementById("toggle-password-new").innerHTML =
          '<i class="bi bi-eye-slash-fill"></i>';
      } else {
        passwordField.type = "password";
        document.getElementById("toggle-password-new").innerHTML =
          '<i class="bi bi-eye-fill"></i>';
      }
    });
});

function togglePasswordForm() {
  var passwordForm = document.getElementById("password-form");
  if (passwordForm.style.display === "none") {
    passwordForm.style.display = "block";
  } else {
    passwordForm.style.display = "none";
  }
}

function submitForm(form) {
  form.submit();
}
