import * as divTemplate from './services/divTemplate.js';
import * as searchTemplate from './services/searchTemplate.js';
import * as login from './services/login.js';

login.show();

let searchContainer = new divTemplate.divTemplate({ renderTo: '#main', title: 'Foglalások'}, false);
let search = new searchTemplate.searchTemplate({renderTo: searchContainer.element, inputPlaceholder: 'Fodrász neve vagy ID-ja', buttonText: 'Keresés'});