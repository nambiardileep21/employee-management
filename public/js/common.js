document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('country_id');
    const stateSelect = document.getElementById('state_id');
    const citySelect = document.getElementById('city_id');

    if (!countrySelect || !stateSelect || !citySelect) return;

    // Read base API URL from a meta tag or fallback to relative path
    const metaAppUrl = document.querySelector('meta[name="app-url"]')?.getAttribute('content') || '';
    const baseUrl = metaAppUrl ? metaAppUrl.replace(/\/+$/, '') : '';

    countrySelect.addEventListener('change', function () {
        const countryId = this.value;
        stateSelect.innerHTML = '<option value="">Select State</option>';
        citySelect.innerHTML = '<option value="">Select City</option>';
        stateSelect.disabled = true;
        citySelect.disabled = true;

        if (!countryId) return;

        fetch(`${baseUrl}/api/countries/${countryId}/states`)
            .then(res => res.json())
            .then(data => {
                data.forEach(state => {
                    const opt = document.createElement('option');
                    opt.value = state.id;
                    opt.textContent = state.name;
                    stateSelect.appendChild(opt);
                });
                stateSelect.disabled = false;
            })
            .catch(err => console.error('Error fetching states:', err));
    });

    stateSelect.addEventListener('change', function () {
        const stateId = this.value;
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;

        if (!stateId) return;

        fetch(`${baseUrl}/api/states/${stateId}/cities`)
            .then(res => res.json())
            .then(data => {
                data.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city.id;
                    opt.textContent = city.name;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            })
            .catch(err => console.error('Error fetching cities:', err));
    });
});