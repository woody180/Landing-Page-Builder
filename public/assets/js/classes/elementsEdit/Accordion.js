import SketchEngine from '../../classes/SketchEngine.js';

export default class Accordion extends SketchEngine {


    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {};


    execute = [];


    selectors = {
        add: '.ld-add-new-faq-item',
        edit: '.ld-edit-faq-item',
        delete: '.ld-delete-faq-item',
        ulContainer: '#ld-faq-management',
        checkboxMakeFirst: '.ld-faq-checkbox-first',
        wrapper: '.ld-accordion-wrapper div',
        openModalButton: '.ld-element-manage[data-type="accordion"]'
    };


    html = {
        boilerplate() {
            return `
<li class="uk-padding-small uk-background-muted uk-border-rounded uk-margin-bottom">
    <div>
        <div>
            <div class="uk-flex uk-flex-right">
                <button data-index="0" uk-icon="icon: check;" class="ld-edit-faq-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Save item;"></button>
                <a data-index="0" href="#" uk-icon="icon: move;" class="ld-sort-item uk-icon-button uk-margin-small-right" uk-tooltip="pos: left; title: Sort item;"></a>
                <a data-index="0" href="#" uk-icon="icon: trash;" class="ld-delete-faq-item uk-icon-button" uk-tooltip="pos: left; title: Delete item;"></a>
            </div>

            <div>
                <label for="" class="uk-form-label">Set as open</label>
                <div>
                    <input type="checkbox" name="open" class="uk-checkbox ld-faq-checkbox-first" value="">
                </div>
            </div>

            <div>
                <label for="" class="uk-form-label">Question</label>
                <input name="title" class="uk-input uk-form-small uk-border-rounded" value="Answer">
            </div>

            <div>
                <label for="" class="uk-form-label">Answer</label>
                <textarea name="answer" class="uk-textarea uk-form-small uk-border-rounded" style="width: 100%">Answer</textarea>
            </div>
        </div>
    </div>
</li>`
        }
    }


    catchDOM() {}


    bindEvents() {
        this.lib('body').on('click', this.functions.add.bind(this), this.selectors.add);
        this.lib('body').on('click', this.functions.save.bind(this), this.selectors.edit);
        this.lib('body').on('click', this.functions.delete.bind(this), this.selectors.delete);
        this.lib('body').on('change', this.functions.checkboxMakeFirst.bind(this), this.selectors.checkboxMakeFirst);
        this.lib(this.selectors.openModalButton).on('click', this.functions.openModal.bind(this));
    }


    functions = {
        
        openModal(e)
        {
            e.preventDefault();
            
            const pageurl = document.getElementById('meta-location').getAttribute('content');

            fetch(this.variables.baseurl + `/load-editable-element/accordion?pageurl=${pageurl}`, {
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
        
        
        checkboxMakeFirst(e) 
        {           
            document.querySelectorAll(`${this.selectors.ulContainer} ${this.selectors.checkboxMakeFirst}`).forEach(input => input.checked = false   );
            e.target.checked = true;
        },
        
        
        add(e) {
            e.preventDefault();
            
            const ul = e.target.closest('.uk-modal').querySelector('ul.uk-list');
            ul.insertAdjacentHTML('afterbegin', this.html.boilerplate.call(this));
            
            this.functions.reindexItems.call(this)
        },
        
        
        // Save
        save(e) {
            e.preventDefault();
            
            const elementID = document.querySelector(this.selectors.ulContainer).getAttribute('data-id');
            console.log(document.querySelector(this.selectors.ulContainer));
            const alias = `section.${elementID}.body.items`;
            const content = JSON.stringify(this.functions.createObject.call(this));
                        
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
        
        
        delete(e) {
            e.preventDefault();
            
            if (confirm('Are you sure?')) e.target.closest('li').remove();
        },
        
        
        reindexItems() {
            const items = document.querySelectorAll(`${this.selectors.ulContainer} li`);
            items.forEach((item, index) => item.setAttribute('data-index', index) )
        },
        
        
        createObject() {
            const items = document.querySelectorAll(`${this.selectors.ulContainer} li`);
            const arr = [];
            items.forEach(item => {
                arr.push({
                    open: item.querySelector('input[name="open"]').checked ?? false,
                    title: item.querySelector('input[name="title"]').value.trim(),
                    answer: item.querySelector('textarea[name="answer"]').value.trim()
                });
            });
            
            return arr;
        },
        
        
        renderElement(callback)
        {
            fetch(`${this.variables.baseurl}/elements/accordion`, {
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