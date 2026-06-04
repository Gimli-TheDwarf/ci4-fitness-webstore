<?= $this->extend('Home') ?>

    <?= $this->section('layout-content') ?>
        <section class="homepage-products w-100 flex-grow-1 min-vh-100 px-3 py-4 py-lg-5">
            <div id="account-info-container" class="d-flex min-vh-100 w-100 justify-content-center align-items-start p-0">
            </div>
        </section>

    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>
        <script id="account-info-items" type="application/json">
            <?= json_encode([
            'user_info' => $userInformation
            ], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>
        </script>
    <?= $this->endSection() ?>
