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
