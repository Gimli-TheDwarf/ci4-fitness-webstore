<html>
    <?= view('partials/headSection') ?>
    <?= view('partials/toast') ?>

    <body class="d-flex flex-column align-items-center justify-content-center bg-secondary">
        <div class="d-flex flex-column justify-content-center align-items-center body-wrapper">
            <div id="MainContainer" class="d-flex justify-content-center shadow align-items-stretch flex-row w-60 h-55 rounded-2 overflow-hidden border">

                <form id="SignUpForm" method="post" action="<?= base_url('signup')?>" class="d-flex flex-column align-items-center justify-content-center bg-light mb-0 p-4 w-50">

                    <h3 class="text-dark text-center fw-semibold mb-3 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-person-plus"></i><span>Sign Up</span>
                    </h3>

                    <label for="email" class="w-100 text-start fw-semibold small mb-1 d-inline-flex align-items-center gap-2"><i class="bi bi-envelope-at opacity-75"></i><span>E-Mail</span></label>
                    <input id="email" name="email" type="email" required autocomplete="email" class="rounded-1 form-control" placeholder="name@example.com" maxlength="254"><br>

                    <label for="username" class="w-100 text-start fw-semibold small mb-1 d-inline-flex align-items-center gap-2"><i class="bi bi-person-badge opacity-75"></i><span>Username</span></label>
                    <input id="username" name="username" required minlength="3" maxlength="24" pattern="^[a-zA-Z0-9_]{3,24}$" autocomplete="username" class="rounded-1 form-control" placeholder="Username"><br>
                    <div class="w-100 small text-muted mb-2">3–24 characters, letters/numbers/underscore only.</div>

                    <label for="password" class="w-100 text-start fw-semibold small mb-1 d-inline-flex align-items-center gap-2"><i class="bi bi-lock opacity-75"></i><span>Password</span></label>
                    <input id="password" type="password" name="password" required minlength="8" maxlength="64" autocomplete="new-password" class="hiddenPassword rounded-1 form-control" placeholder="Password"><br>

                    <label for="confirm_password" class="w-100 text-start fw-semibold small mb-1 d-inline-flex align-items-center gap-2"><i class="bi bi-lock-fill opacity-75"></i><span>Confirm Password</span></label>
                    <input id="confirm_password" type="password" name="confirm_password" required minlength="8" maxlength="64" autocomplete="new-password" class="hiddenPassword rounded-1 form-control" placeholder="Confirm Password"><br>
                    <div class="w-100 small text-muted mb-3">Minimum 8 characters.</div>

                    <button type="button" id="showPasswordButton" class="w-100 rounded-1 btn btn-outline-secondary fw-semibold d-inline-flex justify-content-center align-items-center gap-2 shadow-sm" onclick="showPassword()">
                        <i class="bi bi-eye"></i><span>Show Password</span>
                    </button><br>

                    <input type="submit" value="Proceed" class="w-100 rounded-1 btn btn-success fw-semibold shadow-sm"><br>

                    <p class="d-flex justify-content-center align-items-center text-center mb-2 small opacity-75">Or Sign Up With</p>

                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <a class="btn btn-danger fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center gap-2" type="button" href="<?= base_url('auth/google')?>">
                            <i class="fab fa-google"></i><span class="d-none d-md-inline">Google</span>
                        </a>
                    </div>

                </form>

                <div class="bg-success bg-gradient d-flex flex-column align-items-center justify-content-center w-50 p-4">
                    <h3 class="text-white text-center fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-stars"></i><span>Welcome to Lave!</span>
                    </h3><br>
                    <p class="text-white text-center p-3 mb-0 opacity-90">We are very happy to have you here, and we are even more pleased to be able to share our work with you.</p>
                </div>

            </div>
            
            <div class="d-flex flex-column justify-content-center align-items-center mt-3 mb-2">
                <p id="SignIn" class="m-0 fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-person-check opacity-75"></i>
                    <span>Already have an account?</span>
                    <a class="link-primary fw-semibold text-decoration-none" href="<?= base_url('login') ?>">Log in</a>
                </p>
            </div>

            <div class="w-100 d-flex justify-content-center mb-3">
                <span class="text-muted fw-semibold small text-uppercase letter-spacing-1">Lave™</span>
            </div>
        </div>



    <script src="<?= base_url('js/toastScript.js') ?>" defer></script>
    <script src="<?= base_url('js/showPassword.js') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => 
    {
        <?php if (session()->getFlashdata('success')): ?>
            notify("Sign in successful");
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            notify("Invalid credentials");
        <?php endif; ?>
    });
    </script>

    </body>
</html>