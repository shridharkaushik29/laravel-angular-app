<?php if ($app->getConfig("html5Mode")): ?>
    <base href="<?= $app->siteUrl() ?>/">
<?php endif; ?>
<script>
    <?php
    $app->jsVars()->each(function ($value, $name) {
        if (!is_bool($value) && !is_integer($value)) {
            $value = "\"$value\"";
        }
        echo "var $name = $value;";
    });
    ?>
</script>