<?= $this->extend('Home') ?>

<?= $this->section('layout-content') ?>


<div id="cart-container" class="d-flex justify-content-center p-0 m-0 bg-dark bg-gradient h-100 w-100 ">
</div>

<script id="cart-items" type="application/json">
    <?= json_encode($cartItems) ?>
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>

    </script>
<?= $this->endSection() ?>