import $ from "jquery";

class Search {
  constructor() {
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
  }

  hideOverlay() {
    this.overlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
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
    console.log(this.searchInput.val());

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
    console.log("Hi!");
    $.getJSON(
      `${
        new URL(window.location.href).origin
      }/custom-university/wp-json/wp/v2/posts?search=${this.searchInput.val()}`,
      (posts) => {
        console.log(posts);
      }
    );
    this.resultsDiv.html(e.target.value);
  }
}

export default Search;
