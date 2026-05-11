<?= view('partials/headSection') ?>
<?= view('partials/adminInfoSection') ?>


<body class="d-flex min-vh-100 bg-blue-gray">
  <main id="admin-main" class="flex-grow-1 d-flex flex-column align-items-center">
    <header id="header-admin" class="w-100 d-flex align-items-center border-bottom border-white border-opacity-10 shadow-sm sticky-top bg-blue-gray text-white">
      <div class="container-fluid">
        <div class="row g-3 p-3 d-flex justify-content-center align-items-center">

          <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-start align-items-center gap-2">
            <a href="<?= base_url('homepage') ?>" class="admin-icon-box rounded-2 bg-orange text-white shadow-sm d-inline-flex align-items-center justify-content-center text-decoration-none" title="Return Home" aria-label="Return Home">
              <i title="Admin Panel" class="fs-3 fa-solid fa-user-tie"></i>
            </a>
            <span class="fs-4 fw-semibold m-0"><?= esc(session()->get('username')) ?></span>
          </div>

          <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
            <p class="fs-6 text-center flex-grow-1 m-0 text-white opacity-75">
              Logged in as <span class="fw-bold fs-6 badge rounded-pill bg-orange text-white border-0"><?= esc(session()->get('role')) ?></span>
            </p>
          </div>

          <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
            <a class="text-decoration-none" href="<?= base_url('logout') ?>">
              <button class="admin-action-button shadow-sm border-0 bg-orange text-light d-inline-flex align-items-center justify-content-center btn-orange rounded-circle" title="Log Out" type="button">
                <i class="fs-4 fa-solid fa-arrow-right-from-bracket"></i>
              </button>
            </a>
          </div>

        </div>
      </div>
    </header>

    <section class="homepage-products flex-fill w-100 px-3 py-4 py-lg-5">
      <div id="admin-panel-container" class="container-xxl px-0 d-flex justify-content-center"></div>
    </section>
  </main>
</body>


<?= view('partials/toast') ?>
<?= view('partials/modalWindow') ?>

<?= view('partials/loadInfo') ?>

<script src="<?= base_url('js/toastScript.js') ?>" defer></script>
<script type="module" src="<?= base_url('js/modalScript.js')?>"></script>
