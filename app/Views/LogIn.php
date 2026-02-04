<html>
    <?= view('partials/headSection') ?>
    <?= view('partials/toast') ?>

    <body class="bg-gradient bg-secondary d-flex justify-content-center align-items-center flex-column no-scrollbar">
        <div class="d-flex flex-column justify-content-center align-items-center body-wrapper">

            <form id="LogInForm" method="post" action="<?= base_url("login_info")?>" class="d-flex flex-column align-items-center justify-content-center gap-1 bg-light p-4 rounded-2 shadow-sm border">

                <h3 class="text-dark text-center fw-semibold mb-3 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-right"></i><span>Log In</span>
                </h3>

                <label for="email" class="w-100 text-start fw-semibold small mb-1 d-inline-flex align-items-center gap-2"><i class="bi bi-envelope-at opacity-75"></i><span>E-Mail</span></label>
                <input id="email" name="email" type="email" required autocomplete="email" class="rounded-1 form-control" placeholder="name@example.com" maxlength="254"><br>

                <label for="password" class="w-100 text-start fw-semibold small mb-1 d-inline-flex align-items-center gap-2"><i class="bi bi-lock opacity-75"></i><span>Password</span></label>
                <input id="password" name="password" type="password" required minlength="8" maxlength="64" autocomplete="current-password" class="hiddenPassword rounded-1 form-control" placeholder="Password"><br>

                <div class="w-100 d-flex justify-content-center align-items-center gap-2 flex-column">
                    <button type="button" id="showPasswordButton" class="w-100 rounded-1 btn btn-outline-secondary fw-semibold d-inline-flex justify-content-center align-items-center gap-2 shadow-sm" onclick="showPassword()"><i class="bi bi-eye"></i><span>Show Password</span></button>
                    <input type="submit" value="Proceed" class="w-100 rounded-1 btn btn-success fw-semibold shadow-sm">
                </div>

                <p class="text-center mb-2 mt-3 small opacity-75">Or Log In With</p>

                <a class="btn btn-danger fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center gap-2" type="button" href="<?= base_url('auth/google')?>"><i class="fab fa-google"></i><span class="d-none d-md-inline">Google</span></a>

            </form>

            <div class="d-flex flex-column justify-content-center align-items-center mt-3 mb-2">
                <p id="SignIn" class="m-0 fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-person-plus opacity-75"></i>
                    <span>Don't have an account?</span>
                    <a class="link-primary fw-semibold text-decoration-none" href="<?= base_url('/') ?>">Sign up</a>
                </p>
            </div>

            <div class="w-100 d-flex justify-content-center mb-3">
                <span class="text-muted fw-semibold small text-uppercase letter-spacing-1">Lave™</span>
            </div>

        </div>
    </body>

    <script src="<?= base_url('js/showPassword.js') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= base_url('js/toastScript.js') ?>" defer></script>

    
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
</html>