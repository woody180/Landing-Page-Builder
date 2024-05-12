<div class="uk-flex uk-flex-between uk-flex-middle uk-margin-bottom">
    <div><h2 class="uk-modal-title uk-margin-remove">Partners Management</h2></div>
    
    <div>
        <button class="uk-button uk-button-primary ld-add-new-slide">Add new item</button>
    </div>
</div>


<form>
    <ul class="uk-list uk-list-divider" id="ld-slider-management" uk-sortable="handle: .ld-sort-item" data-id="<?= $id ?>">
        <?php foreach ($data as $index => $item): ?>
        <li class="uk-padding-small uk-background-muted uk-border-rounded uk-margin-bottom">
            <div>
                <div class="uk-flex uk-flex-right">
                    <button data-index="<?= $index ?>" uk-icon="icon: check;" class="ld-save-slider uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Save item;"></button>
                    <a data-index="<?= $index ?>" href="#" uk-icon="icon: move;" class="ld-sort-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Sort item;"></a>
                    <a data-index="<?= $index ?>" href="#" uk-icon="icon: trash;" class="ld-delete-sldie uk-icon-button" uk-tooltip="pos: left; title: Delete item;"></a>
                </div>

                <div class="uk-flex uk-flex-bottom uk-flex-between">
                    <a href="#" class="object-cover uk-display-block" style="height: 50px;">
                        <input type="hidden" name="logo" value="<?= $item->logo ?>">
                        <img class="preview-image" style="height: 100%;" class="uk-display-block " src="<?= assetsUrl("tinyeditor/filemanager/files/{$item->logo}") ?>" />
                    </a>
                    
                    <a href="#" class="ld-add-slide-image uk-button uk-button-primary uk-button-small">Add image</a>
                </div>

                <div class="uk-margin-top">
                    <label for="" class="uk-form-label">Url address</label>
                    <input name="url" class="uk-textarea uk-form-small uk-border-rounded" style="width: 100%" value="<?= $item->url ?>" />
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>


    <div class="uk-modal-footer uk-text-right">
        <button class="uk-button uk-button-primary ld-save-slider" type="submit">Save</button>
    </div>
</form>