import * as apiHandler from './apiHandler.js';
import * as smallFunctions from '../models/smallFunctions.js';

const container = document.getElementById('outer');
const contentId = 'inner';

const template = `
        <div id="${contentId}">
            <input type="text" id="name" placeholder="Név">
            <input type="tel" id="phone" placeholder="Telefonszám">
            <select></select>
            <input type="text" id="datepicker" placeholder="Dátum" readonly><div id="getDate">Időpontok</div>
            <div id="loader" class="hidden"></div>
            <div id="appointmentContainer" class="hidden"></div>
        </div>            
        <div id="${contentId}1"></div>
    `;

const div = document.createElement('div');
div.innerHTML = template;
const boxElement = div.firstElementChild;
const boxElement1 = div.lastElementChild;

const hide = () => {
    container.classList.add('hidden');
    boxElement.querySelector('#appointmentContainer').classList.add('hidden');
    let appointmentContainer = document.querySelector('#appointmentContainer');

    const end = appointmentContainer.getElementsByClassName('appointment').length;
    for (let i = 0; i < end; i++)
        appointmentContainer.removeChild(appointmentContainer.lastChild);

    let select = boxElement.querySelector('select');
    while (select.lastElementChild) {
        select.removeChild(select.lastElementChild);
    }
}

function create() {
    if (!document.getElementById(contentId)) {
        const confirmBtn = document.createElement('button');
        const cancelBtn = confirmBtn.cloneNode(true);
        const date = boxElement.querySelector('#datepicker');
        const appointmentContainer = boxElement.querySelector('#appointmentContainer');
        date.addEventListener('click', function () {
            appointmentContainer.classList.add('hidden');
        })

        boxElement.querySelector('#getDate').addEventListener('click', function () {
            if (date.value !== '') {
                appointmentContainer.classList.add('hidden');
                let appointmentBtns = document.getElementsByClassName('appointment');
                for (const btn of appointmentBtns) {
                    if (btn.classList.contains('taken'))
                        btn.classList.remove('taken');
                }
                (async function () {
                    loader.classList.remove('hidden');
                    let taken = await apiHandler.getAppointmentsByDate(boxElement.getAttribute("data-id"), smallFunctions.convertDateFormat(date.value));
                    for (const btn of appointmentBtns) {
                        if (Date.now() > smallFunctions.stringToTime(date.value) || taken.includes(btn.textContent))
                            btn.classList.add('taken');
                    }
                    boxElement.querySelector('#appointmentContainer').classList.remove('hidden');
                    loader.classList.add('hidden');
                })();
            }
        })

        confirmBtn.addEventListener('click', function () {
            let name = boxElement.querySelector('#name');
            let phone = boxElement.querySelector('#phone');
            const loader = boxElement.querySelector('#loader');
            if (name.value.trim() !== '' && phone.value.trim().match(/^[+0-9][-0-9]+/g) !== null && date.value !== '' && boxElement.querySelector('.chosenAppointment'))
                (async () => {
                    loader.classList.remove('hidden');
                    const fullDate = `${smallFunctions.convertDateFormat(date.value)} ${boxElement.querySelector('.chosenAppointment').innerText}:00`;
                    const res = await apiHandler.getAppointmentsByDate(boxElement.getAttribute('data-id'), fullDate);
                    if (res.length === 0)
                        var newAppointment = {
                            appointment_date: fullDate,
                            created_at: smallFunctions.formatDate(new Date()),
                            customer_name: name.value.trim(),
                            customer_phone: phone.value.trim(),
                            hairdresser_id: boxElement.getAttribute('data-id'),
                            id: await apiHandler.getLastAppointmentId() + 1,
                            service: boxElement.querySelector('select').value
                        }
                    console.log(newAppointment);
                    await apiHandler.sendAppointment(newAppointment, document.getElementById(`${contentId}1`));
                    document.getElementById(contentId).classList.add('hidden');
                    confirmBtn.classList.add('hidden');
                    date.value = '';
                    name.value = '';
                    phone.value = '';
                    loader.classList.add('hidden');
                })();
        });

        cancelBtn.addEventListener('click', function () {
            document.getElementById(contentId).classList.remove('hidden');
            document.getElementById(`${contentId}1`).innerText = null;
            hide();
            if (confirmBtn.classList.contains('hidden'))
                confirmBtn.classList.remove('hidden');
        })

        confirmBtn.textContent = 'Lefoglalom';
        cancelBtn.textContent = 'Vissza';

        container.appendChild(boxElement);
        container.appendChild(boxElement1);
        container.appendChild(confirmBtn);
        container.appendChild(cancelBtn);
    }
}

const show = (text, id, services) => {
    if (window.getComputedStyle(container).display === 'none') {
        boxElement.setAttribute("data-id", id);
        for (var i = 0; i < services.length; i++) {
            var option = document.createElement("option");
            option.value = services[i];
            option.text = services[i];
            boxElement.querySelector('select').appendChild(option);
        }

        for (const t of text) {
            const div = document.createElement('div');
            div.classList.add('appointment');
            div.innerText = t;
            div.addEventListener('click', function () {
                let prev = this.parentElement.querySelector('.chosenAppointment');
                if (!this.classList.contains('taken')) {
                    if (prev)
                        prev.classList.remove('chosenAppointment');
                    this.classList.add("chosenAppointment");
                }
            });
            document.querySelector('#appointmentContainer').appendChild(div);
        }

        container.classList.remove('hidden');
    }
}

export { create, show, hide };