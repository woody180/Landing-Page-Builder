import SketchEngine from '../SketchEngine.js';

export default class Slideshow extends SketchEngine {

    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {};


    execute = [];


    selectors = {
        openModalButton: '.ld-element-manage[data-type="slideshow"]'
    };


    html = {
        boilerplate() {
            return `
    <li class="uk-padding-small uk-background-muted uk-border-rounded uk-margin-bottom">
        <div>
            <div class="uk-flex uk-flex-right">
                <button data-index="<?= $index ?>" uk-icon="icon: check;" class="ld-save-slideshow uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Save item;"></button>
                <a data-index="<?= $index ?>" href="#" uk-icon="icon: move;" class="ld-sort-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Sort item;"></a>
                <a data-index="<?= $index ?>" href="#" uk-icon="icon: trash;" class="ld-delete-slideshow-item uk-icon-button" uk-tooltip="pos: left; title: Delete item;"></a>
            </div>

            <div class="uk-flex uk-flex-bottom uk-flex-between">
                <a href="#" class="object-cover uk-display-block" style="height: 50px;">
                    <input type="hidden" name="logo" value="<?= $item->logo ?>">
                    <img class="preview-image" style="height: 100%;" class="uk-display-block " src="${this.variables.baseurl}/assets/images/not-found.png" />
                </a>
                
                <a href="#" class="ld-add-slideshow-image uk-button uk-button-primary uk-button-small">Add image</a>
            </div>

            <div class="uk-margin-top">
                <label for="" class="uk-form-label">Url address</label>
                <input name="url" class="uk-textarea uk-form-small uk-border-rounded" style="width: 100%" value="<?= $item->url ?>" />
            </div>
        </div>
    </li>`
        }
    }


    catchDOM() {}


    bindEvents() {
        this.lib(this.selectors.openModalButton).on('click', this.functions.openModal.bind(this));
    }


    functions = {
        openModal(e)
        {
            e.preventDefault();
            
            const pageurl = document.getElementById('meta-location').getAttribute('content');
            console.log(pageurl);

            fetch(this.variables.baseurl + `/load-editable-element/slideshow?pageurl=${pageurl}`, {
                method: 'get',
                headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(view => view.text())
            .then(view => {
                const modal = UIkit.modal.dialog(view);
                modal.$el.classList.add('uk-modal-container');
                modal.$el.firstElementChild.classList.add('uk-modal-body');
                modal.$el.firstElementChild.classList.add('uk-border-rounded');
            });
        },
    }

}