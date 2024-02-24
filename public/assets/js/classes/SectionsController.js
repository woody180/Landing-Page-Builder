import SketchEngine from './SketchEngine.js';

export default class SectionsController extends SketchEngine {


    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {
        sectionID: undefined,
        elementPath: undefined,
        section: undefined,
    };


    execute = [
        'insertButtonsToSections', 'coloris'
    ];


    selectors = {
        adminEdtiButton: '.ld-admin-editable-button',
        section: '.ld-set-editable-icon',
        sidebarBody: '.offcanvas-body .ld-offcanvas-wrapper > div',
        spinner: '.uk-spinner',
        save: '#ld-offcanvas-save-settings',
        removeBg: '#ld-remove-section-bg',
        bgFiled: '#ld-background-hidden',
        filemanagerButton: '#ld-open-filemanager',
        preview: '.preview',
        hideSectionCheckbox: '#ld-hide-section',
        sectionsListButton: '#sections-list',
        toggleVisibility: '.ld-toggle-section-visibility'
    };


    html = {
        editIcon: `<a 
            href="#" 
            uk-toggle="target: #offcanvas-reveal" 
            class="uk-icon-button ${this.selectors.adminEdtiButton.replace('.', '')}" 
            uk-icon="icon: pencil; ratio: 1" style="z-index: 999">
        </a>`,
            
        rederSidebar(data)
        {            
            return `
                <form>
                    <ul class="uk-list uk-list-divider">
                        <li>
                            <div class="uk-flex uk-flex-between uk-flex-middle">
                                <div uk-tooltip="title: Show / hide section;">Show section</div>
                                <div>
                                    <input id="ld-hide-section" name="show" class="uk-checkbox" type="checkbox" ${data.show ? 'checked' : ''}>
                                </div>
                            </div>
                        </li>


                        <li>
                            <div class="uk-flex uk-flex-between uk-flex-middle">
                                <div uk-tooltip="title: Choose background color;">Choose BG</div>
                                <div>
                                    <input id="color-field" name="color" type="text" class="uk-input uk-form-small uk-border-rounded" value="${(data.color && data.color.length) ? data.color : ''}" />
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="uk-flex uk-flex-between uk-flex-middle">
                                <div uk-tooltip="title: Choose text color;">Text color</div>
                                <div>
                                    <input id="color-field" name="text_color" type="text" class="uk-input uk-form-small uk-border-rounded" value="${(data.text_color && data.text_color.length) ? data.text_color : ''}" />
                                </div>
                            </div>
                        </li>


                        <li>
                            <div class="uk-flex uk-flex-center uk-margin-top">
                                <div class="uk-text-center">
                                    <input id="ld-background-hidden" name="background" type="hidden" value="${data.background ?? ''}" />
                                    <div class="js-upload" uk-form-custom>
                                        <button id="ld-open-filemanager" class="uk-button uk-button-default" type="button" tabindex="-1">Select background</button>
                                    </div>

                                    <div class="uk-position-relative">
                                        <a id="ld-remove-section-bg" style="background-color: #222222" href="#" class="uk-icon-button uk-position-top-right uk-position-z-index uk-margin-small-right uk-margin-small-top" uk-icon="icon: close;"></a>
                                        <img src="${this.variables.baseurl}/assets/tinyeditor/filemanager/files/${data.background ?? 'not-found.png'}" class="object-cover preview uk-height-small uk-display-block uk-width-1-1 uk-border-rounded uk-margin-top" />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>
            `;
        }
    }


    catchDOM() {}


    bindEvents() {
        this.lib('body').on('click', this.functions.sidebar.bind(this), this.selectors.adminEdtiButton);
        this.lib('body').on('click', this.functions.removeBg.bind(this), this.selectors.removeBg);
        this.lib('body').on('click', this.functions.setBackground.bind(this), this.selectors.filemanagerButton);
        this.lib('body').on('click', this.functions.save.bind(this), this.selectors.save);
        document.addEventListener('coloris:pick', this.functions.setColorToSection.bind(this) );
        this.lib('body').on('change', this.functions.hideSectionCheckbox.bind(this), this.selectors.hideSectionCheckbox);
        this.lib('body').on('click', this.functions.sectionsList.bind(this), this.selectors.sectionsListButton);
        this.lib('body').on('click', this.functions.toggleVisibility.bind(this), this.selectors.toggleVisibility);
    }


    functions = {
        
        toggleVisibility(e)
        {
            e.preventDefault();
            
            const btn = e.target.closest(this.selectors.toggleVisibility);
            const isTrue = btn.className.includes('show');
            const sectionID = btn.getAttribute('data-sec-id');
            const section = document.querySelector(`[data-section-id="${sectionID}"]`);
            const obj = {initial: true};
            
            if (isTrue) {
                btn.classList.remove('show');
                btn.classList.add('hide');
                btn.setAttribute('uk-icon', 'icon: eye-slash');
                if (section) section.setAttribute('hidden', true);
                obj.show = 0;
            } else {
                btn.classList.remove('hide');
                btn.classList.add('show');
                btn.setAttribute('uk-icon', 'icon: eye');
                if (section) section.removeAttribute('hidden');
                obj.show = 1;
            }
            
            
            fetch(`${this.variables.baseurl}/section/${sectionID}`, {
                method: 'PUT',
                header: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(obj)
            })
            .then(res => res.json())
            .then(res => {
                UIkit.notification(`<p class="uk-text-small uk-margin-remove">${res.success}</p>`, {status:'primary'})
            })
            .catch(err => {
                UIkit.notification(`<p class="uk-text-small uk-margin-remove">${res.err}</p>`, {status:'danger'})        
            });
            
        },
        
        
        
        sectionsList(e)
        {
            e.preventDefault();            
            
            fetch(`${this.variables.baseurl}/section`, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.text())
            .then(res => {
                
                const modal = UIkit.modal.dialog(res);

                // Add classes
                modal.$el.classList.add('uk-modal-container');
                modal.$el.firstElementChild.classList.add('uk-modal-body');
                modal.$el.firstElementChild.classList.add('uk-border-rounded');
                
                
                UIkit.util.on('.uk-modal', 'hidden', () => location.reload());
                
            })
            .catch(err => console.error(err));
                
        },
        
        
        hideSectionCheckbox(e)
        {
            const checked = e.target.checked;
            if (!checked) 
                this.variables.section.setAttribute('hidden', true);
            else
                this.variables.section.removeAttribute('hidden');

        },
        
        
        setColorToSection(e)
        {
            if (e.detail.currentEl.getAttribute('name') === 'text_color') {
                this.variables.section.style.color = e.detail.color;
            } else {
                this.variables.section.style.backgroundColor = e.detail.color;
            }
        },
        
        
        setBackground(e)
        {
            filemanager(files => {
                const imgUrl = files[0].split('files/')[1];
                const fullurl = `${this.variables.baseurl}/assets/tinyeditor/filemanager/files/${imgUrl}`;
                document.querySelector(this.selectors.preview).src = fullurl
                document.querySelector(this.selectors.bgFiled).value = imgUrl;
                
                this.variables.section.style.background = `url(${fullurl}) no-repeat center / cover`;
            });
        },
        
        
        removeBg(e)
        {
            e.preventDefault();
            
            const img = e.target.closest(this.selectors.removeBg).nextElementSibling;
            img.src = `${this.variables.baseurl}/assets/tinyeditor/filemanager/files/not-found.png`;
            document.querySelector(this.selectors.bgFiled).removeAttribute('value');
            
            this.variables.section.style.background = '';
        },
        
        
        sidebar(e)
        {
            this.lib(this.selectors.spinner).removeAttr('hidden');
            const button = e.target.closest(this.selectors.adminEdtiButton);
            const section = button.closest(this.selectors.section);
            const sectionID = section.getAttribute('data-section-id');

            this.variables.section = section;
            this.variables.sectionID = sectionID;
            this.variables.elementPath = section.getAttribute('data-element-path') ?? undefined;
            
            if (!this.variables.elementPath) {
                fetch(`${this.variables.baseurl}/section/${sectionID}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    this.lib(this.selectors.spinner).attr('hidden', true);
                    document.querySelector(this.selectors.sidebarBody).innerHTML = this.html.rederSidebar.call(this, res);
                });
            }

            if (!!this.variables.elementPath) {

                fetch(`${this.variables.baseurl}/section/${sectionID}?elpath=${this.variables.elementPath}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    this.lib(this.selectors.spinner).attr('hidden', true);
                    document.querySelector(this.selectors.sidebarBody).innerHTML = this.html.rederSidebar.call(this, res);
                });
            }
        },
        

        insertButtonsToSections()
        {
            document.querySelectorAll('.ld-set-editable-icon').forEach(section => {
                section.insertAdjacentHTML('afterbegin', this.html.editIcon)
            });
        },
        
        
        save(e)
        {
            e.preventDefault();
            
            const form = document.querySelector(`${this.selectors.sidebarBody} form`);
            const formData = this.lib().formData(form);
            formData.show = formData.show === 'on' ? 1 : 0;

            // If is true send data to save route instead of sections controller
            // Route to save data - directsave
            // Method - PATCH
            if (!!this.variables.elementPath) {
                const data = {};
                data.alias = this.variables.elementPath;
                data.content = formData;
                if (!data.content.background) data.content.background = null;
                if (!data.content.color) data.content.color = null;
                if (!data.content.url) data.content.url = null;
                if (!data.content.show) data.content.show = 0;
                if (!data.content.text_color) data.content.text_color = null;

                fetch(`${this.variables.baseurl}/directsave`, {
                    method: 'PATCH',
                    header: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    UIkit.notification(`<p class="uk-text-small uk-margin-remove">${res.success}</p>`, {status:'primary'})
                })
                .catch(err => {
                    UIkit.notification(`<p class="uk-text-small uk-margin-remove">${res.err}</p>`, {status:'danger'})        
                });
            }


            if (!this.variables.elementPath) {
                fetch(`${this.variables.baseurl}/section/${this.variables.sectionID}`, {
                    method: 'PUT',
                    header: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(res => {
                    UIkit.notification(`<p class="uk-text-small uk-margin-remove">${res.success}</p>`, {status:'primary'})
                })
                .catch(err => {
                    UIkit.notification(`<p class="uk-text-small uk-margin-remove">${res.err}</p>`, {status:'danger'})        
                });
            }
           
        },
        
        
        
        coloris()
        {
            Coloris({
                el: '#color-field',
                swatches: [
                    '#264653',
                    '#2a9d8f',
                    '#e9c46a',
                    '#f4a261',
                    '#e76f51',
                    '#d62828',
                    '#023e8a',
                    '#0077b6',
                    '#0096c7',
                    '#00b4d8',
                    '#48cae4'
                ]
            });
            
            Coloris.setInstance('#color-field', {
                theme: 'pill',
                themeMode: 'dark',
                formatToggle: true,
                closeButton: true,
                clearButton: true
            });
        }

    }


}