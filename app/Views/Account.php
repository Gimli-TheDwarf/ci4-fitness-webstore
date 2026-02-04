<?= $this->extend('Home') ?>

    <?= $this->section('layout-content') ?>
        <div id="account-info-container" class="bg-secondary d-flex min-vh-100 w-100 justify-content-center">
        </div>

    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>
        <script id="account-info-items" type="application/json">
            <?= json_encode([
            'user_info' => $userInformation
            ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>
        </script>
    <?= $this->endSection() ?>
