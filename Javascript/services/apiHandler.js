import * as hairdresser from '../models/hairdresser.js';

const apiHairdressers = 'https://salonsapi.prooktatas.hu/api/hairdressers';
const apiAppointments = 'https://salonsapi.prooktatas.hu/api/appointments';

async function getHairdressers() {
    const response = await fetch(apiHairdressers)
        .then((res) => {
            if (res.status >= 400 && response.status < 600)
                throw new Error("Bad response from server");
            else
                return res;
        })
    const data = await response.json();

    let allHairdressers = [];
    for (const element of data) {
        let img = `${element.id}.jpg`;
        await fetch('../data/intros.json')
            .then(res => res.json())
            .then(data => {
                allHairdressers.push(new hairdresser.Hairdresser(element, img, data.find(n => n.id == element.id).profession, data.find(n => n.id == element.id).text));
            })
            .catch(err => console.log(err));
    }
    return allHairdressers;
}

async function getAllAppointments() {
    const response = await fetch(apiAppointments)
        .then((res) => {
            if (res.status >= 400 && response.status < 600)
                throw new Error("Bad response from server");
            else
                return res;
        })
    const data = await response.json();
    return data;
}

async function getAppointmentsById(hairdresserId) {
    let data = await getAllAppointments();
    let search = [];
    for (const element of data) {
        if (element.hairdresser_id == hairdresserId && Date.now() < new Date(element.appointment_date).getTime())
            search.push(element);
    }
    return search;
};

async function getAppointmentsByDate(hairdresserId, date) {
    let data = await getAllAppointments();
    let search = [];
    for (const element of data) {
        if (element.hairdresser_id == hairdresserId && element.appointment_date.match(date))
            search.push(element.appointment_date.match(/(?<=\s)[0-9]{2}[:][0-9]{2}/g)[0]);
    }
    return search;
};

async function getLastAppointmentId() {
    let data = await getAllAppointments();
    return Number(data[data.length - 1].id);
}

async function sendAppointment(data, logInto) {
    await fetch(apiAppointments, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(res => {
            if (res.status === 400) {
                logInto.innerText = `Error 400 - Helytelenül kitöltött adatmezők!`;
                throw new Error('Error 400 - Helytelenül kitöltött adatmezők!');
            }else if (res.status === 404) {
                logInto.innerText = `Error 404 - Az adott link nem létezik!`;
                throw new Error('Error 404 - Az adott link nem létezik!');
            }else if (res.status === 500) {
                logInto.innerText = `Error 500 - Gond van az elérni kívánt szerverrel! Kérjük próbálja újra később!`;
                throw new Error('Error 500 - Gond van az elérni kívánt szerverrel! Kérjük próbálja újra később!');
            }else if (!res.ok) {
                logInto.innerText = `Sikertelen foglalás!`;
                throw new Error(res.statusText);
            }
            return res.json();
        })
        .then(data => {
            logInto.innerText = `Sikeres foglalás`;
        });
}

export { getHairdressers, getAppointmentsById, getAppointmentsByDate, getLastAppointmentId, sendAppointment }