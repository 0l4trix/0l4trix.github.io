function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function convertDateFormat(date) {
    const parts = date.split('/');
    return `${parts[2]}-${parts[0]}-${parts[1]}`;
}

function stringToTime(dateString) {
    const dateParts = dateString.split('/');
    return new Date(dateParts[2], dateParts[0] - 1, dateParts[1]).getTime();
}

export { formatDate, convertDateFormat, stringToTime }