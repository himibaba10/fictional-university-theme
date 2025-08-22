import $ from "jquery";

class MyNotes {
  constructor() {
    this.notesWrapper = $("#my-notes");
    this.createBtn = $(".submit-note");
    this.editBtn = $(".edit-note");
    this.updateBtn = $(".update-note");
    this.deleteBtn = $(".delete-note");
    this.noteLimitMsg = $(".note-limit-message");
    this.events();
  }

  events() {
    this.createBtn.on("click", this.createNote);
    this.notesWrapper.on("click", ".edit-note", this.editNote);
    this.notesWrapper.on("click", ".update-note", this.updateNote);
    this.notesWrapper.on("click", ".delete-note", this.deleteNote);
  }

  // Methods here
  createNote = () => {
    const title = $(".new-note-title").val();
    const content = $(".new-note-body").val();

    const ourNewPost = {
      title,
      content,
      status: "private",
    };

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      },
      data: ourNewPost,
      url: `${universityData.rootUrl}/wp-json/wp/v2/note`,
      type: "POST",
      success: (response) => {
        $(`
        <li data-id="${response.id}">
            <input class="note-title-field" type="text" value="${response.title.rendered}" readonly>
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>
                Delete</span>
            <textarea class="note-body-field" readonly>${response.content.raw} </textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>
                Save</span>
        </li>  
        `)
          .prependTo("#my-notes")
          .hide()
          .slideDown();

        console.log("Success");
        console.log(response);

        $(".new-note-title, .new-note-body").val("");
      },
      error: (response) => {
        if (response.responseText == "You have reached your note limit.") {
          this.noteLimitMsg.addClass("active");
        }

        console.log("Error");
        console.log(response);
      },
    });
  };

  editNote = (e) => {
    const thisNote = $(e.target).parents("li");

    if (thisNote.data("state") == "editable") {
      this.makeNoteReadonly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
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
      )}?force=true`,
      type: "DELETE",
      success: (response) => {
        thisNote.slideUp();
        console.log("Success");
        console.log(response);

        if (this.noteLimitMsg.hasClass("active")) {
          this.noteLimitMsg.removeClass("active");
        }
      },
      error: (response) => {
        console.log("Error");
        console.log(response);
      },
    });
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
}

export default MyNotes;
