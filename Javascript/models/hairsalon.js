import * as hairdresser from './hairdresser.js';
import * as divTemplate from '../services/divTemplate.js';

class Hairsalon {
    #id;
    #employees = [];

    constructor(id) {
        this.#id = id;
    }

    addEmployee(hairdressers) {
        for (const employee of hairdressers) {
            if (employee instanceof hairdresser.Hairdresser && employee.salon_id === this.#id && !this.#employees.some(n => n.id == employee.id))
                this.#employees.push(employee);
        }
    }

    get employees() {
        return this.#employees;
    }

    getEmployeeById(id) {
        return this.#employees[this.#employees.findIndex(n => n.id === id)];
    }

    createHTML(renderTo) {
        this.#employees.forEach(n => new divTemplate.divTemplate({ renderTo: renderTo, id: n.id, title: n.name, subtitle: n.profession, image: n.image, text: [`${n.description.match(/.{120}/g)[0]}...`], appointments: n.appointmentsTime(), services: this.getEmployeeById(n.id).services, buttonText: 'Foglalj időpontot!' } ))
    }

}

export { Hairsalon }