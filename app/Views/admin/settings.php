<?= $this->layout('layouts/template', ['title' => $title, 'theme' => 'uk-background-secondary']) ?>

<?= $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container min-height uk-margin-large-top">
        
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
        
        <form action="<?= baseUrl("admin/settings") ?>" method="POST" uk-grid class="uk-child-width-1-1">
            <?= setMethod('put') ?>
            
            <div>
                <label class="uk-form-label">Meta title</label>
                <input name="title" type="text" class="uk-input uk-border-rounded" value="<?= initModel('settings')->getSettings("title") ?>">
            </div>
            
            
            <div>
                <label class="uk-form-label">Meta description</label>
                <textarea name="description" class="uk-textarea uk-border-rounded"><?= initModel('settings')->getSettings("description") ?></textarea>
            </div>
            
            
            <div>
                <label class="uk-form-label">Meta keywords</label>
                <textarea name="keywords" class="uk-textarea uk-border-rounded"><?= initModel('settings')->getSettings("keywords") ?></textarea>
            </div>
            
            
            <div>
                <div>
                    <p class="">Site logo</p>
                    
                    <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-flex uk-flex-middle uk-flex-between">
                        <input type="hidden" name="logo" value="<?= initModel('settings')->getSettings("logo") ?>">
                        <img width="200" src="<?= assetsUrl("tinyeditor/filemanager/files/") . initModel('settings')->getSettings("logo") ?>" alt="alt"/>
                        
                        <button class="uk-button uk-button-primary ld-change-logo-button">Add logo</button>
                    </div>
                </div>
            </div>
            
            
            <div>
                <div>
                    <p class="">Site logo</p>
                    
                    <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-flex uk-flex-middle uk-flex-between">
                        <input type="hidden" name="favicon" value="<?= initModel('settings')->getSettings("favicon") ?>">
                        <img width="25" src="<?= assetsUrl("tinyeditor/filemanager/files/") . initModel('settings')->getSettings("favicon") ?>" alt="alt"/>
                        
                        <button class="uk-button uk-button-primary ld-change-favicon-button">Add favicon</button>
                    </div>
                </div>
            </div>
            
            
            <div>
                <div>
                    <p class="">Social icons</p>
                    
                    <div uk-grid class="uk-child-width-1-1 uk-grid-medium">
                        <div>
                            <label for="" class="uk-form-label">Facebook</label>
                            <input class="uk-input uk-border-rounded" type="text" name="facebookurl" value="<?= initModel('settings')->getSettings("facebookurl") ?>">
                        </div>
                        <div>
                            <label for="" class="uk-form-label">Instagram</label>
                            <input class="uk-input uk-border-rounded" type="text" name="instagramurl" value="<?= initModel('settings')->getSettings("instagramurl") ?>">
                        </div>
                        <div>
                            <label for="" class="uk-form-label">YouTube</label>
                            <input class="uk-input uk-border-rounded" type="text" name="youtubeurl" value="<?= initModel('settings')->getSettings("youtubeurl") ?>">
                        </div>
                        <div>
                            <label for="" class="uk-form-label">Twitter</label>
                            <input class="uk-input uk-border-rounded" type="text" name="twitterurl" value="<?= initModel('settings')->getSettings("twitterurl") ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div>
                <div>
                    <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Save settings</button>
                </div>
            </div>
        </form>
        
    </div>
</section>


<script type="module" src="<?= assetsUrl('js/bootstrap.js') ?>" type="module"></script>

<?= $this->stop() ?>




