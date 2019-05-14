//Controller for loading

var loadingController = (function(cookie){
    const renderLoaderIcon = (parrent) => {
        const loader = `
            <div class="loading">
                <svg width="40px"  height="40px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-rolling">
                    <circle cx="50" cy="50" fill="none" ng-attr-stroke="{{config.color}}" ng-attr-stroke-width="{{config.width}}" ng-attr-r="{{config.radius}}" ng-attr-stroke-dasharray="{{config.dasharray}}" stroke="#4e2187" stroke-width="10" r="30" stroke-dasharray="141.37166941154067 49.12388980384689">
                    </circle>
                </svg>
            </div>
        `;
        parrent.insertAdjacentHTML('beforeend', loader);
    };
    const clearLoader = () =>{
        const loader = document.querySelector('.loading');
        if(loader){
            loader.parentElement.removeChild(loader);
        }
    };

    var newButtonsIDs = function(htmlElement){
        var newButtons = htmlElement.querySelectorAll('.meme-post');
        var newButtonIDarray = [];
        newButtons.forEach(element => {
            newButtonIDarray.push(element.getAttribute("data-meme-post"));
        });
        return newButtonIDarray;
    }

    var main_document = document.querySelector('.main-column');
    window.addEventListener('scroll',function(){
        if(Math.round(window.innerHeight + window.scrollY) === document.documentElement.scrollHeight){
            renderLoaderIcon(main_document);
            $.ajax({
                url:'show_memes.php',
                dataType: 'html',
                type: 'POST',
                data:{
                    'memeCount': document.querySelectorAll('.meme-post').length,
                    'categories': window.location.pathname.split('/')[2],
                },
                success: function(html){
                    main_document.insertAdjacentHTML('beforeend',html);
                    clearLoader();
                }
            }).done(function(results){
                var htmlDOMelement = new DOMParser().parseFromString(results,'text/html');
                var buttonArrayLoaded = newButtonsIDs(htmlDOMelement);
                var newButtonArray = [];
                buttonArrayLoaded.forEach(el =>{
                    newButtonArray.push(document.querySelector(`[data-downvote-id="${el}"]`));
                    newButtonArray.push(document.querySelector(`[data-upvote-id="${el}"]`));
                })
                cookie.init(newButtonArray,buttonArrayLoaded);
            });

        }
    })
})(cookiesController);