<?php $this->layout('layouts/template', ['title' => $title, 'theme' => 'uk-background-secondary']) ?>

<?= $this->start('mainSection') ?>

<section class="uk-section uk-padding-remove-top">
    
    <div id="alter-profile-banner" class="uk-background-secondary uk-height-medium uk-overflow-hidden">
        <img class="uk-object-cover uk-display-block uk-width-1-1" src="https://picsum.photos/1920/500" alt="Profile banner"/>
    </div>
    
    <div class="uk-container">
        
        <div class="uk-grid" uk-grid>
            
            <div style="top: -100px" class="uk-width-1-3@m uk-background-default uk-position-relative uk-position-z-index uk-margin-auto uk-border-rounded uk-flex uk-flex-column uk-flex-middle uk-card uk-card-body uk-card-hover uk-box-shadow-medium">
                <div class="uk-margin-bottom uk-flex-1">
                    <?php if ($user->avatar): ?>
                        <img width="120" height="120" class="uk-object-cover uk-border-circle" src="<?= assetsUrl("images/avatars/{$user->avatar}") ?>" alt="<?= $user->username ?> avatar"/>
                    <?php else: ?>
                        <img width="120" height="120" class="uk-object-cover" src="<?= assetsUrl("images/avatar_default/default_avatar_". random_int(1, 10) .".svg") ?>" alt="<?= $user->username ?> avatar"/>
                    <?php endif; ?>
                </div>
                
                <?php if ($isSelf): ?>
                <div class="uk-flex uk-flex-center uk-margin-bottom uk-flex-1">
                    <div>
                        <a uk-tooltip="Home" class="uk-button uk-button-group uk-button-default uk-padding-small uk-button-small uk-margin-remove" href="<?= baseUrl() ?>" uk-icon="icon: home; ratio: .8;"></a>
                        <a uk-tooltip="Edit profile" class="uk-button uk-button-group uk-button-default uk-padding-small uk-button-small uk-margin-remove" href="<?= baseUrl("users/account/" . urlSegments(3)) ?>" uk-icon="icon: pencil; ratio: .8;"></a>
                        <a uk-tooltip="Logout" class="uk-button uk-button-group uk-button-default uk-padding-small uk-button-small uk-margin-remove" href="<?= baseUrl("users/logout") ?>" uk-icon="icon: sign-out; ratio: .8;"></a>
                    </div>
                </div>
                <?php endif; ?>
                
                <ul class="uk-margin-remove uk-padding-remove uk-list uk-list-divider uk-list-hyphen uk-flex-1 uk-width-1-1">
                    <li>
                        <b>Name</b>: 
                        <i><?= $user->name ?></i>
                    </li>
                    <li>
                        <b>Username</b>: 
                        <i><?= $user->username ?></i>
                    </li>
                    <li>
                        <b>eMail</b>: 
                        <i><?= $user->email ?></i>
                    </li>
                    <li>
                        <b>Group</b>: 
                        <i><?= $user->usergroups->name ?></i>
                    </li>
                </ul>
            </div>
            
        </div>
        
    </div>
</section>

<?= $this->stop() ?>