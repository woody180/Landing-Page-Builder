<div class="uk-flex uk-flex-between uk-flex-middle uk-margin-bottom">
    <div><h2 class="uk-modal-title uk-margin-remove">FAQ Management</h2></div>
    
    <div>
        <button class="uk-button uk-button-primary ld-add-new-faq-item">Add new item</button>
    </div>
</div>


<form>
    <ul class="uk-list uk-list-divider" id="ld-faq-management" uk-sortable="handle: .ld-sort-item" data-id="<?= $id ?>">
        <?php foreach ($data as $index => $item): ?>
        <li class="uk-padding-small uk-background-muted uk-border-rounded uk-margin-bottom">
            <div>
                <div>
                    <div class="uk-flex uk-flex-right">
                        <button data-index="<?= $index ?>" uk-icon="icon: check;" class="ld-edit-faq-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Save item;"></button>
                        <a data-index="<?= $index ?>" href="#" uk-icon="icon: move;" class="ld-sort-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Sort item;"></a>
                        <a data-index="<?= $index ?>" href="#" uk-icon="icon: trash;" class="ld-delete-faq-item uk-icon-button" uk-tooltip="pos: left; title: Delete item;"></a>
                    </div>
                    
                    <div>
                        <label for="" class="uk-form-label">Set as open</label>
                        <div>
                            <input type="checkbox" name="open" class="uk-checkbox ld-faq-checkbox-first" value="<?= $item->title ?>" <?= $item->open ? 'checked' : '' ?>>
                        </div>
                    </div>

                    <div>
                        <label for="" class="uk-form-label">Question</label>
                        <input name="title" class="uk-input uk-form-small uk-border-rounded" value="<?= $item->title ?>">
                    </div>

                    <div>
                        <label for="" class="uk-form-label">Answer</label>
                        <textarea name="answer" class="uk-textarea uk-form-small uk-border-rounded" style="width: 100%"><?= $item->answer ?></textarea>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>


    <div class="uk-modal-footer uk-text-right">
        <button class="uk-button uk-button-primary ld-edit-faq-item" type="submit">Save</button>
    </div>
</form>