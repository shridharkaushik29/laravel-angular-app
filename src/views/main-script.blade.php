<script>
    <?php
    /** @var Shridhar\Angular\App $app */
    $app->js_vars()->each(function ($value, $name) {
        if (is_object($value) || is_array($value)) {
            $value = json_encode($value);
        } elseif (!is_bool($value) && !is_integer($value)) {
            $value = "\"$value\"";
        }
        echo "var $name = $value;";
    });
    ?>
</script>
