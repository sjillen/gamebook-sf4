var $choicesHolder;
var $npcsHolder;
var $weaponsHolder;
var $specialItemsHolder;
var $consumableItemsHolder;

// setup an "add a choice" link
var $addChoiceLink = $('<a href="#" class="add_choice_link"><button class="btn btn-primary">Add a choice</button></a>');
var $newChoiceLinkLi = $('<li></li>').append($addChoiceLink);

var $addNpcLink = $('<a href="#" class="add_npc_link"><button class="btn waves-light waves-effect amber accent-4">Add a npc</button></a>');
var $newNpcLinkLi = $('<li></li>').append($addNpcLink);

var $addWeaponLink = $('<a href="#" class="add_weapon_link"><button class="btn waves-light waves-effect cyan accent-4">Add a weapon</button></a>');
var $newWeaponLinkLi = $("<li></li>").append($addWeaponLink);

var $addSpecialItemLink = $('<a href="#" class="add_specialItem_link"><button class="btn waves-effect waves-light purple accent-4">Add a special item</button></a>');
var $newSpecialItemLinkLi = $("<li></li>").append($addSpecialItemLink);

var $addConsumableItemLink = $('<a href="#" class="add_consumableItem_link"><button class="btn waves-effect waves-light green accent-4">Add a consumable</button></a>');
var $newConsumableItemLinkLi = $("<li></li>").append($addConsumableItemLink);

$(document).ready(function() {

    initializeLabelCheckbox();

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
    $choicesHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $npcsHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $weaponsHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $specialItemsHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $consumableItemsHolder.find('li').each(function() {
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
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#"><button class="btn-floating waves-effect waves-light red"><i class="material-icons">delete</i></button></a>');
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
