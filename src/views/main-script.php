<?php if ($app->getConfig("html5Mode")): ?>
    <base href="<?= $app->siteUrl() ?>/">
<?php endif; ?>
<script>
    var $appName = "<?= $app->name() ?>";
    var $appTitle = "<?= $app->title() ?>";
    var $siteUrl = "<?= $app->siteUrl() ?>/";
    var $templatesUrl = "<?= $app->templatesUrl() ?>/";
    var $templatesExtension = "<?= $app->templatesExtension() ?>";
    var $servicesUrl = "<?= $app->servicesUrl() ?>/";

    function getTemplateUrl(path) {
        var base = $templatesUrl;
        var ext = $templatesExtension;
        var url = path;
        if (base) {
            url = base + url;
        }
        if (ext) {
            url += "." + ext;
        }
        return  url;
    }

    angular.module($appName, <?= $app->dependencies() ?>)
<?php if ($app->getConfig("html5Mode")): ?>
        .config($locationProvider => {
            $locationProvider.html5Mode(true)
        })
<?php endif; ?>
</script>