import SketchEngine from '../../classes/SketchEngine.js';

export default class YourClassName extends SketchEngine {

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


    html = {}


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