'use strict';

document.addEventListener("DOMContentLoaded", () => {
    const expandinfos = document.querySelectorAll('.table_man.expandinfo');
    const infos = document.querySelectorAll('.td_agencies_info');

    expandinfos.forEach(i => {
        i.addEventListener('click', clickHandler);
    });
    
    function clickHandler(e) {
        infos.forEach(i => {
            if (i.dataset.reg == e.currentTarget.dataset.reg) {
                i.style.display="block";
            } else {
                i.style.display="none";              
            }
        });
    }
});