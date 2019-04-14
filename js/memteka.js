/***
 * Sausainiu kontroleris
 */
(function(){

//Mygtuku masyvas, imamas is viso page
var buttonArray = document.querySelectorAll('.control-button.voting');

function changeColorAfterClick(el,backColor, borderColor, contentColor){
    el.style.backgroundColor = backColor;
    el.style.borderColor = borderColor;
    el.childNodes[1].style.color = contentColor;
}

/**
 * Sausainio sukūrimas ir jo modifikavimas
 * @param {Object} obj  - vartotojo JSON objektas
 */
function setCookie(obj){
    var date = new Date();
    date.setTime(date.getTime() + (30*24*60*60*1000));
    var expires = 'expires='+ date.toUTCString() + ';';
    document.cookie = 'memeObj=' + obj + ';' + expires;
}

/**
 * Sausainiu nuskaitymo metodas, kuris grazina JSON objektą
 */
function getCookie(){
    var cookieElements = document.cookie.split(';');
    var jsonElement = cookieElements[0].split('memeObj=');
    return document.cookie.indexOf("memeObj=") == -1 ? null : JSON.parse(jsonElement[1]);
}

/**
 * Paspausto mygtuko disablinimas ir cookie sukurimas jei jis reikalingas
 */
buttonArray.forEach(el => el.addEventListener('click',()=>{
    //Kintamieji naudojami apdoroti duomenis
    var anchorArray =  el.parentNode.parentNode.querySelector('.post-meta > a.point').innerHTML;
    var points = anchorArray.trim().split('>');
    var pointsElement = points[1].trim().split(' ');
    var pointsInt = parseInt(pointsElement[0]);

    //Sausainio apdorojimas 
    var userJSON = getCookie();
    if(userJSON == null){
            userJSON = {
                date: new Date(),
                memeButton: []
        }
    }

    var memeButtonID;
    var memePostID;
    if(el.classList.contains('upvote')){
        el.parentNode.childNodes[3].style.pointerEvents = "auto";
        memeButtonID = "data-upvote-id=" + el.parentNode.childNodes[1].getAttribute("data-upvote-id");
        memePostID = el.parentNode.childNodes[1].getAttribute("data-upvote-id");

        if(userJSON.memeButton.indexOf("data-downvote-id=" + memePostID) !== -1 && getCookie()){
            userJSON = removeCookieElement(el.parentNode.childNodes[1],"data-upvote-id");
            pointsInt+=2;
        } else{
            pointsInt++;
        }

        changeColorAfterClick(el.parentNode.childNodes[3],"white","#0099cc","#0099cc");
        changeColorAfterClick(el,"#056495","#056495","white");
    } else if(el.classList.contains('downvote')){
        el.parentNode.childNodes[1].style.pointerEvents = "auto";
        memeButtonID = "data-downvote-id=" + el.parentNode.childNodes[3].getAttribute("data-downvote-id");
        memePostID = el.parentNode.childNodes[3].getAttribute("data-downvote-id");

        if(userJSON.memeButton.indexOf("data-upvote-id=" + memePostID) !== -1 && getCookie()){
            userJSON = removeCookieElement(el.parentNode.childNodes[3],"data-downvote-id");
            pointsInt-=2;
        } else{
            pointsInt--;
        }

        changeColorAfterClick(el.parentNode.childNodes[1],"white","#0099cc","#0099cc");
        changeColorAfterClick(el,"#056495","#056495","white");
    }
    el.style.pointerEvents = "none";
    updateMemeVotes(memePostID,pointsInt);

    //Sukuriamas cookie vartotojui 
    userJSON.memeButton.push(memeButtonID);
    var jsonString = JSON.stringify(userJSON);
    setCookie(jsonString);

    //Tasku atvaizdavimas vartotojo sasajoje
    points[1] = ' ' + pointsInt + " taškų"; 
    var string = points.join('>');
    el.parentNode.parentNode.querySelector('.post-meta > a.point').innerHTML = string;

}));

function removeCookieElement(pressedButton, type){
    var jsonObject = getCookie();
    for(var i = 0; i < jsonObject.memeButton.length; i++){
        var buttonElement = jsonObject.memeButton[i].split('=');
        var buttonId = pressedButton.getAttribute(type);
        if((buttonId === buttonElement[1] && pressedButton.classList.contains('upvote')) || 
           (buttonId === buttonElement[1] && pressedButton.classList.contains('downvote'))){
            jsonObject.memeButton.splice(i);
        } 
    }
    return jsonObject;
}

function updateMemeVotes(memePostID,votesNumber){
    $.ajax({
        url:'update-meme.php',
        type:"POST",
        data:{'memeID' : memePostID, 
              'votes' : votesNumber 
            },
        success:  function(){                      
        }
    })
}

/**
 * Mygtuko isjungimai puslapio ijungimo metu
 */
function disableButtons(){
    var jsonObject = getCookie();
    //jsonObject.memeButton.forEach(el => {console.log(el)});
    jsonObject.memeButton.forEach(el => {
        var buttonElement = el.split('=');
        buttonElement[1] = '"' + buttonElement[1] + '"';
        var elementAttribute = buttonElement.join('=');
        var element = document.querySelector(`[${elementAttribute}]`);
        if(element){
            if(buttonElement[0] === "data-upvote-id"){
                changeColorAfterClick(element,"#056495","#056495","white");
            } else if(buttonElement[0] === "data-downvote-id"){
                changeColorAfterClick(element,"#056495","#056495","white");
            }        
            element.style.pointerEvents = "none";
        }
    });
}

if(getCookie()){
    disableButtons();
}
})();


/***
 * Mobilaus meniu controleris
 */
(function(){
    var navMenu = document.querySelector('.mobile-menu-background');

    function windowOnClick(event) {
        var windowDiv = document.querySelector('.mobile-menu-background');
        if (event.target === windowDiv) {
            toggleMeniu();
            toggleMeniuIcon();
        }
    }
    
    function toggleMeniu(){
        document.querySelector('.mobile-menu-background').classList.toggle('meniu-close');
    }
    
    function toggleMeniuIcon(){
        document.querySelector('#mobile-nav').classList.toggle('meniu-close');
    }
    
    var menuDIV = document.querySelector('.mobile-menu-content  > ul > #categories-hr');
    var liElement = [];
    for(var i =0; i < categoriesArray.length; i++){
        var liElement = `<li class="mobile-menu-item">
                            <a>${categoriesArray[i]}</a>
                        </li>`;
        menuDIV.insertAdjacentHTML('beforeend',liElement);
    }
    
    document.querySelector('#mobile-nav').addEventListener('click',function(){
        toggleMeniu();    
        toggleMeniuIcon();
    });
    
    window.addEventListener("click", windowOnClick);
})();
