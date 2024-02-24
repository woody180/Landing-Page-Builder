import SketchEngine from '../../classes/SketchEngine.js';

export default class Slider extends SketchEngine {


    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {};


    execute = [];


    selectors = {
        addButton: '.ld-add-new-slide',
        ulContainer: '#ld-slider-management',
        openModalButton: '.ld-element-manage[data-type="slider"]',
        save: '.ld-save-slider',
        delete: '.ld-delete-sldie',
        addImageButton: '.ld-add-slide-image',
        logoInput: 'input[name="logo"]',
        urlInput: 'input[name="url"]',
        preview: '.preview-image',
        wrapper: '.ld-slider-wrapper div'
    };


    html = {
        boilerplate() {
            return `
 <li class="uk-padding-small uk-background-muted uk-border-rounded uk-margin-bottom">
    <div>
        <div class="uk-flex uk-flex-right">
            <button uk-icon="icon: check;" class="ld-save-slider uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Save item;"></button>
            <a href="#" uk-icon="icon: move;" class="ld-sort-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Sort item;"></a>
            <a href="#" uk-icon="icon: trash;" class="ld-delete-sldie uk-icon-button" uk-tooltip="pos: left; title: Delete item;"></a>
        </div>

        <div class="uk-flex uk-flex-bottom uk-flex-between">
            <a href="#" class="object-cover uk-display-block" style="height: 50px;">
                <input type="hidden" name="logo" value="not-found.png">
                <img class="preview-image" style="height: 100%;" class="uk-display-block " src="${this.variables.baseurl}/assets/images/not-found.png" />
            </a>

            <a href="#" class="ld-add-slide-image uk-button uk-button-primary uk-button-small">Add image</a>
        </div>

        <div class="uk-margin-top">
            <label for="" class="uk-form-label">Url address</label>
            <input name="url" class="uk-textarea uk-form-small uk-border-rounded" style="width: 100%" value="#" />
        </div>
    </div>
</li>`
        }
    }


    catchDOM() {}


    bindEvents() {
        this.lib('body').on('click', this.functions.save.bind(this), this.selectors.save);
        this.lib(this.selectors.openModalButton).on('click', this.functions.openModal.bind(this));
        this.lib('body').on('click', this.functions.addImage.bind(this), this.selectors.addImageButton);
        this.lib('body').on('click', this.functions.delete.bind(this), this.selectors.delete);
        this.lib('body').on('click', this.functions.add.bind(this), this.selectors.addButton);
    }


    functions = {
        
        
        add(e)
        {
            e.preventDefault();
            document.querySelector(this.selectors.ulContainer).insertAdjacentHTML('afterbegin', this.html.boilerplate.call(this));
        },
        
        
        delete(e)
        {
            e.preventDefault();
            if (confirm('Are you sure?')) e.target.closest('li').remove();
        },
        
        
        addImage(e)
        {
            e.preventDefault();
            
            filemanager(files => {
                const file = files[0].split('files/')[1];
                const fullurl = `${this.variables.baseurl}/assets/tinyeditor/filemanager/files/${file}`;
                
                document.querySelector(this.selectors.preview).src = fullurl;
                document.querySelector(`${this.selectors.ulContainer} ${this.selectors.logoInput}`).value = file;
                
            });
        },
        
        
        openModal(e)
        {
            e.preventDefault();
            
            fetch(this.variables.baseurl + '/load-editable-element/slider', {
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
        
        
       
        // Save
        save(e) {
            e.preventDefault();
            
            const elementID = document.querySelector(this.selectors.ulContainer).getAttribute('data-id');
            const alias = `section.${elementID}.body.items`;
            const content = JSON.stringify(this.functions.createObject.call(this));
            
//            console.log(content);
//            return false;
                        
            fetch(`${this.variables.baseurl}/directsave`, {
                method: 'PATCH',
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    alias,
                    params: {},
                    content
                })
            })
            .then(res => res.json())
            .then(res => {

                this.functions.renderElement.call(this, data => {
                    document.querySelectorAll(this.selectors.wrapper).forEach(wrapper => wrapper.innerHTML = data);
                });
                
                UIkit.notification(`<p class="uk-text-samll uk-margin-remove">${res.success}</p>`, {status:'success'});
                
            })
            .catch(err => {
                console.error(err);
                document.getElementById('tiny-loader-animation').remove();
            });
        },
        
        
        createObject() {
            const items = document.querySelectorAll(`${this.selectors.ulContainer} li`);
            const arr = [];
            items.forEach(item => {
                arr.push({
                    logo: item.querySelector('input[name="logo"]').value.trim(),
                    url: item.querySelector('input[name="url"]').value.trim()
                });
            });
            
            return arr;
        },
        
        
        renderElement(callback)
        {
            fetch(`${this.variables.baseurl}/elements/slider`, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.text())
            .then(res => {
                callback(res)
            })
            .catch(err => console.error(err));
        }
        
    }


}