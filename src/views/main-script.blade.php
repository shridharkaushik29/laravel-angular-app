<script>
    <?php
    /** @var Shridhar\Angular\App $app */
    $app->js_vars()->each(function ($value, $name) {
        if (!is_bool($value) && !is_integer($value)) {
            $value = "\"$value\"";
        } elseif (is_iterable($value)) {
            $value = json_encode($value);
        }
        echo "var $name = $value;";
    });
    ?>
</script>
