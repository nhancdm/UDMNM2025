document.addEventListener('DOMContentLoaded', function() {
    const swatches = document.querySelectorAll('.thwvsf-wrapper-ul li');

    swatches.forEach((swatch) => {
        swatch.addEventListener('click', () => {
            const parent = swatch.parentElement;
            parent.querySelectorAll('li').forEach((el) => el.classList.remove('selected'));
            swatch.classList.add('selected');
        });
    });
});