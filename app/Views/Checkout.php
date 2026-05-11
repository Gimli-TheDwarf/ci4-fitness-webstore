<?= $this->extend('Home') ?>

    <?= $this->section('layout-content') ?>
        <div id="checkout-container" class="d-flex min-vh-100 w-100 justify-content-center align-items-start p-3 p-md-4">

        </div>
    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>
            <script defer src="<?= base_url('js/sessionSet.js') ?>"></script>
    <?= $this->endSection() ?>
