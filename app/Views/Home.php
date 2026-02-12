<html>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?= view('partials/headSection') ?>

    <body class="no-scrollbar d-flex flex-column min-vh-100">

    <?= view('partials/header', ['username' => $username]) ?>

    <?= view('partials/toast') ?>
    <?= view('partials/modalWindow') ?>
    
    <div id="layout-container" class="w-100 flex-grow-1 d-flex flex-column align-items-center bg-blue-gray bg-gradient">
        <?= $this->renderSection('layout-content') ?>
    </div>

    <?= view('partials/footer') ?>

    
    <?= view('partials/loadInfo') ?>

    <?= $this->renderSection('scripts') ?>

    </body>
</html>
    
<script id="boot-data" type="application/json">
    <?= json_encode([
        'tags'     => $info['tags'],
        'products' => $info['products'],
    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>
</script>

<script src="<?= base_url('js/toastScript.js') ?>" defer></script> <!-- TOAST SCRIPT -->

<script src="<?= base_url('js/modalScript.js')?>" defer></script> <!-- MODAL SCRIPT -->