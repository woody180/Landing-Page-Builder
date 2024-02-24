<?php $this->layout('partials/template', ['title' => $title, 'theme' => 'uk-background-secondary']) ?>

<?= $this->start('mainSection') ?>
    <section class="uk-section uk-section-large">
        <div class="uk-container uk-container-small">
            
            <div class="uk-card uk-card-default">
                <div class="uk-card-body">
                    
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <p class="uk-text-lead"><?= App\Engine\Libraries\Languages::translate('auth.update_account') ?></p>
                        <a href="<?= baseUrl("users/profile/" . $_SESSION['userid']) ?>"><span uk-icon="icon: arrow-left; ratio: 1"></span> Go to profile page</a>
                    </div>
                    
                    <?php if (hasFlashData('error')): ?>
                    <div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p><?= getFlashData('error'); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (hasFlashData('message')): ?>
                    <div class="uk-alert-info" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p><?= getFlashData('message'); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (hasFlashData('success')): ?>
                    <div class="uk-alert-success" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p><?= getFlashData('success'); ?></p>
                    </div>
                    <?php endif; ?>

                    <form enctype="multipart/form-data" id="alter-login-form" class="alter-login-form uk-grid-medium uk-child-width-1-1k" uk-grid action="<?= baseUrl("users/account/" . $_SESSION['userid']) ?>" method="POST" accept-charset="utf-8">
                        
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="name" class="uk-form-label">Name</label>
                            <input id="name" type="text" name="name" class="uk-input" value="<?= $user->name ?>">
                            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->name ?? []) ?></p>
                        </div>
                        
                        <div>
                            <label for="username" class="uk-form-label">Username</label>
                            <input id="username" type="text" name="username" class="uk-input" value="<?= $user->username ?>">
                            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->username ?? []) ?></p>
                        </div>
                        
                        <div>
                            <label for="email" class="uk-form-label">eMail</label>
                            <input id="email" type="email" name="email" class="uk-input" value="<?= $user->email ?>">
                            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->email ?? []) ?></p>
                        </div>
                        
                        <div>
                            <input type="hidden" name="avatar_hidden" value="<?= $user->avatar ?>">
                            <label for="" class="uk-form-label">Profile image</label>
                            <div class="uk-placeholder uk-margin-remove uk-text-center">
                                <span uk-icon="icon: cloud-upload"></span>
                                <span class="uk-text-middle">Set profile image -</span>
                                <div uk-form-custom>
                                    <input name="avatar" type="file">
                                    <span class="uk-link">Select avatar</span>
                                </div>
                            </div>
                            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->avatar ?? []) ?></p>
                        </div>
                    
                        <div>
                            <label for="password" class="uk-form-label">Password</label>
                            <input id="password" type="password" name="password" class="uk-input" value="">
                            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->password ?? []) ?></p>
                        </div>
                        
                        <div>
                            <label for="password" class="uk-form-label">Password repeat</label>
                            <input id="password" type="password" name="password_repeat" class="uk-input" value="">
                            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->password_repeat ?? []) ?></p>
                        </div>
                        
                        <div id="account-buttons-set" class="uk-flex uk-flex-between uk-flex-middle">
                            <button class="uk-button uk-button-primary" type="submit">Update account</button>
                            
                            <a href="<?= baseUrl("users/profile/" . $_SESSION['userid']) ?>"><span uk-icon="icon: arrow-left; ratio: 1"></span> Go to profile page</a>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </section>

<?= $this->stop(); ?>