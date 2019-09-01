<script>
    <?php
    /** @var Shridhar\Angular\App $app */
    $app->js_vars()->each(function ($value, $name) {
        if (is_object($value) || is_array($value)) {
            $value = json_encode($value);
        } elseif (is_string($value)) {
            $value = "\"$value\"";
        } elseif ($value === null) {
            $value = "null";
        } elseif ($value === false) {
            $value = "false";
        } elseif ($value === true) {
            $value = "true";
        }
        echo "var $name = $value;";
    });
    ?>
</script>
