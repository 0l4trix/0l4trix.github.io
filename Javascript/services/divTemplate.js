import * as popup from './popup.js';

const divTemplate = (function () {

    const template = `
        <div class="box">
            <div class="img"></div>
            <h2></h2>
            <h4></h4>
            <p></p>
            <h5></h5>
        </div>
    `;

    const div = document.createElement('div');
    div.innerHTML = template;
    const boxElement = div.firstElementChild;

    return class divTemplate {
        parentElement;
        element;
        titleElement;
        subtitleElement;
        imageElement;
        textElement;
        buttonElement;
        #title;
        #subtitle;
        #image;
        #text;
        #button;

        constructor(options, needPopup = true) {
            if (typeof options.renderTo == "string")
                this.parentElement = document.querySelector(options.renderTo);
            else if (typeof options.renderTo == "object" && options.renderTo instanceof HTMLElement)
                this.parentElement = options.renderTo;

            this.element = boxElement.cloneNode(true);

            this.titleElement = this.element.querySelector('h2');
            this.subtitleElement = this.element.querySelector('h4');
            this.imageElement = this.element.querySelector('.img');
            this.textElement = this.element.querySelector('p');
            this.buttonElement = this.element.querySelector('h5');

            if(needPopup){
                popup.create();
                this.element.addEventListener('click', () => popup.show(options.appointments, options.id, options.services));
            }

            this.title = options.title;
            this.subtitle = options.subtitle;
            this.image = options.image;
            this.text = options.text;
            this.button = options.buttonText;

            this.parentElement.appendChild(this.element);
        }

        set title(text) {
            this.titleElement.textContent = text;
        }

        get title() {
            return this.titleElement.textContent;
        }

        set subtitle(text) {
            this.subtitleElement.textContent = text;
        }

        get subtitle() {
            return this.subtitleElement.textContent;
        }

        set image(img) {
            this.imageElement.style.cssText += `background-image: url('../img/${img}'); background-size: 100%`;
        }

        get image() {
            return window.getComputedStyle(this.imageElement).backgroundImage.slice(5, -2);
        }

        set text(text) {
            if (text)
                this.textElement.innerHTML = text;
        }

        get text() {
            return this.textElement.textContent;
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

export { divTemplate }