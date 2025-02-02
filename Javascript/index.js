
import * as hairsalon from './models/hairsalon.js';
import * as apiHandler from './services/apiHandler.js';

let allHairdressers = await apiHandler.getHairdressers();

let newHairsalon = new hairsalon.Hairsalon(1);
newHairsalon.addEmployee(allHairdressers);

const container = document.querySelector('#main');
newHairsalon.createHTML(container);