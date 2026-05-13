<?= $this->extend('Home') ?>

    <?= $this->section('layout-content') ?>
        <section class="homepage-products w-100 flex-grow-1 min-vh-100 px-3 py-4 py-lg-5">
            <div id="billing-container" class="d-flex min-vh-100 w-100 justify-content-center align-items-start p-0">
            </div>
        </section>
    <?= $this->endSection() ?>
        <?= $this->section('scripts') ?>
            <script defer src="<?= base_url('js/sessionSet.js') ?>"></script>
    <?= $this->endSection() ?>
