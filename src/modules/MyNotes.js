import $ from "jquery";

class MyNotes {
  constructor() {
    this.editBtn = $(".edit-note");
    this.deleteBtn = $(".delete-note");
    this.updateBtn = $(".update-note");
    this.events();
  }

  events() {
    this.editBtn.on("click", this.editNote);
    this.updateBtn.on("click", this.updateNote);
    this.deleteBtn.on("click", this.deleteNote);
  }

  // Methods here
  editNote = (e) => {
    const thisNote = $(e.target).parents("li");

    if (thisNote.data("state") == "editable") {
      this.makeNoteReadonly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
  };

  makeNoteEditable = (thisNote) => {
    thisNote
      .find(".note-title-field, .note-body-field")
      .removeAttr("readonly")
      .addClass("note-active-field");

    thisNote.find(".update-note").addClass("update-note--visible");

    thisNote
      .find(".edit-note")
      .html(`<i class="fa fa-times" aria-hidden="true"></i> Cancel`);

    thisNote.data("state", "editable");
  };

  makeNoteReadonly = (thisNote) => {
    thisNote
      .find(".note-title-field, .note-body-field")
      .attr("readonly", "readonly")
      .removeClass("note-active-field");

    thisNote.find(".update-note").removeClass("update-note--visible");

    thisNote
      .find(".edit-note")
      .html(`<i class="fa fa-pencil" aria-hidden="true"></i> Edit`);

    thisNote.data("state", "cancel");
  };

  updateNote = (e) => {
    const thisNote = $(e.target).parents("li");

    const updatedContent = {
      title: thisNote.find(".note-title-field").val(),
      content: thisNote.find(".note-body-field").val(),
    };

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      },
      data: updatedContent,
      url: `${universityData.rootUrl}/wp-json/wp/v2/note/${thisNote.data(
        "id"
      )}`,
      type: "POST",
      success: (response) => {
        this.makeNoteReadonly(thisNote);
        console.log("Success");
        console.log(response);
      },
      error: (response) => {
        console.log("Error");
        console.log(response);
      },
    });
  };

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
