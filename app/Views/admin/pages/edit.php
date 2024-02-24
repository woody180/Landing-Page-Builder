<?= $this->layout('layouts/template', ['title' => 'All pages', 'theme' => 'uk-background-secondary']) ?>

<?= $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container min-height uk-margin-large-top">
        
        
        <div class="uk-margin-large-bottom uk-flex uk-flex-middle uk-flex-between">
            
            <div>
                
            </div>
            
            <div>
                <a class="uk-button uk-button-primary" href="<?= baseUrl("pages") ?>">Go to pages list</a>
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

        
        
        <form action="<?= baseUrl("page/{$page->id}") ?>" method="POST" class="uk-child-width-1-1" uk-grid>

            <?= setMethod("put") ?>

            <div>
                <label for="" class="uk-form-label">Title</label>
                <input type="text" name="title" class="uk-input uk-border-rounded" value="<?= $page->title ?>" required>
            </div>

            <div>
                <label for="" class="uk-form-label">Page url</label>
                <input type="text" name="url" class="uk-input uk-border-rounded" value="<?= $page->url ?>" required>
            </div>
        
            <div>
                <label for="" class="uk-form-label">Description</label>
                <textarea name="description" class="uk-textarea uk-height-small uk-border-rounded" required><?= $page->description ?></textarea>
            </div>
            
            <div>
                <button class="uk-width-1-1 uk-button uk-button-primary">Save page</button>
            </div>
        </form>
        
    </div>
</section>

<?= $this->stop() ?>




