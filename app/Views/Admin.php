<?= view('partials/headSection') ?>
<?= view('partials/adminInfoSection') ?>


<body class="d-flex min-vh-100 bg-light">
  <main id="admin-main" class="flex-grow-1 d-flex flex-column align-items-center">
    <header id="header-admin" class="w-100 d-flex align-items-center border-bottom shadow-sm" style="min-height: 10vh; position: sticky; top: 0; z-index: 1040; background: rgba(255,255,255,.85); backdrop-filter: blur(10px);">
      <div class="container-fluid">
        <div class="row g-3 p-3 d-flex justify-content-center align-items-center">

          <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-start align-items-center gap-2">
            <div class="rounded-3 bg-light border shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
              <i title="Admin Panel" class="fs-3 fa-solid fa-user-tie"></i>
            </div>
            <span class="fs-4 fw-semibold m-0"><?= esc(session()->get('username')) ?></span>
          </div>

          <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
            <p class="fs-6 text-center flex-grow-1 m-0">
              Logged in as <span class="fw-bold fs-5 badge rounded-pill bg-success-subtle text-success-emphasis border"><?= esc(session()->get('role')) ?></span>
            </p>
          </div>

          <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
            <a class="text-decoration-none" href="<?= base_url('logout') ?>">
              <button class="shadow-sm border-0 bg-blue-gray text-light d-inline-flex align-items-center justify-content-center btn-blue-gray" style="width: 56px; height: 56px; border-radius:50%; padding:0;" title="Log Out" type="button">
                <i class="fs-4 fa-solid fa-arrow-right-from-bracket"></i>
              </button>
            </a>
          </div>

        </div>
      </div>
    </header>

    <div id="admin-panel-container" class="flex-fill w-100 d-flex justify-content-center p-3"></div>
  </main>
</body>


<?= view('partials/toast') ?>
<?= view('partials/modalWindow') ?>

<?= view('partials/loadInfo') ?>

<script src="<?= base_url('js/toastScript.js') ?>" defer></script>
<script type="module" src="<?= base_url('js/modalScript.js')?>"></script>