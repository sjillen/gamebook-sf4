
const pulser = {
    addPulse : function(elt) {
        if (!elt.classList.contains("pulse")) {
            elt.className += " pulse";
        }
    },

    addIconPulse : function(elt) {
        let icon = elt.parentNode.parentNode.querySelector('.skill-icon');
        icon.className += " pulse-purple";
    },

    removePulse : function(elt) {
        elt.classList.remove("pulse");
    },

    pulseTimer : function(elt) {
        this.addPulse(elt);
        setTimeout( () => this.removePulse(elt), 3000);
    },

    removeIconPulse : function(elt, color) {
        //remove specific pulse to the icon of the concerned skill
        let icon = elt.parentNode.parentNode.querySelector('.skill-icon');
        icon.classList.remove(`pulse-${color}`);
    }

};

export default pulser;
