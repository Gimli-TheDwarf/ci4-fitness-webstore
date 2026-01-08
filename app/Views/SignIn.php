<html>
    <?= view('partials/headSection') ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?= session()->getFlashdata('success') ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = new bootstrap.Modal(document.getElementById('success-modal'));
        modal.show()
    </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">SignUp Unsuccesful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = new bootstrap.Modal(document.getElementById('error-modal'));
        modal.show()
    </script>
    <?php endif; ?>

    <body class="d-flex flex-column align-items-center justify-content-center bg-secondary">
    <div class="d-flex flex-column justify-content-center align-items-center body-wrapper">
        <div id="MainContainer" class="d-flex justify-content-center shadow align-items-stretch flex-row w-60 h-55">

            <form id="SignUpForm" method="post" action="<?= base_url('signup')?>" class="d-flex flex-column align-items-center justify-content-center bg-light mb-0 p-4 w-50">

                <h3 class="text-dark text-center">SignIn</h3>

                <label for="email" class="w-100 text-start">E-Mail</label>
                <input id="email" name="email" required class="rounded-1 form-control" placeholder="E-mail"><br>

                <label for="username" class="w-100 text-start">Username</label>
                <input id="username" name="username" required class="rounded-1 form-control" placeholder="Username"><br>
                
                <label for="password" class="w-100 text-start">Password</label>
                <input id="password" type="password" name="password" required class="hiddenPassword rounded-1 form-control" placeholder="Password"><br>

                <label for="confirm_password" class="w-100 text-start">Confirm Password</label>
                <input id="confirm_password" type="password" name="confirm_password" required class="hiddenPassword rounded-1 form-control" placeholder="Confirm Password"><br>

                <button type="button" id="showPasswordButton" class="w-100 rounded-1 btn text-light btn-secondary"onclick="showPassword()">Show Password</button><br>

                <input type="submit" value="Proceed" class="w-100 rounded-1 btn btn-success"><br>

                <p class="d-flex justify-content-center align-items-center text-center">Or Sign In With</p>
                
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <a class="btn btn-danger" type="button" href="<?= base_url('auth/google')?>">
                        <i class="fab fa-google"></i>
                    </a>
                </div>

            </form>

            <div class="bg-success bg-gradient d-flex flex-column align-items-center justify-content-center w-50">
                <h3 class="text-white text-center">Welcome to Lave!</h3><br>
                <p class="text-white text-center p-3">We are very happy to have you here, and we are even more pleased to be able to share our work with you.</p>
            </div>

        </div>

        <p id="SignIn" method="get" class="m-4 fw-semibold d-flex align-items-center flex-column text-shadow">Already Have An Account?<a class="link-primary" href="login">Log in.</a></p>

        <div class="fixed-bottom text-white fw-semibold m-3 d-flex justify-content-center text-shadow">Lave™</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    </body>

    <script>
    
    function showPassword()
    {
        var passwords = document.getElementsByClassName("hiddenPassword");
        var button = document.getElementById("showPasswordButton");
        Array.from(passwords).forEach(function(password)
        {
            if(password.type === "password")
            {
                button.innerHTML = 'Hide Password';
                password.type = "text";
            }
            else
            {
                button.innerHTML = 'Show Password';
                password.type = "password";
            }
        });
    }
        // $(document).ready(function()
        // {
        //     $('#SignUpForm').on('submit', function(event)
        //     {
        //         $.ajax({
        //             url: '<?= base_url('SignUp') ?>',
        //             method: 'POST',
        //             dataType: 'JSON',
        //             data: $(this).serialize(), //line takes all the form fields inside the form and converts them into a URL-encoded query string

        //             success: function(response)
        //             {
        //                 console.log(response.message);
        //                 alert(response);
        //             },

        //             error: function(jqXHR)
        //             {
        //                 console.log("Error occured, item was not deleted.");
        //                 console.log(jqXHR.responseJSON.message);
        //                 alert(jqXHR.responseJSON.message);
        //             }
        //         });
        //     });
        // });
    </script>
</html> 
<!-- justify-content aligns items on the horizontal (X) axis -->
<!-- align-items aligns items on the vertical (Y) axis -->