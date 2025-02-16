const fullnameInput = document.getElementById("fullname");
const usernameInput = document.getElementById("username");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");

const fullnameIcon = document.getElementById("fullnameIcon");
const usernameIcon = document.getElementById("usernameIcon");
const emailIcon = document.getElementById("emailIcon");
const lengthIcon = document.getElementById("lengthIcon");
const caseIcon = document.getElementById("caseIcon");
const numberIcon = document.getElementById("numberIcon");
const symbolIcon = document.getElementById("symbolIcon");

fullnameInput.addEventListener("input", function () {
  if (fullnameInput.value.trim() !== "") {
    fullnameIcon.textContent = "✓";
    fullnameIcon.style.color = "green";
  } else {
    fullnameIcon.textContent = "❌";
    fullnameIcon.style.color = "red";
  }
});

usernameInput.addEventListener("input", function () {
  if (usernameInput.value.trim() !== "") {
    usernameIcon.textContent = "✓";
    usernameIcon.style.color = "green";
  } else {
    usernameIcon.textContent = "❌";
    usernameIcon.style.color = "red";
  }
});

emailInput.addEventListener("input", function () {
  if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
    emailIcon.textContent = "✓";
    emailIcon.style.color = "green";
  } else {
    emailIcon.textContent = "❌";
    emailIcon.style.color = "red";
  }
});

passwordInput.addEventListener("input", function () {
  const password = passwordInput.value;

  if (password.length >= 8) {
    lengthIcon.textContent = "✓";
    lengthIcon.style.color = "green";
  } else {
    lengthIcon.textContent = "❌";
    lengthIcon.style.color = "red";
  }

  if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
    caseIcon.textContent = "✓";
    caseIcon.style.color = "green";
  } else {
    caseIcon.textContent = "❌";
    caseIcon.style.color = "red";
  }

  if (/[0-9]/.test(password)) {
    numberIcon.textContent = "✓";
    numberIcon.style.color = "green";
  } else {
    numberIcon.textContent = "❌";
    numberIcon.style.color = "red";
  }

  if (/[^a-zA-Z0-9\s]/.test(password)) {
    symbolIcon.textContent = "✓";
    symbolIcon.style.color = "green";
  } else {
    symbolIcon.textContent = "❌";
    symbolIcon.style.color = "red";
  }
});
