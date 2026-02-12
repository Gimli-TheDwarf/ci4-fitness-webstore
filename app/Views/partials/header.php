<header id="site-header" class="fixed-top bg-success shadow-sm" style="min-height: 10vh;">
  <div class="container-fluid px-3">
    <div class="row align-items-center justify-content-center g-2 py-2">

      <!-- Left -->
      <div id="left-side" class="col-12 col-md-4 d-flex align-items-center justify-content-center justify-content-md-start gap-3">
        <a id="home-page-icon" href="<?= base_url('homepage')?>" class="d-inline-flex align-items-center">
          <img title="Home" style="height: 50px;" class="rounded-2 shadow-sm hover-fx-1" src="<?= base_url('images/Lave1.png') ?>"alt="Lave logo">
        </a>

        <div id="delivery-info" class="d-flex flex-column text-white lh-sm">
          <span class="small opacity-75">Deliver to <strong class="opacity-100"><?= $username ?></strong></span>
          <a href="checkout" class="btn btn-sm btn-outline-light d-inline-flex align-items-center justify-content-center gap-2 fw-semibold py-1 px-2">
            <i class="bi bi-truck"></i>
            <span>Delivery</span>
          </a>
        </div>
      </div>

      <!-- Middle -->
      <div id="search-bar-container" class="col-12 col-md-4">
      </div>

      <!-- Right -->
      <div id="container-basics"
           class="col-12 col-md-4 d-flex align-items-center justify-content-center justify-content-md-end gap-3">

        <?php if(session()->get('isLoggedIn')): ?>
          <button onclick="window.location.href = 'account'" title="Account Info" class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold py-1 px-2" aria-label="Account">
            <i class="bi bi-person-circle"></i>
            <span class="d-none d-lg-inline">Account</span>
          </button>
        <?php else: ?>
          <a href="<?= base_url('login') ?>" class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold py-1 px-2 text-decoration-none" aria-label="Log in">
            <i class="bi bi-box-arrow-in-right"></i>
            <span class="d-none d-lg-inline">Log in</span>
          </a>
        <?php endif; ?>

        <a href="<?= base_url('cart')?>" class="btn btn-sm btn-light d-inline-flex align-items-center gap-2 fw-semibold py-1 px-2 text-decoration-none">
          <span class="position-relative d-inline-flex align-items-center">
            <i class="bi bi-cart3 fs-5"></i>
            <span id="cartItemsCountIcon" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fw-bold"><?= esc(session()->get('cart_items_count') ?? 0) ?></span>
          </span>
          <span class="d-none d-lg-inline">Cart</span>
        </a>

        <a href="<?= base_url('Favorites')?>" class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold py-1 px-2 text-decoration-none" aria-label="Wishlist">
            <i class="bi bi-bookmark-heart"></i>
            <span class="d-none d-lg-inline">Wishlist</span>
        </a>

      </div>
    </div>
  </div>
</header>