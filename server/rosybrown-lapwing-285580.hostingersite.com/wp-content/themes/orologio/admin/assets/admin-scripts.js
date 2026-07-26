document.addEventListener('DOMContentLoaded', function () {
    const sourceSelect = document.getElementById('theme_source');
    const codeField = document.getElementById('purchase_code_field');

    sourceSelect.addEventListener('change', function () {
        if (this.value === 'elements') {
            codeField.style.display = 'none';
        } else {
            codeField.style.display = 'block';
        }
    });
});