
const $ = require("jquery");
require("materialize-css");
require("./adventure");
require("./form");
require("./dropup.js");
require("../images/forest.jpg");
require('../images/potions-card.jpg');
require('../images/mystic_book_by_adalbertofsouza-d2xvmui.jpg');
require('../images/skull_and_shackles_set_by_mattiasfahlberg-d8l0u44.jpg');
require('../images/weapon_design_by_zoriy-d4q4aty.jpg');
require('../images/4e_dnd_orcs_by_ralphhorsley.jpg');
require('../images/open_book.jpg');
require('../images/excalibur-hd.jpg');
require('../images/book_of_monsters_by_gailee-d563tn5.jpg');
require('../images/tavern-hero.jpg');

$(document).ready(function (){
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown({
        coverTrigger: false,
    });
    $('.carousel').carousel({
        shift: 50,
        padding: 50
    });
    $('.tabs').tabs({
        swipeable: false

    });
    $('.collapsible').collapsible();
    $('.tooltipped').tooltip();

    const actionBtns = document.querySelectorAll(".fixed-action-btn");
    actionBtns.forEach((btn) => {
        const btnInstance = M.FloatingActionButton.init(btn, {
            direction: "bottom"
        });
    });

    $("a[href='#top']").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
});

