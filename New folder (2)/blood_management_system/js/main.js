document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('roleSelect');
    const doctorFields = document.getElementById('doctorFields');

    if (roleSelect && doctorFields) {
        roleSelect.addEventListener('change', function() {
            doctorFields.style.display = (this.value === 'doctor') ? 'block' : 'none';
        });
    }

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const ageInput = form.querySelector('input[name="age"]');
            if (ageInput) {
                const age = parseInt(ageInput.value, 10);
                if (age < 18) {
                    alert('Donors must be at least 18 years of age.');
                    event.preventDefault();
                }
            }
        });
    });
});