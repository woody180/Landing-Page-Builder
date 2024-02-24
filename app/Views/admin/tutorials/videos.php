<?= $this->layout('layouts/template', ['title' => 'Site management tutorials', 'theme' => 'uk-background-secondary']) ?>

<?= $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container min-height uk-margin-large-top">
        
        
        <div class="uk-margin-large-bottom uk-flex uk-flex-middle uk-flex-between">
            
            <div>
                
            </div>
            
            <div>
                <a class="uk-button uk-button-primary" href="<?= baseUrl("page/new") ?>">Website management</a>
            </div>
            
        </div>
        
        
        <?php if (hasFlashData('success')): ?>
        <div class="uk-alert-success uk-margin-medium-bottom" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getFlashData('success') ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (hasFlashData('message')): ?>
        <div class="uk-alert-primary uk-margin-medium-bottom" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getFlashData('message') ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (hasFlashData('error')): ?>
        <div class="uk-alert-danger uk-margin-medium-bottom" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getFlashData('message') ?></p>
        </div>
        <?php endif; ?>

        
        
        <div class="uk-child-width-1-3@m uk-child-width-1-1 uk-grid-match" uk-grid>
        
            <?php foreach ($videos as $video): ?>
            <div>
                <a href="<?= assetsUrl("tinyeditor/filemanager/files/tutorials/" . basename($video)) ?>" target="_blank" class="uk-display-block uk-card uk-card-default uk-card-body uk-border-rounded">
                    <p class="uk-text-medium"><?= basename($video, ".mp4") ?></p>
                </a>
            </div>
            <?php endforeach; ?>
        
        </div>
        
    </div>
</section>

<?= $this->stop() ?>