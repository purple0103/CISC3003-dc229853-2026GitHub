document.addEventListener('DOMContentLoaded', function () {
  const authCard = document.getElementById('auth-card');
  const showSignUpButton = document.getElementById('show-sign-up');
  const showSignInButton = document.getElementById('show-sign-in');

  if (!authCard || !showSignUpButton || !showSignInButton) {
    return;
  }

  showSignUpButton.addEventListener('click', function () {
    authCard.classList.add('right-panel-active');
  });

  showSignInButton.addEventListener('click', function () {
    authCard.classList.remove('right-panel-active');
  });
});
