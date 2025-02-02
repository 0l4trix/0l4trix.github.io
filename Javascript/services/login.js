const container = document.getElementById('outer');
const contentId = 'inner';

const template = `
        <div id="${contentId}">
            Kérjük jelentkezzen be a folytatáshoz vagy lépjen vissza a főoldalra!
            <br><br>(Ez a funkció hozzákapcsolt adatbázis hiánya miatt nincs implementálva, csak szemléltetés, bármit elfogad)
            <input type="text" id="name" placeholder="Név">
            <input type="text" id="password" placeholder="Jelszó">
        </div>
    `;

const div = document.createElement('div');
div.innerHTML = template;
const boxElement = div.firstElementChild;

const hide = () => {
    container.classList.add('hidden');
}

function create() {
    const confirmBtn = document.createElement('button');
    const cancelBtn = confirmBtn.cloneNode(true);

    confirmBtn.addEventListener('click', function () {
        //if name and pass match with database's
        if (boxElement.querySelector('#name').value.trim() !== '' && boxElement.querySelector('#password').value.trim() !== '')
            hide();
    });

    cancelBtn.addEventListener('click', () => window.location.href = "index.html");

    confirmBtn.textContent = 'Mehet';
    cancelBtn.textContent = 'Vissza';

    container.appendChild(boxElement);
    container.appendChild(cancelBtn);
    container.appendChild(confirmBtn);
}

if (!document.getElementById(contentId))
    create();

const show = () => {
    if (window.getComputedStyle(container).display === 'none')
        container.classList.remove('hidden');
}

export { show, hide };