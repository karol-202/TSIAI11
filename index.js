function updateVoivodeships() {
    const selectVoivodeship = document.getElementById('select-voivodeship');

    selectVoivodeship.innerHTML = '';
    appendOption(selectVoivodeship, '');

    Object.keys(voivodeshipTree).forEach(voivodeship => appendOption(selectVoivodeship, voivodeship));

    updateCounties();
}

function updateCounties() {
    const selectVoivodeship = document.getElementById('select-voivodeship');
    const selectCounty = document.getElementById('select-county');

    selectCounty.innerHTML = '';
    appendOption(selectCounty, '');

    const voivodeshipValue = selectVoivodeship.value;
    if(voivodeshipValue !== null && voivodeshipValue !== '')
        Object.keys(voivodeshipTree[voivodeshipValue]).forEach(county => appendOption(selectCounty, county));

    updateParishes();
}

function updateParishes() {
    const selectVoivodeship = document.getElementById('select-voivodeship');
    const selectCounty = document.getElementById('select-county');
    const selectParish = document.getElementById('select-parish');

    selectParish.innerHTML = '';
    appendOption(selectParish, '');

    const voivodeshipValue = selectVoivodeship.value;
    const countyValue = selectCounty.value;
    if(countyValue !== null && countyValue !== '')
        Object.keys(voivodeshipTree[voivodeshipValue][countyValue]).forEach(parish => appendOption(selectParish, parish));

    updateCities();
}

function updateCities() {
    const selectVoivodeship = document.getElementById('select-voivodeship');
    const selectCounty = document.getElementById('select-county');
    const selectParish = document.getElementById('select-parish');
    const selectCities = document.getElementById('select-city');

    selectCities.innerHTML = '';
    appendOption(selectCities, '');

    const voivodeshipValue = selectVoivodeship.value;
    const countyValue = selectCounty.value;
    const parishValue = selectParish.value;
    if(parishValue !== null && parishValue !== '')
        voivodeshipTree[voivodeshipValue][countyValue][parishValue].forEach(city => appendOption(selectCities, city));
}

function updateTypes() {
    const selectTypes = document.getElementById('select-type');
    selectTypes.innerHTML = '';
    appendOption(selectTypes, '');
    schoolTypes.forEach(type => appendOption(selectTypes, type));
}

function appendOption(selectElement, value) {
    const option = document.createElement('option');
    option.value = value;
    option.innerHTML = value;
    selectElement.appendChild(option);
}

function showDetails(id) {
    window.location.href = 'details.php?id=' + id;
}

function previousPage(currentPage) {
    const previousPage = currentPage - 1;
    window.location.search += '&page=' + previousPage;
}

function nextPage(currentPage) {
    const nextPage = currentPage + 1;
    window.location.search += '&page=' + nextPage;
}
