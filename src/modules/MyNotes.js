import $ from "jquery";

class MyNotes {
  constructor() {
    this.editBtn = $(".edit-note");
    this.deleteBtn = $(".delete-note");
    this.events();
  }

  events() {
    this.deleteBtn.on("click", this.deleteNote);
  }

  // Methods here
  deleteNote = (e) => {
    const thisNote = $(e.target).parents("li");

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      },
      url: `${universityData.rootUrl}/wp-json/wp/v2/note/${thisNote.data(
        "id"
      )}`,
      type: "DELETE",
      success: (response) => {
        thisNote.slideUp();
        console.log("Success");
        console.log(response);
      },
      error: (response) => {
        console.log("Error");
        console.log(response);
      },
    });
  };
}

export default MyNotes;
