import * as apiHandler from './apiHandler.js';

const searchTemplate = (function () {

    const template = `
        <div class="search">
            <input type="text">
            <button></button>
            <div id="loader" class="hidden"></div>
            <div id="data"></div>
        </div>
    `;

    const div = document.createElement('div');
    div.innerHTML = template;
    const boxElement = div.firstElementChild;

    return class divTemplate {
        parentElement;
        element;
        inputElement;
        buttonElement;
        containerElement;
        #button;

        constructor(options) {
            if (typeof options.renderTo == "string")
                this.parentElement = document.querySelector(options.renderTo);
            else if (typeof options.renderTo == "object" && options.renderTo instanceof HTMLElement)
                this.parentElement = options.renderTo;

            this.element = boxElement.cloneNode(true);

            this.inputElement = this.element.querySelector('input');
            this.buttonElement = this.element.querySelector('button');
            this.containerElement = this.element.querySelector('div');

            this.input = options.inputPlaceholder;
            this.button = options.buttonText;

            this.buttonElement.addEventListener('click', function () {
                let value = this.parentElement.querySelector('input').value;
                let loader = document.querySelector('#loader');
                (async function () {
                    if (isNaN(Number(value)))
                        var id = await apiHandler.getHairdresserId(value);
                    else
                        var id = value;
                    loader.classList.remove('hidden');
                    let results = await apiHandler.getAppointmentsById(id);
                    let string = '<p>';
                    for (const result of results) {
                        string += `${Object.entries(result).map(([key, value]) => `${key}: ${value}`).join('<br>')}</p><p>`;
                    }
                    string += '</p>'
                    document.querySelector('#data').innerHTML = string;
                    loader.classList.add('hidden');
                })();
            })

            this.parentElement.appendChild(this.element);
        }

        set input(text) {
            if (text)
                this.inputElement.placeholder = text;
        }

        get input() {
            return this.inputElement.placeholder;
        }

        set button(text) {
            if (text)
                this.buttonElement.innerHTML = text;
        }

        get button() {
            return this.buttonElement.textContent;
        }

    }
})();

export { searchTemplate }