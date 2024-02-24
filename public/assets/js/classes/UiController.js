import SketchEngine from './SketchEngine.js';

export default class UiController extends SketchEngine {


    constructor()
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
    }


    variables = {};


    execute = [
        'checkZIndex', 'addStyles'
    ];


    selectors = {
        layer: '.ld-color-layer'
    };


    html = {
       
    }


    catchDOM() {
        
    }


    bindEvents() {}


    functions = {
        
        checkZIndex()
        {
            this.lib(this.selectors.layer).getStyle('z-index').forEach(obj => {
                const zIndex = parseInt(obj['z-index']) + 1;
                
                if (obj.el.nextElementSibling) obj.el.nextElementSibling.style.zIndex = zIndex;
                if (obj.el.previousElementSibling) obj.el.previousElementSibling.style.zIndex = zIndex;
            });
        },
        
        
        addStyles()
        {
            document.querySelectorAll('[data-style]').forEach(el => {
                const style = el.getAttribute('data-style');
                if (!el.getAttribute('style')) {
                    el.setAttribute('style', style);
                } else {
                    let previousStyle = el.getAttribute('style');
                    previousStyle += style;
                    el.setAttribute('style', previousStyle);
                }
            });
        }

    }


}