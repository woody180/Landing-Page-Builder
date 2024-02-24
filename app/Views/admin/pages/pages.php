<?= $this->layout('layouts/template', ['title' => 'All pages', 'theme' => 'uk-background-secondary']) ?>

<?= $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container min-height uk-margin-large-top">
        
        
        <div class="uk-margin-large-bottom uk-flex uk-flex-middle uk-flex-between">
            
            <div>
                
            </div>
            
            <div>
                <a class="uk-button uk-button-primary" href="<?= baseUrl("page/new") ?>">Create new page</a>
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

        
        
        <ul class="uk-list uk-list-divider">
            <?php foreach ($data->pages as $page): ?>
            <li>
                <div class="uk-flex uk-flex-middle uk-flex-between">
                    <div>
                        <div><strong>ID:</strong> <?= $page->id ?></div>
                        <div><strong>Title:</strong> <?= $page->title ?></div>
                        <div><strong>URL:</strong> <?= $page->url ?></div>
                    </div>
                    
                    <div class="uk-flex">
                        <a uk-tooltip="Go to page" class="uk-icon-button uk-margin-small-right" uk-icon="icon: link;" href="<?= (empty($page->url) || $page->url === '/') ? baseUrl() : baseUrl("page/" . $page->url) ?>"></a>
                        <a uk-tooltip="Copy page URL" class="copy-url-button uk-icon-button uk-margin-small-right" uk-icon="icon: copy;" href="<?= (empty($page->url) || $page->url === '/') ? baseUrl() : baseUrl("page/" . $page->url) ?>"></a>
                        <a uk-tooltip="Edit page" class="uk-icon-button uk-margin-small-right" uk-icon="icon: pencil;" href="<?= baseUrl("page/{$page->id}/edit") ?>"></a>
                        <form onsubmit="return confirm('Are you sure?!')" action="<?= baseUrl("page/{$page->id}") ?>" method="post">
                            <?= setMethod('delete') ?>
                            <button type="submit" uk-tooltip="Detele page" class="uk-icon-button" uk-icon="icon: trash;"></button>
                        </form>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        
    </div>
</section>

<?= $this->stop() ?>




