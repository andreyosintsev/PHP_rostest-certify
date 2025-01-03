document.addEventListener("DOMContentLoaded", () => {
    const downloadButtonElement = document.querySelector(".specs__download");
    if (!downloadButtonElement) {
        return;
    }

    const certificateElements = document.querySelectorAll('.certificate__image');

    function contextMenuEvent(e) {
        e.preventDefault();
        downloadButtonElement.scrollIntoView({ behavior: 'smooth', block: 'center' });

        downloadButtonElement.classList.add('specs__download_emphasized');

        clearTimeout(downloadButtonElement.animationTimeout);
        downloadButtonElement.animationTimeout = setTimeout(() => {
            downloadButtonElement.classList.remove('specs__download_emphasized');
        }, 3000);
    }

    certificateElements.forEach(certificateElement => {
        certificateElement.addEventListener('click', contextMenuEvent);
        certificateElement.addEventListener('contextmenu', contextMenuEvent);
    });
});