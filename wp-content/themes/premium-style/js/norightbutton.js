document.addEventListener("DOMContentLoaded", () => {
    const certificateElements = document.querySelectorAll('.certificate__image');

    function contextMenuEvent(e) {
        e.preventDefault();

        const $downloaButtonElement = document.querySelector(".download")


            .scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    if (imgLeft) { 
        imgLeft.addEventListener("click", (e) => {
            contextmenuEvent (e);
        });
        imgLeft.addEventListener("contextmenu", (e) => {
            contextmenuEvent (e);
        });
    }

    if (imgRight) {
        imgRight.addEventListener("click", (e) => {
            contextmenuEvent (e);
        });
        imgRight.addEventListener("contextmenu", (e) => {
            contextmenuEvent (e);
        });
    }

    if (imgCenter) {
        imgCenter.addEventListener("click", (e) => {
            contextmenuEvent (e);
        });
        imgCenter.addEventListener("contextmenu", (e) => {
            contextmenuEvent (e);
        });
    }    
});