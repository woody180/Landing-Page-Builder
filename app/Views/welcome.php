<?= $this->layout('layouts/template', ['title' => $title]) ?>

<?= $this->start('mainSection') ?>

<?php if ($section->banner->show): ?>
<!--Banner section-->
<div data-section-id="<?= $section->banner->id ?>" class="ld-hero uk-position-relative uk-flex uk-flex-middle ld-set-editable-icon" data-bg="<?= background($section, 'banner') ?>">
    <div class="ld-color-layer"></div>

    <div class="uk-container uk-position-relative uk-light" data-style="width: 100%;" data-responsive="max-width[980]; style[margin-top: 120px;]" >
        <div class="uk-child-width-1-2@m uk-width-1-1 uk-flex-middle" uk-grid>

            <div>
                <div data-responsive="max-width[980]; style[text-align: center;]" >
                    <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'wipe-tags="true" editor-tools="false" alias="section.'.$section->banner->id.'.body.title"' : '' ?>>
                        <h1 class="uk-heading-small uk-text-uppercase <?= tinyClass('child') ?>" data-responsive="max-width[980]; style[font-size: 20px;]" ><?= $section->banner->body->title ?></h1>
                    </div>
                   
                    <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'wipe-tags="true" editor-tools="false" alias="section.'.$section->banner->id.'.body.content"' : '' ?>>
                        <p class="uk-text-large uk-margin-remove <?= tinyClass('child') ?>"><?= $section->banner->body->content ?></p>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-position-relative <?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->banner->id.'.body.artwork"' : '' ?> data-responsive="max-width[980]; style[width: 320px; margin: auto;]">
                    <div class="<?= tinyClass('child') ?>">
                        <?= relevantPath($section->banner->body->artwork) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</div>
<?php endif; ?>




<?php if ($section->cards->show): ?>
<!--Cards section-->
<section data-section-id="<?= $section->cards->id ?>" class="uk-section ld-set-editable-icon" data-background-color="<?= $section->cards->color ?>" data-bg="<?= background($section, 'cards') ?>">
    <div class="uk-container">

        
        <div class="uk-margin-large-bottom">
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'wipe-tags="true" editor-tools="false" alias="section.'.$section->cards->id.'.body.title"' : '' ?>>
                <h1 class="<?= tinyClass('child') ?>"><?= $section->cards->body->title ?></h1>
            </div>
            
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->cards->id.'.body.description"' : '' ?>>
                <p class="<?= tinyClass('child') ?>"><?= $section->cards->body->description ?></p>
            </div>
        </div>
            

        <div class="uk-grid-match uk-child-width-1-4@l uk-child-width-1-2@m uk-child-width-1-1" uk-grid>
            
            <?php foreach ($section->cards->body->cards as $key => $card): ?>
            <div>
                <div 
                    class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded stroke-top ld-set-editable-icon" 
                    data-section-id="<?= $section->cards->id ?>" 
                    data-background-color="<?= $card->color ?>" 
                    data-element-path="<?= 'section.'.$section->cards->id.'.body.cards.'.$key.'"' ?>"
                    data-bg="<?= background($section, $section->cards->body->cards[$key]->background, TRUE) ?>" 
                    data-text-color="<?= $card->text_color ?>">
                    
                    <h3 class="uk-card-title <?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->cards->id.'.body.cards.'.$key.'.title"' : '' ?>>
                        <span uk-icon="icon: bolt"></span>
                        <span class="<?= tinyClass('child') ?>"><?= $card->title ?></span>
                    </h3>
                    
                    <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->cards->id.'.body.cards.'.$key.'.body"' : '' ?>>
                        <p class="<?= tinyClass('child') ?>"><?= relevantPath($card->body) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>




<?php if ($section->parallax->show): ?>
<!--Parallax section-->
<div 
    data-section-id="<?= $section->parallax->id ?>"
    data-responsive="max-width[980]; style[text-align: center; height: 600px;]" 
    id="ld-paralax-section" 
    class="uk-height-large uk-background-cover uk-light uk-position-relative uk-flex uk-flex-middle ld-set-editable-icon" 
    uk-parallax="bgy: 150" 
    data-style="background-image: url('<?= background($section, 'parallax') ?>');" >
    <div class="ld-color-layer"></div>
    
    <div class="uk-container" data-style="width: 100%;">
        <div class="uk-child-width-1-2@m" uk-grid>
            <div>
                <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->parallax->id.'.body.artwork"' : '' ?>>
                    <div class="<?= tinyClass('child') ?>" data-responsive="max-width[980]; style[width: 200px; display: block; margin: auto;]">
                        <?= relevantPath($section->parallax->body->artwork) ?>
                    </div>
                </div>
            </div>

            <div class="uk-flex uk-flex-middle">
                <div>
                    <div class="ld-parallax-content">
                        
                        <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->parallax->id.'.body.title"' : '' ?>>
                            <h3 class="<?= tinyClass('child') ?>"><?= $section->parallax->body->title ?></h3>
                        </div>

                        <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->parallax->id.'.body.description"' : '' ?>>
                            <p class="<?= tinyClass('child') ?>"><?= $section->parallax->body->description ?></p>
                        </div>

                        <div>
                            <a target="_blank" href="https://velobet.com/en/registration" class="uk-button uk-button-primary"><?= $section->parallax->body->button ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>





<?php if ($section->about->show): ?>
<!--About section-->
<section 
    data-section-id="<?= $section->about->id ?>" class="uk-section ld-set-editable-icon lg-section-semi-color uk-position-relative" 
    data-background-color="<?= $section->about->color ?>" 
    data-text-color="<?= $section->about->text_color ?>" 
    data-bg="<?= background($section, 'about') ?>" >
    <div class="uk-container">

        <div uk-grid class="uk-child-width-1-2@m uk-flex-middle uk-position-relative" data-style="z-index: 1">
            
            <div>
                <div>
                    <div data-responsive="max-width[980]; style[text-align: center;]">
                        
                        <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->about->id.'.body.meta"' : '' ?>>
                            <p class="uk-text-meta <?= tinyClass('child') ?>"><?= $section->about->body->meta ?></p>
                        </div>
                        
                        <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->about->id.'.body.title"' : '' ?>>
                            <h3 class="uk-heading-small <?= tinyClass('child') ?>"><?= $section->about->body->title ?></h3>
                        </div>
                        
                        <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->about->id.'.body.description"' : '' ?>>
                            <p class="<?= tinyClass('child') ?>"><?= $section->about->body->description ?></p>
                        </div>
                    </div>
                    
                    <div class="uk-list <?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->about->id.'.body.list"' : '' ?>>
                        <ul class="<?= tinyClass('child') ?>">
                            <?= relevantPath($section->about->body->list); ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div>
                <div>
                    
                    <div class="uk-child-width-1-2@m uk-grid-match" uk-grid>

                        <?php foreach ($section->about->body->cards as $key => $card): ?>
                        <div>
                            <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-card-hover ld-set-editable-icon" 
                            data-section-id="<?= $section->about->id ?>" 
                            data-background-color="<?= $card->color ?>" 
                            data-element-path="<?= 'section.'.$section->about->id.'.body.cards.'.$key.'"' ?>"
                            data-bg="<?= background($section, $card->background, true) ?>" 
                            data-text-color="<?= $card->text_color ?>">
                            
                            
                                <!--<a href="<?= '' //$card->url ?>" class="spread"></a>-->
                                <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->about->id.'.body.cards.'.$key.'.title"' : '' ?>>
                                    <h3 class="uk-card-title uk-margin-remove <?= tinyClass('child') ?>"><?= $card->title ?></h3>
                                </div>
                                
                                <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->about->id.'.body.cards.'.$key.'.body"' : '' ?>>
                                    <p class="<?= tinyClass('child') ?>"><?= relevantPath($card->body); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                    
                </div>
            </div>
            
        </div>

    </div>
</section>
<?php endif; ?>




<?php if ($section->stats->show): ?>
<!--Stats section-->
<section 
    data-section-id="<?= $section->stats->id ?>" class="uk-section ld-set-editable-icon" 
    data-background-color="<?= $section->stats->color ?>" 
    data-text-color="<?= $section->stats->text_color ?>"
    data-bg="<?= background($section, 'stats') ?>" >
    <div class="uk-container">

        <div class="uk-margin-auto uk-width-5-6@m uk-width-1-1 uk-text-center">
            
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->stats->id.'.body.title"' : '' ?>>
                <h3 class="uk-heading-small <?= tinyClass('child') ?>"><?= $section->stats->body->title ?></h3>
            </div>
           
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->stats->id.'.body.description"' : '' ?>>
                <p class="uk-text-large <?= tinyClass('child') ?>"><?= $section->stats->body->description ?></p>
            </div>
            
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'alias="section.'.$section->stats->id.'.body.image" editor-tools="true" default-tools="true"' : '' ?>>
                <div class="<?= tinyClass('child') ?>">
                    <?= relevantPath($section->stats->body->image) ?>
                </div>
            </div>
        </div>
        
    </div>
</section>
<?php endif; ?>




<?php if ($section->faq->show): ?>
<!--FAQ section-->
<section data-section-id="<?= $section->faq->id ?>" class="uk-section ld-set-editable-icon" data-background-color="<?= $section->faq->color ?>" data-bg="<?= background($section, 'faq') ?>">
    <div class="uk-container">
        
        <div class="uk-margin-medium-bottom">
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'wipe-tags="true" editor-tools="false" alias="section.'.$section->faq->id.'.body.title"' : '' ?>>
                <h3 class="uk-margin-small uk-heading-small <?= tinyClass('child') ?>"><?= $section->faq->body->title ?></h3>
            </div>
            
            <div class="<?= tinyClass('parent') ?>" <?= checkAuth([1]) ? 'wipe-tags="true" editor-tools="false" alias="section.'.$section->faq->id.'.body.description"' : '' ?>>
                <p class="uk-margin-remove <?= tinyClass('child') ?>"><?= $section->faq->body->description ?></p>
            </div>
        </div>


        <div class="ld-accordion-wrapper ld-element">
            <?= elementEdit('accordion') ?>
            <div>
                <?= $this->insert("elements/accordion", ["section" => $section]) ?>
            </div>
        </div>
        
    </div>
</section>
<?php endif; ?>





<?php if ($section->partners->show): ?>
<!--Partners section-->
<section data-section-id="<?= $section->partners->id ?>" class="uk-section ld-set-editable-icon ld-logo-slider" data-background-color="<?= $section->partners->color ?>" data-bg="<?= background($section, 'partners') ?>">
    <div class="uk-container">
        
        <div class="ld-slider-wrapper ld-element">
            <?= elementEdit('slider') ?>
        
            <div>
                <?= $this->insert("elements/slider", ["section" => $section]) ?>
            </div>
        </div>
        
    </div>
</section>
<?php endif; ?>

<?= $this->stop() ?>



<?= $this->start('footer') ?>
    <?php if ($section->footer->show): ?>
        <?= $this->insert('partials/footer', ['section' => $section]) ?>
    <?php endif; ?>
<?= $this->stop(); ?>