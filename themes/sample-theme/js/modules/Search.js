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
        $.getJSON(`${universityData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`, (results) => {
            console.log(results)
            this.searchResultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> General Information </h2>
                    ${results.generalInfo.length>0 ? ` <ul class="link-list min-list">
                    ${results.generalInfo.map(result => `<li> <a href="${result.permalink}">${result.title} </a>${result.postType == 'post' ? `by ${result.authorName}` : ''}</li>`)}
                    </ul>`:
                    `<p> No results found </p>`}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Programs </h2>
                    ${results.programs.length>0 ? ` <ul class="link-list min-list">
                    ${results.programs.map(result => `<li> <a href="${result.permalink}">${result.title} </a></li>`)}
                    </ul>`:
                    `<p> No related programs found. <a href="${universityData.root_url}/programs">View all Programs</a> </p>`}
                    <h2 class="search-overlay__section-title"> Professors </h2>
                    ${results.professors.length>0 ? ` <ul class="professor-cards">
                    ${results.professors.map(result => `
                        <li class="professor-card__list-item">
                            <a href="${result.permalink}" class="professor-card">
                            <img src="${result.image}" class="professor-card__image">
                            <span class="professor-card__name">${result.title}</span>
                            </a>
                        </li>
                    `)}
                    </ul>`:
                    `<p> No results found </p>`}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Campuses</h2>
                    ${results.campuses.length>0 ? ` <ul class="link-list min-list">
                    ${results.campuses.map(result => `<li> <a href="${result.permalink}">${result.title} </a></li>`)}
                    </ul>`:
                    `<p> No related campuses found. <a href="${universityData.root_url}/campuses">View all Campuses</a> </p>`}
                    <h2 class="search-overlay__section-title">Events</h2>
                    ${results.events.length>0 ? `
                    ${results.events.map(result => `
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="<?php the_permalink();?>">
                                <span class="event-summary__month">
                                    ${result.month}
                                </span>
                                <span class="event-summary__day">
                                    ${result.day}
                                </span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${result.permalink}">${result.title}</a></h5>
                                <p>${result.description}<a href="${result.permalink}" class="nu gray">Learn more</a></p>
                            </div>
                        </div>
                    `)}
                        `:
                    `<p> No related events found. <a href="${universityData.root_url}/campuses">View all Events</a> </p>`}
                   
                </div>
            </div>
           `)
        this.isSpinnerVisible = false
        })
       
    }

    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active")
        this.searchField.trigger("focus")
        $("body").addClass("body-no-scroll")
    }

    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active")
        $("body").removeClass("body-no-scroll")
    }
}

export default Search;