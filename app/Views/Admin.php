<?= view('partials/headSection') ?>
<?= view('partials/adminInfoSection') ?>

<body class="d-flex min-vh-100">
    <div class="d-flex position-fixed h-100 bg-blue-gray col-lg-2 col-md-3" >

    </div>

    <main class="flex-grow-1 d-flex flex-column align-items-center offset-lg-2 offset-md-3">
        <header id="header-admin" class="w-100 rounded rounded-start-0 shadow d-flex align-items-center fixed-top bg-light bg-gradient" style="position: relative; min-height: 10vh; max-height: 20vh;">  
            <div class="container-fluid">
                <div class="row g-4 p-2 d-flex justify-content-center align-items-center">

                    <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-start">
                        <i title="Log In" class="d-flex align-items-center display-4 fa-solid fa-user-tie"></i>
                        <span class="d-flex justify-content-start align-items-center ms-2 fs-4 text-center "><?= session()->get('username')?></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-center col-12 col-md-4">
                        <p class="fs-4 text-center flex-grow-1 m-0">
                            Logged in as <span class="fw-bold fs-3"><?= session()->get('role')?></span>
                        </p>
                    </div>

                    <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end">
                        <a class="text-decoration-none" href="<?= base_url('logout') ?>"> 
                            <button class="shadow border-0 bg-blue-gray text-light d-flex align-items-center justify-content-center btn-blue-gray" style="width: 70px; height: 70px; border-radius:50%; padding:0;" title="Log Out"> 
                                <i class="fs-2 fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </a>
                    </div>

                </div>
            </div>
        </header>

        
        <div id="admin-panel-container" class="mt-2 flex-fill w-100 d-flex justify-content-center">
        </div>
    </main>

</body>


<?= view('partials/toast') ?>
<?= view('partials/modalWindow') ?>

<?= view('partials/loadInfo') ?>

<script src="<?= base_url('js/toastScript.js') ?>" defer></script>
<script type="module" src="<?= base_url('js/modalScript.js')?>"></script>