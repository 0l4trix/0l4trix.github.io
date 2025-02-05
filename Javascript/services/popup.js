import * as apiHandler from './apiHandler.js';
import * as smallFunctions from '../models/smallFunctions.js';

const container = document.getElementById('outer');
const contentId = 'inner';

const template = `
        <div id="${contentId}">
            <input type="text" id="name">
            <input type="tel" id="phone">
            <select></select>
            <input type="text" id="datepicker" readonly>
            <div id="getDate">Időpontok</div>
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
        const name = boxElement.querySelector('#name');
        const phone = boxElement.querySelector('#phone');
        const date = boxElement.querySelector('#datepicker');
        const loader = boxElement.querySelector('#loader');
        function inputReset(){
            name.placeholder = "Név";
            name.value = '';
            if(name.classList.contains('alert'))
                name.classList.remove('alert');
            phone.placeholder = "Telefonszám";
            phone.value = '';
            if(phone.classList.contains('alert'))
                phone.classList.remove('alert');
            date.placeholder = "Dátum";
            date.value = '';
            if(date.classList.contains('alert'))
                date.classList.remove('alert');
        }
        inputReset();
        $( function() {
            $( "#datepicker" ).datepicker();
          } );
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
            if (name.value.trim() !== '' && phone.value.trim().match(/^[+0-9][-0-9]+/g) !== null && boxElement.querySelector('.chosenAppointment'))
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
                    await apiHandler.sendAppointment(newAppointment, document.getElementById(`${contentId}1`));
                    document.getElementById(contentId).classList.add('hidden');
                    confirmBtn.classList.add('hidden');
                    loader.classList.add('hidden');
                    inputReset();
                })();
            else {
                if(name.value.trim() == ''){
                    name.classList.add('alert');
                    name.placeholder = 'Kérem adja meg a nevét!';
                }
                if(phone.value.trim() == ""){
                    phone.classList.add('alert');
                    phone.placeholder = 'Kérem adja meg telefonszámát!';

                }else if(phone.value.trim().match(/^[+0-9][-0-9]+/g) == null){
                    phone.value = '';
                    phone.classList.add('alert');
                    phone.placeholder = 'Nem megfelelő telefonszám!';
                }
                if(date.value == "" || !boxElement.querySelector('.chosenAppointment')){
                    date.value = "";
                    date.classList.add('alert');
                    date.placeholder = 'Kérem válasszon egy időpontot!';
                }
            }
        });

        cancelBtn.addEventListener('click', function () {
            document.getElementById(contentId).classList.remove('hidden');
            document.getElementById(`${contentId}1`).innerText = null;
            hide();
            inputReset();
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