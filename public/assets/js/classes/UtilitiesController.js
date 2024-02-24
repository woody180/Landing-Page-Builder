import SketchEngine from './SketchEngine.js';

export default class UtilitiesController extends SketchEngine {

    constructor() {
        super();
    }


    variables = {};


    execute = [
        'background', 'responsive', 'backgroundColor', 'textColor'
    ];


    selectors = {};


    html = {}


    catchDOM() {
        
    }


    bindEvents() {}


    functions = {

        responsive()
        {
            /*
            * add attributes like in the example
            * data-responsive="max-width[100]; style[color: ...; font-size: ...;]"
            */

            document.querySelectorAll('[data-responsive]').forEach(elem => {
                const str = elem.getAttribute('data-responsive');
                const match = str.match(/max-width[\s+]?\[(.*?)\]\;/g);
                const maxWidth = match[0].match(/\[(.*?)\]/)[1];
                const styles = str.match(/style[\s+]?\[(.*?)\]/)[1];

                let myFunction = x => {
                    if (x.matches) { // If media query matches
                        elem.setAttribute('style', styles);
                    } else {
                        elem.removeAttribute('style')
                    }
                }

                let x = window.matchMedia("(max-width: "+maxWidth+"px)");
                myFunction(x);
                x.addListener(myFunction);
            });
        },

        
        background()
        {
            document.querySelectorAll('[data-bg]').forEach(el => {
                const imageUrl = el.getAttribute('data-bg');
                if (imageUrl) {
                    el.style.backgroundImage = `url(${imageUrl})`;
                    el.style.backgroundSize = `cover`;
                    el.style.backgroundPosition = el.getAttribute('data-position') ? el.getAttribute('data-position') : 'center';
                    el.style.backgroundRepeat = el.getAttribute('data-repeat') ? el.getAttribute('data-repeat') : 'no-repeat';
                }
            });
        },
        
        
        backgroundColor()
        {
            document.querySelectorAll('[data-background-color]').forEach(section => {
                const attr = section.getAttribute('data-background-color');
                if (attr) section.style.backgroundColor = section.getAttribute('data-background-color');
            });
        },


        textColor()
        {
            document.querySelectorAll('[data-text-color]').forEach(section => {
                const attr = section.getAttribute('data-text-color');
                if (attr) section.style.color = section.getAttribute('data-text-color');
            });
        }

    }
}