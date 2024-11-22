document.addEventListener('DOMContentLoaded', () => {
    const body = document.querySelector('body');
    const viewGostButton = document.querySelector('#button-view-gost');
    const modal = document.querySelector('.modal');
    const overlay = document.querySelector('.modal-overlay');

    viewGostButton.addEventListener('click', () => {
        if (modal) modal.classList.add('modal_visible');
        if (overlay) overlay.classList.add('modal-overlay_visible');
        if (body) body.classList.add('no-scroll');
    });
})
