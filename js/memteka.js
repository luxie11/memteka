//Mygtuku masyvas - upvote,downvote,komentarai
var buttonArray = document.querySelectorAll('.control-button');

function changeColorAfterClick(el,backColor,borderColor,contentColor){
    el.style.backgroundColor = backColor;
    el.style.borderColor = borderColor;
    el.childNodes[1].style.color = contentColor;
}

buttonArray.forEach(el => el.addEventListener('click',()=>{
    var anchorArray =  el.parentNode.parentNode.querySelector('.post-meta > a.point').innerHTML;
    var points = anchorArray.trim().split('>');
    var pointsElement = points[1].trim().split(' ');
    var pointsInt = parseInt(pointsElement[0]);
    if(el.classList.contains('upvote')){
        pointsInt++;
        el.parentNode.childNodes[3].style.pointerEvents = "auto";
        changeColorAfterClick(el.parentNode.childNodes[3],"white","#0099cc","#0099cc");
        changeColorAfterClick(el,"#056495","#056495","white");
    } else if(el.classList.contains('downvote')){
        pointsInt--;
        el.parentNode.childNodes[1].style.pointerEvents = "auto";
        changeColorAfterClick(el.parentNode.childNodes[1],"white","#0099cc","#0099cc");
        changeColorAfterClick(el,"#056495","#056495","white");
    }
    el.style.pointerEvents = "none";

    points[1] = ' ' + pointsInt + " taškų"; 
    var string = points.join('>');
    el.parentNode.parentNode.querySelector('.post-meta > a.point').innerHTML = string;
}));

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