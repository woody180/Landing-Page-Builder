<?php if (property_exists($section, 'faq') && $section->faq->show): ?>
<ul uk-accordion>
    <?php foreach ($section->faq->body->items as $faq): ?>
    <li class="<?= $faq->open ? 'uk-open' : ''?>">
        <a class="uk-accordion-title" href><?= $faq->title ?></a>
        <div class="uk-accordion-content">
            <p><?= nl2br($faq->answer) ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>