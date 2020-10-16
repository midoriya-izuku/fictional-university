import $ from "jquery";
class Search{
    constructor(){
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.searchResultsDiv = $("#search-overlay__results");
        this.isSpinnerVisible = false;
        this.previousValue;
        this.searchTimer;
        this.events();
    }

    events(){
        this.openButton.on("click", this.openOverlay.bind(this))
        this.closeButton.on("click", this.closeOverlay.bind(this))
        this.searchField.on("keydown", this.typeSearch.bind(this))

    }

    typeSearch(){
        if(this.previousValue != this.searchField.val()){
            clearTimeout(this.searchTimer)
            if(this.searchField.val()){
                if(!this.isSpinnerVisible){
                    this.searchResultsDiv.html('<div class="spinner-loader"></div>')
                    this.isSpinnerVisible = true
                }
                this.searchTimer = setTimeout(this.getResults.bind(this), 750) 
            }
            else{
                this.isSpinnerVisible = false
                this.searchResultsDiv.html('')
            }
           
        }
        this.previousValue = this.searchField.val()
    }

    getResults(){
        $.getJSON(`http://localhost:90/wordpress/wp-json/wp/v2/posts?search=${this.searchField.val()}`,(posts) => {
            this.searchResultsDiv.html(`
            <h2 class="search-overlay__section-title">Search Results</h2>
            <ul class="link-list min-list">
                ${posts.map(post => `<li> <a href="${post.link}">${post.title.rendered}</a></li>`)}
            </ul>`)
        })
        this.isSpinnerVisible = false
    }

    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active")
        $("body").addClass("body-no-scroll")
    }

    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active")
        $("body").removeClass("body-no-scroll")
    }
}

export default Search;