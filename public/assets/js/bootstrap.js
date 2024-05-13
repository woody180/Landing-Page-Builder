const baseurl = document.querySelector('meta[name="baseurl"]').getAttribute('content');
const checkuser = document.querySelector('meta[name="checkauth"]').getAttribute('content');
const loggedin = (checkuser === 'true') ? true : false;


import PagesController from '../js/classes/PagesController.js';
import SettingsController from '../js/classes/SettingsController.js';
import UtilitiesController from '../js/classes/UtilitiesController.js';
import UiController from '../js/classes/UiController.js';
import SectionsController from '../js/classes/SectionsController.js';


// Import editable element here
import Accordion from '../js/classes/elementsEdit/Accordion.js';
import Slider from '../js/classes/elementsEdit/Slider.js';
import ImageSlider from '../js/classes/elementsEdit/ImageSlider.js';


new PagesController(baseurl);
new UtilitiesController(baseurl);
new UiController(baseurl);
if (loggedin) new SettingsController(baseurl);
if (loggedin) new SectionsController(baseurl);



// Initialization of editable elements from here
if (loggedin) {
    
    new Accordion(baseurl);
    new Slider(baseurl);
    new ImageSlider(baseurl);
    

    new FgTinyEditor({
        selector: '.ld-editable',
        rootPath: `${baseurl}/assets/tinyeditor`,
        saveUrl: `${baseurl}/save`,
        loadjQuery: false
    });

}