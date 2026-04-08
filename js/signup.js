document.getElementById("signupForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirm = document.getElementById("confirm").value;

  // Validation
  if (password !== confirm) {
    alert("Passwords do not match!");
    return;
  }

  // Store data
  localStorage.setItem("userEmail", email);
  localStorage.setItem("userPassword", password);

  alert("Signup successful!");

  // Redirect
  window.location.href = "login.html";
});