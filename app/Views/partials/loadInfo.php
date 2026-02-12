
<?php if (ENVIRONMENT === 'development'): ?>
    <script type="module" src="http://localhost:3000/src/main.js"></script>
    <script type="module" src="http://localhost:3000/src/admin.js"></script>
<?php else: ?>
    <link   rel="stylesheet" href="<?= base_url('frontend/public/assets/index.css') ?>">
    <script defer src="<?= base_url('frontend/public/assets/index.js') ?>"></script>
<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
