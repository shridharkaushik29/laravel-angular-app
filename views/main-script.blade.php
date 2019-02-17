<script>
    <?php
    $app->js_vars()->each(function ($value, $name) {
        if (!is_bool($value) && !is_integer($value)) {
            $value = "\"$value\"";
        }
        echo "var $name = $value;";
    });
    ?>
</script>
