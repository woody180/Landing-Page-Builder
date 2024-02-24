<ul class="uk-list uk-list-divider">
    <?php foreach ($sections as $section): ?>
    <li>
        <div class="uk-flex uk-flex-middle uk-flex-between">
            <div><?= $section['title'] ?></div>
            
            <div>
                <a uk-tooltip="pos: left; title: Show / Hide Section" data-sec-id="<?= $section['id'] ?>" class="uk-icon-button ld-toggle-section-visibility <?= $section['show'] ? 'show' : '' ?>" href="#" uk-icon="icon: <?= $section['show']  ? 'eye' : 'eye-slash' ?>"></a>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
