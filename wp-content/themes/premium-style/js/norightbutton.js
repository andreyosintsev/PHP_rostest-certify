document.addEventListener("DOMContentLoaded", () => {
    const imgLeft = document.querySelector('.left_img');
    const imgRight = document.querySelector('.right_img');
    const imgCenter = document.querySelector('.center_img');

    function contextmenuEvent (e) {
        e.preventDefault();

        document.querySelector(".download").scrollIntoView({
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