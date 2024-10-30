import $ from "jquery";

class Search {
  constructor() {
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.overlay = $(".search-overlay");
    this.searchInput = $("#search-term");
    this.isOverlayOpen = false;
    this.timer = null;

    this.event();
  }

  event() {
    this.openButton.on("click", this.showOverlay.bind(this));
    this.closeButton.on("click", this.hideOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchInput.on("input", (e) => {
      clearTimeout(this.timer);

      this.timer = setTimeout(() => {
        console.log(e.target.value);
      }, 1000);
    });
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
    if (e.key === "s" && !this.isOverlayOpen) {
      this.showOverlay();
    }

    if (e.key === "Escape" && this.isOverlayOpen) {
      this.hideOverlay();
    }
  }
}

export default Search;
