<html>
    <?= view('partials/headSection') ?>

    <?php if(session()->getFlashData('error')):  ?>

        <button class="position-absolute"><?= session()->getFlashData('error')?></button>

    <?php endif; ?>

    <body class="bg-gradient bg-secondary d-flex justify-content-center align-items-center flex-column">
    <div class="d-flex flex-column justify-content-center align-items-center body-wrapper">
            <form id="LogInForm" method="post" action="<?= base_url("login_info")?>" class="d-flex flex-column align-items-center justify-content-center gap-1 bg-light p-4">

                <h3 class="text-dark text-center">LogIn</h3>

                <label for="email" class="w-100 text-start">E-Mail</label>
                <input id="email" name="email" required class="rounded-1 form-control" placeholder="E-Mail"><br>

                <label for="password" class="w-100 text-start">Password</label>
                <input id="password" name="password" required class="hiddenPassword rounded-1 form-control" placeholder="Password"><br>
                
                <input type="Submit" value="Proceed" class="w-100 rounded-1 btn btn-success">
                
                <p class="text-center mb-0">Or Log In With</p>

                <a class="btn btn-danger" type="button" href="<?= base_url('auth/google')?>">
                    <i class="fab fa-google"></i>
                </a>
                
            </form>

            <p id="SignIn" method="get" class="m-4 fw-semibold d-flex align-items-center flex-column text-shadow">Don't Have An Account?<a class="link-primary" href="/">Sign in.</a></p>

            <div class="fixed-bottom text-white fw-semibold m-3 d-flex justify-content-center text-shadow">Lave™</div>
    </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</html>