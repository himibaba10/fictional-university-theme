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

    // Add small delay to ensure overlay is visible first
    setTimeout(() => {
      this.searchInput.trigger("focus");
    }, 100);
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
    $.getJSON(
      `${
        new URL(window.location.href).origin
      }/custom-university/wp-json/wp/v2/posts?search=${this.searchInput.val()}`,
      (posts) => {
        if (posts.length > 0) {
          posts.map((post) => {
            this.resultsDiv.html(`
              <h2 class="search-overlay__section-title">General Information</h2>
              <ul class="link-list min-list">
                ${posts
                  .map(
                    (post) =>
                      `<li><a href="${post.link}">${post.title.rendered}</a></li>`
                  )
                  .join(" ")}
              </ul>
              `);
          });
        } else {
          this.resultsDiv.html("<b>No posts found</b>");
        }
      }
    );
  }
}

export default Search;
