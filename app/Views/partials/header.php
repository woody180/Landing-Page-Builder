<header id="ld-main-header" class="<?= $theme ?>">
    <div class="uk-container uk-flex uk-flex-middle uk-flex-between">
        <a data-responsive="max-width[980]; style[width: 170px]" href="<?= baseUrl() ?>" id="ld-main-logo" title="<?= APPNAME ?>">
            <img src="<?= assetsUrl("tinyeditor/filemanager/files/") . initModel('settings')->getSettings('logo') ?>" alt="<?= APPNAME ?>">
        </a>

        <a class="uk-button uk-button-default" href="#">JOIN NOW</a>
    </div>
</header>