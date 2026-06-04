<script id="admin-boot-data" type="application/json">
    <?= json_encode([
            'tags' => $adminInfo['tags'],
            'products' => $adminInfo['products'],
            'users' => $adminInfo['users'],
        ], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?>
</script>
