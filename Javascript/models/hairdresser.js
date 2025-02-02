class Hairdresser {
    #id;
    name;
    #salon_id;
    phone_number;
    email;
    work_start_time;
    work_end_time;
    services;
    image;
    profession;
    description;
    #allServices = ['hajvágás', 'hajfestés', 'borotválás', 'hajformázás', 'szakáll igazítás', 'hajmosás', 'dauerolás'];

    constructor(options, img, prof, desc) {
        this.#id = options.id;
        this.name = options.name;
        this.#salon_id = options.salon_id;
        this.phone_number = options.phone_number;
        this.email = options.email;
        this.work_start_time = options.work_start_time.match(/^[0-9]{2}[:][0-9]{2}/g)[0];
        this.work_end_time = options.work_end_time.match(/^[0-9]{2}[:][0-9]{2}/g)[0];
        this.services = options.services;
        this.image = img;
        this.profession = prof;
        this.description = desc;
        this.appointmentsTime();
    }

    get id() {
        return this.#id;
    }

    changeSalon(id) {
        if (typeof id === 'number')
            this.#salon_id == id;
        //remove from salon
    }

    get salon_id() {
        return this.#salon_id;
    }

    addService(service) {
        if (this.#allServices.includes(service))
            this.services.push(service);
    }

    appointmentsTime() {
        const appointments = [];
        function timeToMinute(time) {
            const [hours, minutes] = time.split(':').map(Number);
            return hours * 60 + minutes;
        }
        for (let i = timeToMinute(this.work_start_time); i < timeToMinute(this.work_end_time); i += 30) {
            let time = `${parseInt(i / 60)}:${i % 60 === 0 ? '00' : i % 60}`;
            appointments.push(time);
        }
        return appointments;
    }

}

export { Hairdresser }