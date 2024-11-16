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
    $.when(this.fetchData("posts"), this.fetchData("pages")).then(
      (posts, pages) => {
        const combinedResults = [...posts[0], ...pages[0]];
        this.resultsDiv.html(`
        <h2 class="search-overlay__section-title">General Information</h2>
        ${
          combinedResults.length
            ? `
          <ul class="link-list min-list">
            ${combinedResults
              .map(
                (item) =>
                  `<li><a href="${item.link}">${item.title.rendered}</a> ${
                    item.type == "post" ? `- By ${item.authorName}` : ``
                  }</li>`
              )
              .join(" ")}
          </ul>`
            : "<p>No general info found.</p>"
        }
        `);
      }
    );

    // $.getJSON(
    //   `${
    //     universityData.root_url
    //   }/wp-json/wp/v2/posts?search=${this.searchInput.val()}`,
    //   (posts) => {}
    // );
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

  fetchData(postType) {
    return $.getJSON(
      `${
        universityData.root_url
      }/wp-json/wp/v2/${postType}?search=${this.searchInput.val()}`
    );
  }
}

export default Search;
