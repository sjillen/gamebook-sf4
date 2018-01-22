
function encounter () {
    let fightContainer = document.querySelector(".monsters-chapter-container");
    if (typeof hasFight !== "undefined") {
        const diceType = $('.monsters-chapter-container').data("dice-type");
        let lifeElts = document.querySelectorAll(".npc-life");
        const attackElts = document.querySelectorAll(".attack");
        const heroLifeElt = document.querySelector(".hero-life");
        const skillListBtn = document.querySelector('.skills-dropup-trigger');
        const victoryContent = document.querySelector(".chapter-second-part");
        let skillEventCounter = 0;
        let url = urlEncounter;

        const winBtn = `
            <div class="col s12 center-align">
                <h4 class="red-text text-lighten-1 winBtn">YOU WIN !</h4>    
            </div>`;
        
        const loseBtn = `
            <div class="col s12 center-align">
                <a href=${urlDeath} role="button" class="btn btn-large red waves-light waves-effect">You have been killed !</a>
            </div>
        `;

        const isVictory = function () {
            let monsters = document.querySelectorAll(".npc-card");
            let monstersLeft = monsters.length;
            if (monstersLeft > 0) {
                $(victoryContent).hide();
            } else {
                let monstersContainer = document.querySelector(".monsters-chapter-container");
                console.log(monstersContainer);
                $(monstersContainer).animateCss("bounceOutUp", () => {
                    monstersContainer.innerHTML = winBtn;
                    let win = monstersContainer.querySelector(".winBtn");
                    $(monstersContainer).animateCss("jackInTheBox", () => {
                        win.addEventListener("click", () => {
                            $(win).animateCss("jello");
                        }, false);

                    });
                    $(victoryContent).fadeIn("slow");
                });
                let dataElt = document.querySelector(".hero-life");
                let data = {"heroLife" : dataElt.textContent};
                $.ajax({
                    url: url,
                    data: data,
                    type: "get",
                    dataType: "json",
                    success: function(status) {
                        console.log(status);
                        M.toast({html: "You won the fight!"}, 5000);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
                choicesDisplayer();
            }
        };

        //weakness event:
        //get skills from hero
        const heroSkillsElts = document.querySelectorAll(".use-skill");
        let skills = [];
        heroSkillsElts.forEach((skill) => {
            skills.push(skill.innerText);
        });

        //list all the weaknesses from monster
        const listWeaknesses =  function() {
            const weaknessElts = document.querySelectorAll('.weakness');
            let array = [];
            weaknessElts.forEach((el) => {
                array.push(el.innerText);
            });
            return array;
        };



        // func event : increase ability of hero
        const abilityUp = function () {
            //get ability amount and increment by 2
            let heroAbilityElt = document.querySelector(".hero-ability");
            let abilityPulse = document.querySelector('.ability-dropup-trigger');
            let ability = parseInt(heroAbilityElt.textContent, 10);
            ability += 2;
            heroAbilityElt.innerHTML = ability;
            //add pulse to ability button
            pulseTimer(abilityPulse);
            //send toast message for skill bonus ability
            M.toast({html:`${this.innerText}: Ability +2!`, classes: "purple-text text-lighten-1" }, 5000);
            //remove the pulse from skill icon
            this.parentNode.parentNode.querySelector('.skill-icon').classList.remove('pulse-purple');
            //remove add listener
            this.removeEventListener("click", abilityUp);
            //decrement the counter
            -- skillEventCounter;
            //if no skill has pulse, remove the pulse from skill bar button
            if (skillEventCounter === 0) {
                removePulse(skillListBtn);
            }
        };

        //check if skills hero matches weaknesses of npcs
        heroSkillsElts.forEach( (elt) => {
            let weaknesses = listWeaknesses();
            if (weaknesses.includes(elt.innerText)) {
                //add pulse to the bar skill button if it doesn't already contains it
                addPulse(skillListBtn);
                addIconPulse(elt);
                //bind event for each active skill :  increment ability
                elt.addEventListener("click", abilityUp, false);
                //increment counter for each active skill
                ++ skillEventCounter;
            }
            return weaknesses;
        });

        const updateHeroDamages = function(dmg) {
            let life = document.querySelector(".hero-life");
            let targetValue = parseInt(life.textContent, 10);
            if (dmg < 0) {
                dmg = 0;
            }
            let newValue = targetValue - dmg;
            let finalValue;
            if(newValue > 0){
                finalValue = newValue;
                M.toast({html: `You took ${dmg} damages !`, classes: "orange-text text-lighten-1"}, 5000)
            }else{
                finalValue = 0;
                M.toast({html:`You die !`, classes: "red-text text-lighten-1" }, 5000);
                let monstersContainer = document.querySelector(".monsters-chapter-container");
                $(monstersContainer).animateCss("flipOutY", () => {
                    monstersContainer.innerHTML = loseBtn;
                    $(monstersContainer).animateCss("flipInY");
                });
            }
            life.innerHTML = finalValue;
        };


        const calculateLvlRatio = function(npcAbilityElts, heroAbilityElt) {

            const heroAbility = parseInt(heroAbilityElt.textContent, 10);
            let npcAbility = 0;
            npcAbilityElts.forEach((elt) => {
                npcAbility += parseInt(elt.textContent, 10);
            });
            const ratio = heroAbility - npcAbility;
            return ratio;
        };

        isVictory();

        //Func event
        const attackFunc = function() {
            const npcAbilityElts = document.querySelectorAll(".npc-ability");
            let monsterCard = this.parentNode.querySelector(".npc-card");
            const heroAbilityElt = document.querySelector(".hero-ability");
            let ratio = calculateLvlRatio(npcAbilityElts, heroAbilityElt);
            console.log("ratio: " + ratio);
            const nbNpcs = npcAbilityElts.length;
            let diceResult = Math.floor(Math.random() * diceType) + 1;
            console.log("dice: " + diceResult);
            let target = this.parentNode.querySelector('.npc-life');
            let targetName = this.parentNode.querySelector('.npc-name');
            let name = targetName.textContent;
            let targetValue = parseInt(target.textContent, 10);
            let hitDamage = Math.ceil(3 + diceResult + ratio/2);
            if (hitDamage < 0) {
                hitDamage = 0;
            }
            M.toast({html: `You inflicted ${hitDamage} damages !`, classes: "green-text text-lighten-1"}, 5000);
            console.log("damage to npc: " + hitDamage);
            let newValue = targetValue - hitDamage;
            let finalValue;
            if(newValue > 0){
                finalValue = newValue;

                $(monsterCard).animateCss("bounce")
            }else{
                finalValue = '0 - dead';
                M.toast({html:`You killed ${name} !`, classes: "green-text text-lighten-2"}, 5000);
                $(monsterCard).animateCss("hinge", () => {
                    this.parentNode.remove();
                    isVictory();
                });


            }
            target.innerHTML = finalValue;
            let heroDmg = Math.ceil(7 + nbNpcs - hitDamage/2);
            updateHeroDamages(heroDmg);
            console.log("damages to hero: " + heroDmg);
        };

        //Bind event
        for (let i = 0 ; i < attackElts.length; i++) {
            attackElts[i].addEventListener('click', attackFunc, false);
        }

    }
}

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
