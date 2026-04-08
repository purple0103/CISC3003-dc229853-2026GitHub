<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>dc229853 Zihan Zhang</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <main class="page-shell">
    <section class="auth-wrapper" aria-labelledby="page-heading">
      <h1 class="sr-only" id="page-heading">CISC3003 sign in and sign up form</h1>
      <div class="auth-card" id="auth-card">
        <div class="form-panel sign-up-panel">
          <form class="auth-form" id="sign-up-form" action="#" method="post" data-success="Registration fields validated successfully.">
            <h2 id="signup-title">Join Us</h2>
            <div class="social-row" role="group" aria-label="Social sign up links">
              <a class="social-link" href="#" aria-label="Register with Facebook">f</a>
              <a class="social-link" href="#" aria-label="Register with Google">G+</a>
              <a class="social-link" href="#" aria-label="Register with LinkedIn">in</a>
            </div>
            <p class="helper-text">Use your email to sign up</p>

            <label class="sr-only" for="signup-name">Full Name</label>
            <input id="signup-name" name="full_name" type="text" placeholder="Full Name" autocomplete="name" required>

            <label class="sr-only" for="signup-email">Email</label>
            <input id="signup-email" name="email" type="email" placeholder="Email" autocomplete="email" required>

            <label class="sr-only" for="signup-password">Create Password</label>
            <input id="signup-password" name="create_password" type="password" placeholder="Create Password" autocomplete="new-password" required>

            <button class="action-button" type="submit">Register</button>
            <p class="form-feedback" aria-live="polite" hidden></p>
          </form>
        </div>

        <div class="form-panel sign-in-panel">
          <form class="auth-form" id="sign-in-form" action="#" method="post" data-success="Login fields validated successfully.">
            <h2 id="signin-title">Log In</h2>
            <div class="social-row" role="group" aria-label="Social sign in links">
              <a class="social-link" href="#" aria-label="Sign in with Facebook">f</a>
              <a class="social-link" href="#" aria-label="Sign in with Google">G+</a>
              <a class="social-link" href="#" aria-label="Sign in with LinkedIn">in</a>
            </div>
            <p class="helper-text">Use your account to sign in</p>

            <label class="sr-only" for="signin-email">Email</label>
            <input id="signin-email" name="email" type="email" placeholder="Email" autocomplete="email" required>

            <label class="sr-only" for="signin-password">Password</label>
            <input id="signin-password" name="password" type="password" placeholder="Password" autocomplete="current-password" required>

            <a class="text-link" href="#">Forgot Password?</a>
            <button class="action-button" type="submit">Sign In</button>
            <p class="form-feedback" aria-live="polite" hidden></p>
          </form>
        </div>

        <div class="overlay-panel-group">
          <div class="overlay-track">
            <section class="overlay-panel overlay-left">
              <div class="overlay-copy">
                <h2>Hello, Again!</h2>
                <img class="panel-art" src="images/register-panel-art.png" alt="Illustration of a user profile for the sign in panel">
                <p>Log in to stay connected with us</p>
                <button class="ghost-button" id="show-sign-in" type="button">Sign In</button>
              </div>
            </section>
            <section class="overlay-panel overlay-right">
              <div class="overlay-copy">
                <h2>Welcome!</h2>
                <img class="panel-art" src="images/login-panel-art.jpg" alt="Illustration of an envelope with a secure message for the sign up panel">
                <p>Enter your details to start your journey</p>
                <button class="ghost-button" id="show-sign-up" type="button">Sign Up</button>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <p>CISC3003 Web Programming: dc229853 Zihan Zhang 2026</p>
  </footer>

  <script src="js/form-toggle.js"></script>
  <script src="js/form-submit.js"></script>
</body>
</html>
