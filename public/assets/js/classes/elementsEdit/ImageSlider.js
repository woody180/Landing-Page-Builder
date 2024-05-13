import SketchEngine from '../../classes/SketchEngine.js';

export default class YourClassName extends SketchEngine {

    constructor(baseurl)
    {
        super(); // Use parent contructor comming from extended class "SketchEngine"
        this.variables.baseurl = baseurl;
    }


    variables = {};


    execute = [];


    selectors = {};


    html = {}


    catchDOM() {}


    bindEvents() {}


    functions = {}

}