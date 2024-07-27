<?= $this->layout('layouts/template', ['title' => $page->title, 'theme' => 'uk-background-secondary']) ?>


<?= $this->start('slideshow') ?>

    <?php if (property_exists($section, 'gallery') && $section->gallery->show): ?>
    
        <section class="uk-margin-small-top ld-set-editable-icon ld-slideshow"
            data-section-id="<?= $section->slideshow->id ?>"
            data-background-color="<?= $section->slideshow->color ?>"
            data-bg="<?= background($section, 'slideshow') ?>"
        >

            <div class="ld-slideshow-wrapper ld-element">
                <?= elementEdit('slideshow') ?>

                <?= $this->insert("elements/slideshow", ["section" => $section]); ?>

            </div>
        </section>

    <?php endif; ?>
<?= $this->stop() ?>



<?= $this->start('mainSection') ?>

<!--Stats section-->
<section class="uk-section">
    <div class="uk-container min-height">

        <div class="ld-editable" alias="page.<?= $page->id ?>.body" params='{"type": "html"}'>
            <div class="ld-editable-cage">
                <?= relevantPath($page->body ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel ligula vel leo vulputate convallis fermentum ut nibh. Phasellus aliquet velit ut ex rutrum malesuada eget in elit. Suspendisse at vulputate sem. Proin erat leo, pulvinar at suscipit sollicitudin, commodo ultricies magna. Nullam viverra, velit vel pulvinar lacinia, justo risus imperdiet metus, in aliquet urna mi ac urna. In dapibus rhoncus tincidunt. Morbi sodales dolor nec quam gravida tincidunt. Morbi orci neque, volutpat eget blandit a, malesuada sed arcu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sit amet ligula feugiat, volutpat elit ut, maximus massa. Pellentesque porttitor, felis ac mattis posuere, ex purus ullamcorper dui, nec tincidunt neque elit sed felis. Praesent neque leo, cursus eget porta vel, sagittis ut velit. Ut ultrices aliquam nunc, ut semper ipsum lobortis at. Morbi eu nunc nec ipsum tristique accumsan. Curabitur posuere hendrerit suscipit.') ?>
            </div>
        </div>
        
    </div>
</section>

<?= $this->stop() ?>



<?= $this->start('footer') ?>
    <?php if (property_exists($section, 'footer') && $section->footer->show): ?>
        <?= $this->insert('partials/footer', ['section' => $section]) ?>
    <?php endif; ?>
<?= $this->stop(); ?>
