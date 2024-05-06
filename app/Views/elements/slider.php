<?php if (property_exists($section, 'slider') && $section->slider->show): ?>
<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider>

    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-5@m uk-grid">
        <?php foreach ($section->partners->body->items as $item): ?>
        <li>
            <a target="_blank" href="<?= $item->url ?>" class="uk-panel">
                <img src="<?= assetsUrl("tinyeditor/filemanager/files/" . $item->logo) ?>" />
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slider-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slider-item="next"></a>

</div>
<?php endif; ?>