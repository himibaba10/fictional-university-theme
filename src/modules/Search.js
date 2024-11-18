import $ from "jquery";

class Search {
  constructor() {
    this.addSearchHTML();
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.overlay = $(".search-overlay");
    this.searchInput = $("#search-term");
    this.resultsDiv = $("#search-overlay__results");
    this.isOverlayOpen = false;
    this.timer = null;
    this.isSpinnerVisible = false;

    this.event();
  }

  event() {
    this.openButton.on("click", this.showOverlay.bind(this));
    this.closeButton.on("click", this.hideOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchInput.on("input", this.typingLogic.bind(this));
  }

  showOverlay() {
    this.overlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.isOverlayOpen = true;

    // Add small delay to ensure overlay is visible first
    setTimeout(() => {
      this.searchInput.trigger("focus");
    }, 100);
  }

  hideOverlay() {
    this.overlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
    this.searchInput.val("");
    this.resultsDiv.html("");
  }

  keyPressDispatcher(e) {
    if (
      e.key === "s" &&
      !this.isOverlayOpen &&
      !$("input, textarea").is(":focus")
    ) {
      this.showOverlay();
    }

    if (e.key === "Escape" && this.isOverlayOpen) {
      this.hideOverlay();
    }
  }

  typingLogic(e) {
    clearTimeout(this.timer);

    if (!this.searchInput.val()) {
      this.isSpinnerVisible = false;
      return this.resultsDiv.html("");
    }

    if (!this.isSpinnerVisible && this.searchInput.val()) {
      this.resultsDiv.html('<div class="spinner-loader"></div>');
      this.isSpinnerVisible = true;
    }
    this.timer = setTimeout(this.getResults.bind(this, e), 1000);
  }

  getResults(e) {
    this.isSpinnerVisible = false;

    $.getJSON(
      `${
        universityData.root_url
      }/wp-json/university/v1/search?term=${this.searchInput.val()}`,
      (results) => {
        this.resultsDiv.html(`
          <div class="row">
            <div class="one-third">
              <h2 class="search-overlay__section-title">General Information</h2>
              ${
                results.generalInfo.length
                  ? `<ul class="link-list min-list">
                    ${results.generalInfo
                      .map(
                        (item) =>
                          `<li><a href="${item.permalink}">${item.title}</a></li>`
                      )
                      .join(" ")}
                  </ul>`
                  : `<p>No general info is found.</p>`
              }
            </div>
            <div class="one-third">
              <h2 class="search-overlay__section-title">Programs</h2>
              ${
                results.programs.length
                  ? `<ul class="link-list min-list">
                    ${results.programs
                      .map(
                        (item) =>
                          `<li><a href="${item.permalink}">${item.title}</a></li>`
                      )
                      .join(" ")}
                  </ul>`
                  : `<p>No program is found. See <a href="${universityData.root_url}/programs">all programs</a></p>`
              }

              <h2 class="search-overlay__section-title">Professors</h2>
              <ul class='professor-cards'>
                ${results.professors
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
                  .join(" ")}
              </ul>
            </div>
            <div class="one-third">
              <h2 class="search-overlay__section-title">Campuses</h2>

              <h2 class="search-overlay__section-title">Events</h2>
            </div>
          </div>
          `);
      }
    );
  }

  addSearchHTML() {
    $("body").append(`
      <div class="search-overlay">
          <div class="search-overlay__top">
              <div class="container">
                  <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                  <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term"
                      autocomplete="off">
                  <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
              </div>
          </div>

          <div class="container">
              <div id="search-overlay__results"></div>
          </div>
      </div>
      `);
  }
}

export default Search;
