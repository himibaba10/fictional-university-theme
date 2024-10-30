import $ from "jquery";

class Search {
  constructor() {
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.overlay = $(".search-overlay");
    this.isOverlayOpen = false;

    this.event();
  }

  event() {
    this.openButton.on("click", this.showOverlay.bind(this));
    this.closeButton.on("click", this.hideOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
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
