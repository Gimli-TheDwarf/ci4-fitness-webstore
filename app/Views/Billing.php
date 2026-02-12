<?= $this->extend('Home') ?>

    <?= $this->section('layout-content') ?>
        <div id="billing-container" class="bg-secondary d-flex min-vh-100 w-100 justify-content-center">

        </div>
    <?= $this->endSection() ?>
        <?= $this->section('scripts') ?>
            <script defer src="<?= base_url('js/sessionSet.js') ?>"></script>
    <?= $this->endSection() ?>