<script id="admin-boot-data" type="application/json">
    <?= json_encode([
            'tags' => $adminInfo['tags'],
            'products' => $adminInfo['products'],
            'users' => $adminInfo['users'],
        ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?>
</script>