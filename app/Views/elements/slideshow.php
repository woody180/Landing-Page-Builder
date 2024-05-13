<?php if (property_exists($section, 'slideshow') && $section->slideshow->show): ?>
<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slideshow="ratio: 9:3">

    <div class="uk-slideshow-items">
        <?php foreach ($section->slideshow->body->images as $image): ?>
        <div>
            <img src="<?= assetsUrl("tinyeditor/filemanager/files/" . $image->image) ?>" alt="" uk-cover>
        </div>
        <?php endforeach; ?>
    </div>

    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slideshow-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slideshow-item="next"></a>

</div>
<?php endif; ?>