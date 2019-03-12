//Mygtuku masyvas - upvote,downvote,komentarai
var buttonArray = document.querySelectorAll('.control-button');

buttonArray.forEach(el => el.addEventListener('click',()=>{
    var anchorArray =  el.parentNode.parentNode.querySelector('.post-meta > a.point').innerHTML;
    var points = anchorArray.trim().split('>');
    var pointsInt = parseInt(points[1].split(' '));
    if(el.classList.contains('upvote')){
        pointsInt++;
    } else if(el.classList.contains('downvote')){
        pointsInt--;
    }
    points[1] = pointsInt + " taškų"; 
    var string = points.join('>');
    el.parentNode.parentNode.querySelector('.post-meta > a.point').innerHTML = string;
}));
