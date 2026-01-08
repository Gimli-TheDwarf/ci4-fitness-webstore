
<header id="site-header" class="w-100 d-flex align-items-center fixed-top bg-success bg-gradient" style="min-height: 10vh" >
    <div class="container-fluid">
        <div class="row align-items-center justify-content-center g-2 p-2">

            <div id="left-side" class="d-flex justify-content-center justify-content-md-start col-12 col-md-4 d-flex flex-row">
                <a id="home-page-icon" href="<?= base_url('homepage')?>">
                    <img title="Home" style="height: 50px;" class="me-4 hover-fx-1 rounded-1 shadow" src="<?= base_url('images/Lave1.png') ?>" alt="Lave logo">
                </a>

                <div id="delivery-info" class="text-shadow d-flex flex-column text-white align-items-center">
                    <span>Deliver to <strong><?= $username ?></strong></span>
                    <button style="font-size: 20px;"type="button" class="text-shadow d-flex align-items-center display-6 border-0 bg-transparent text-white">
                        <span class="fa-solid fa-truck me-1"></span>
                        <strong>Delivery</strong>
                    </button>
                </div>
            </div>

            <div id="search-bar-container" class="col-12 col-md-4">
            </div>

            <div id="container-basics" class="d-flex justify-content-center justify-content-md-end col-12 col-md-4 d-flex flex-row justify-content-end gap-4">

                <?php if(session()->get('isLoggedIn')): ?>
                    <div id="dropdown" class="d-flex align-items-center ">
                        <button title="Account Info" class="text-shadow display-6 border-0 bg-transparent text-white hover-fx-1 fa-solid fa-user"></button>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="text-decoration-none"><i title="Log In" class=" d-flex align-items-center text-shadow display-6 text-white hover-fx-1 fa fa-sign-in"></i></a>
                <?php endif; ?>
                
                <a href="<?= base_url('cart')?>" type="button" class="text-decoration-none border-0 bg-transparent hover-fx-1 d-flex flex-row align-items-center">
                    <span style="width: 1.5rem; height: 1.5rem;" id="cartItemsCountIcon" class="d-flex align-items-center justify-content-center border border-2 rounded-circle text-shadow fw-bold bg-warning text-light" id="cart-count"><?= esc(session()->get('cart_items_count') ?? 0) ?></span>
                    <i title="Cart" class="text-shadow display-6 text-white fa-solid fa-cart-shopping"></i>
                    <span class="ms-2 text-white text-shadow">Shopping <br> <strong>Cart</strong></span>
                </a>

                <i title="Wishlist" class="d-flex align-items-center text-shadow display-6 text-white hover-fx-1 fa-solid fa-bookmark"></i>
            </div>
        </div>
    </div>
</header>