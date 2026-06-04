<?= $this->extend('Home') ?>

<?= $this->section('layout-content') ?>


<section class="homepage-products w-100 flex-grow-1 min-vh-100 px-3 py-4 py-lg-5">
    <div id="cart-container" class="d-flex justify-content-center align-items-start p-0 m-0 w-100 flex-grow-1">
    </div>
</section>

<script id="cart-items" type="application/json">
    <?= json_encode($cartItems, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>

    </script>
<?= $this->endSection() ?>
