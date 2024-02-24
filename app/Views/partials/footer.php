<footer data-section-id="<?= $section->footer->id ?>" id="ld-footer" class="uk-section uk-light ld-set-editable-icon" data-background-color="<?= $section->footer->color ?>" data-bg="<?= background($section, 'footer') ?>">
    <div class="uk-container uk-text-center">
        
        <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'wipe-tags="true" alias="section.'.$section->footer->id.'.body.body"' : '' ?>>
            <p class="<?= tinyClass('child') ?>"><?= $section->footer->body->body ?></p>
        </div>


        <div class="uk-flex uk-flex-center ld-socils">
            <a class="uk-icon-button" href="<?= initModel('settings')->getSettings("facebookurl") ?>" uk-icon="icon: facebook;"></a>
            <a class="uk-icon-button" href="<?= initModel('settings')->getSettings("instagramurl") ?>" uk-icon="icon: instagram;"></a>
            <a class="uk-icon-button" href="<?= initModel('settings')->getSettings("youtubeurl") ?>" uk-icon="icon: youtube;"></a>
            <a class="uk-icon-button" href="<?= initModel('settings')->getSettings("twitterurl") ?>" uk-icon="icon: twitter;"></a>
        </div>
    </div>
</footer>