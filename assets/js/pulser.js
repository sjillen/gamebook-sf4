//add pulse to an elt
const addPulse = function(elt) {
    if (!elt.classList.contains("pulse")) {
        elt.className += " pulse";
    }
};
const addIconPulse = function(elt) {
    //add specific pulse to the icon of the concerned skill
    let icon = elt.parentNode.parentNode.querySelector('.skill-icon');

    icon.className += " pulse-purple";
};

//remove pulse from elt
const removePulse = function (elt) {
    elt.classList.remove('pulse');
};

const pulseTimer = function(elt) {
    addPulse(elt);
    setTimeout(() => removePulse(elt), 3000);
};