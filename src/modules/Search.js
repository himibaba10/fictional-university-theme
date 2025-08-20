import $ from "jquery";

class Search {
  constructor() {
    this.addSearchHTML();

    this.searchTerm = $("#search-term");
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.searchTimeout = null;

    this.events();
  }

  events() {
    this.openButton.on("click", this.openOverlay);
    this.closeButton.on("click", this.closeOverlay);
    $(document).on("keydown", this.keyPressDispatcher);
    this.searchTerm.on("input", this.searchTermHandler);
  }

  searchTermHandler = () => {
    clearTimeout(this.searchTimeout);

    if (!this.isSpinnerVisible) {
      this.resultsDiv.html("<div class='spinner-loader'></div>");
      this.isSpinnerVisible = true;
    }

    if (!this.searchTerm.val()) {
      this.resultsDiv.html("");
      this.isSpinnerVisible = false;
      return;
    }

    this.searchTimeout = setTimeout(() => {
      this.getResults();
    }, 1000);
  };

  getResults = () => {
    $.getJSON(
      `${
        universityData.rootUrl
      }/wp-json/university/v1/search?term=${this.searchTerm
        .val()
        .toLowerCase()}`,
      (results) => {
        console.log(results);
        this.resultsDiv.html(`
          <div class="row">
            <div class="one-third">
              ${this.showGeneralHtml(
                "General Information",
                results.generalInfo,
                "No general info found."
              )}
            </div>
            <div class="one-third">
              ${this.showGeneralHtml(
                "Programs",
                results.programs,
                "No program found."
              )}

              ${this.showProfessorsHtml(results.professors)}
            </div>
            <div class="one-third">
              ${this.showGeneralHtml(
                "Campuses",
                results.campuses,
                "No campus found."
              )}

              ${this.showEventsHtml(results.events)}
            </div>
          </div>
        `);
      }
    ).catch((err) => {
      console.log(err);
    });

    this.isSpinnerVisible = false;
  };

  keyPressDispatcher = (e) => {
    if (
      e.keyCode === 83 &&
      !this.isOverlayOpen &&
      !$("input, textarea").is(":focus")
    ) {
      this.openOverlay();
    }

    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  };

  openOverlay = () => {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.isOverlayOpen = true;

    setTimeout(() => this.searchTerm.trigger("focus"), 50);
  };

  closeOverlay = () => {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
  };

  addSearchHTML = () => {
    $("body").append(`
    <div class="search-overlay">
      <div class="search-overlay__top">
          <div class="container">
              <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
              <input type="text" class="search-term" id="search-term" placeholder="What are you looking for?">
              <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
      </div>

      <div class="container">
          <div id="search-overlay__results"></div>
      </div>
    </div>  
    `);
  };

  showGeneralHtml = (title, data, error) => {
    return `
    <h2 class="search-overlay__section-title">${title}</h2>
    ${
      data.length
        ? `
                  <ul class="link-list min-list">
                    ${data
                      .map(
                        (item) =>
                          `<li><a href="${item.permalink}">${item.title}</a> ${
                            item.postType === "post"
                              ? ` by ${item.authorName}`
                              : ""
                          }</li>`
                      )
                      .join("")}
                  </ul>
                  `
        : `<p>${error}</p>`
    }
    `;
  };

  showProfessorsHtml = (data) => {
    return `
    <h2 class="search-overlay__section-title">Professors</h2>
    ${
      data.length
        ? `
                  <ul class="professor-cards">
                    ${data
                      .map(
                        (item) =>
                          `<li class="professor-card__list-item">
                            <a class="professor-card" href="${item.permalink}">
                              <img class="professor-card__image" src="${item.thumbnail}"
                                alt="${item.title}">
                              <span class="professor-card__name">${item.title}</span>
                            </a>
                          </li>`
                      )
                      .join("")}
                  </ul>
                  `
        : `<p>No professor found.</p>`
    }
    `;
  };

  showEventsHtml = (data) => {
    return `
    <h2 class="search-overlay__section-title">Events</h2>
    ${
      data.length
        ? data
            .map(
              (item) =>
                `<div class="event-summary">
                    <a class="event-summary__date t-center" href="${item.permalink}">
                        <span class="event-summary__month">${item.eventMonth}</span>
                        <span class="event-summary__day">${item.eventDate}</span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a
                                href="${item.permalink}">${item.title}</a>
                        </h5>
                        <p>
                            ${item.excerpt}"
                            <a href="${item.permalink}" class="nu gray">Read more</a>
                        </p>
                    </div>
                </div>`
            )
            .join("")
        : `<p>No event found.</p>`
    }
    `;
  };
}

export default Search;
