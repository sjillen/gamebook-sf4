let $choicesHolder;
let $npcsHolder;
let $weaponsHolder;
let $specialItemsHolder;
let $consumableItemsHolder;

// setup an "add a choice" link
const $addChoiceLink = $('<a role="button" href="#" class="add_choice_link btn waves-light waves-effect center-align">Add a choice</a>');
let $newChoiceLinkLi = $('<li></li>').append($addChoiceLink);

const $addNpcLink = $('<a href="#" role="button" class="add_npc_link btn waves-light waves-effect amber accent-4"><i class="material-icons">add</i></a>');
let $newNpcLinkLi = $('<li></li>').append($addNpcLink);

const $addWeaponLink = $('<a href="#" role="button" class="add_weapon_link btn waves-light waves-effect cyan accent-4"><i class="material-icons">add</i></a>');
let $newWeaponLinkLi = $("<li></li>").append($addWeaponLink);

const $addSpecialItemLink = $('<a href="#" role="button" class="add_specialItem_link btn waves-effect waves-light purple accent-4"><i class="material-icons">add</i></a>');
let $newSpecialItemLinkLi = $("<li></li>").append($addSpecialItemLink);

const $addConsumableItemLink = $('<a href="#" role="button" class="add_consumableItem_link btn waves-effect waves-light green accent-4"><i class="material-icons">add</i></a>');
let $newConsumableItemLinkLi = $("<li></li>").append($addConsumableItemLink);

$(document).ready(function() {

    initializeLabelCheckbox();
    displayRequirements();

    $('textarea').characterCounter();

    //initialize select elements for Materialize
    const baseSelects = document.querySelectorAll("select");
    baseSelects.forEach( (select) => {
        let instanceBaseSelect = M.Select.init(select);
    });
    // Get the ul that holds the collection of choices
    $choicesHolder = $('ul.choices');
    $npcsHolder = $('ul.npcs');
    $weaponsHolder = $('ul.weapons');
    $specialItemsHolder = $('ul.specialItems');
    $consumableItemsHolder = $('ul.consumableItems');

    // add a delete link to all of the existing tag form li elements
    $choicesHolder.find('.list-choice-item').each(function() {
        addTagFormDeleteLink($(this));
    });
    $npcsHolder.find('.list-npc-item').each(function() {
        addTagFormDeleteLink($(this));
    });
    $weaponsHolder.find('.list-weapon-item').each(function() {
        addTagFormDeleteLink($(this));
    });
    $specialItemsHolder.find('.list-special-item').each(function() {
        addTagFormDeleteLink($(this));
    });
    $consumableItemsHolder.find('.list-consumable-item').each(function() {
        addTagFormDeleteLink($(this));
    });

    // add the "add a choice" anchor and li to the choices ul
    $choicesHolder.append($newChoiceLinkLi);
    $npcsHolder.append($newNpcLinkLi);
    $weaponsHolder.append($newWeaponLinkLi);
    $specialItemsHolder.append($newSpecialItemLinkLi);
    $consumableItemsHolder.append($newConsumableItemLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $choicesHolder.data('index', $choicesHolder.find(':input').length);
    $npcsHolder.data('index', $npcsHolder.find(':input').length);
    $weaponsHolder.data('index', $weaponsHolder.find(':input').length);
    $specialItemsHolder.data('index', $specialItemsHolder.find(':input').length);
    $consumableItemsHolder.data('index', $consumableItemsHolder.find(':input').length);

    $addChoiceLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addChildForm($choicesHolder, $newChoiceLinkLi);
    });

    $addNpcLink.on('click', function(e) {
        e.preventDefault();
        addChildForm($npcsHolder, $newNpcLinkLi);
    });

    $addWeaponLink.on('click', function(e) {
        e.preventDefault();
        addChildForm($weaponsHolder, $newWeaponLinkLi);
    });

    $addSpecialItemLink.on('click', function(e) {
        e.preventDefault();
        addChildForm($specialItemsHolder, $newSpecialItemLinkLi);
    });

    $addConsumableItemLink.on('click', function(e) {
        e.preventDefault();
        addChildForm($consumableItemsHolder, $newConsumableItemLinkLi);
    });
});

function addChildForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    //newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addTagFormDeleteLink($newFormLi);
    const selectElts = document.querySelectorAll("select");
    selectElts.forEach((select) => {
        const selectElt = M.Select.init(select);
    });

    initializeLabelCheckbox();
    displayRequirements();
}

function addTagFormDeleteLink($tagFormLi) {
    const $removeFormA = $('<a class="collection-remove" href="#"><button class="btn waves-effect waves-light red"><i class="material-icons">delete</i></button></a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

function initializeLabelCheckbox() {
    const checkboxes = document.querySelectorAll("[type=checkbox]");
    checkboxes.forEach ( (checkbox)=> {
        $(checkbox).parent().attr('for', $(checkbox).attr('id'));
    });
}

function displayRequirements() {
    const checkboxes = document.querySelectorAll(".checkbox_require");
    checkboxes.forEach((checkbox) => {
        let requirement = checkbox.parentNode.parentNode.parentNode.nextSibling.nextSibling;
        if ($(requirement).hasClass("requirements")) {
            if ($(checkbox).is(':checked')) {
                $(requirement).fadeIn("slow");
            } else {
                $(requirement).fadeOut();
            }
            $(checkbox).on('change', () => {

                if ($(checkbox).is(':checked')) {
                    $(requirement).fadeIn("slow");
                } else {
                    $(requirement).fadeOut("slow");
                }
            });
        }

    });
}

