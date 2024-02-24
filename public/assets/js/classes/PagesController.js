import SketchEngine from './SketchEngine.js';

export default class PagesController extends SketchEngine {


    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {};


    execute = [];


    selectors = {
        copyButton: '.copy-url-button'
    };


    html = {}


    catchDOM() {}


    bindEvents() {
        this.lib(this.selectors.copyButton).on('click', this.functions.copyUrl.bind(this));
    }


    functions = {

        copyUrl(e)
        {
            e.preventDefault();
            
            const btn = e.target.closest(this.selectors.copyButton);
            const url = btn.getAttribute('href');
            navigator.clipboard.writeText(url)
                .then(() => alert('Page url copied.'))
                .catch((err) => alert('Could not copy text: ', err));
        }

    }


}