// login.php
document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("psw-check-in")
    .addEventListener("change", function () {
      var passwordField = document.getElementById("password-in");
      if (this.checked) {
        passwordField.type = "text";
        document.getElementById("toggle-password-in").innerHTML =
          '<i class="bi bi-eye-slash-fill"></i>';
      } else {
        passwordField.type = "password";
        document.getElementById("toggle-password-in").innerHTML =
          '<i class="bi bi-eye-fill"></i>';
      }
    });
  document
    .getElementById("psw-check-up")
    .addEventListener("change", function () {
      var passwordField = document.getElementById("password-up");
      if (this.checked) {
        passwordField.type = "text";
        document.getElementById("toggle-password-up").innerHTML =
          '<i class="bi bi-eye-slash-fill"></i>';
      } else {
        passwordField.type = "password";
        document.getElementById("toggle-password-up").innerHTML =
          '<i class="bi bi-eye-fill"></i>';
      }
    });
});
