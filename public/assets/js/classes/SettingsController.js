import SketchEngine from './SketchEngine.js';

export default class SettingsController extends SketchEngine {


    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {};


    execute = [

    ];


    selectors = {
        addFaviconButton: '.ld-change-favicon-button',
        addLogoButton: '.ld-change-logo-button',
    };


    html = {
        
    }


    catchDOM() {
       
    }


    bindEvents() {
        this.lib(this.selectors.addLogoButton).on('click', this.functions.addLogo.bind(this));
        this.lib(this.selectors.addFaviconButton).on('click', this.functions.addFavicon.bind(this));
    }


    functions = {
        
        addLogo(e)
        {
            e.preventDefault();
            const button = e.target;
            const input = button.closest('div').querySelector('input[name="logo"]');
            const img = button.previousElementSibling;
            
            filemanager(files => {
                const logo = files[0].split('files/')[1];
                input.value = logo;
                img.src = `${this.variables.baseurl}/assets/tinyeditor/filemanager/files/${logo}`;
            });
        },
        
        
        addFavicon(e)
        {
            e.preventDefault();
            const button = e.target;
            const input = button.closest('div').querySelector('input[name="favicon"]');
            const img = button.previousElementSibling;
            
            filemanager(files => {
                const favicon = files[0].split('files/')[1];
                input.value = favicon;
                img.src = `${this.variables.baseurl}/assets/tinyeditor/filemanager/files/${favicon}`;
            });
        }
      
    }


}