import $ from "jquery";

class MyNotes{
    constructor(){
        this.events();
    }

    events(){
        $(".submit-note").on("click", this.createNote)
        $("#my-notes").on("click", ".edit-note", this.editNote.bind(this))
        $("#my-notes").on("click", ".update-note", this.updateNote.bind(this))
        $("#my-notes").on("click", ".delete-note", this.deleteNote)
    }

    createNote(e){
        let newNoteData = {
            'title': $(".new-note-title").val(),
            'content' : $(".new-note-body").val(),
            'status' : 'publish'
        }

        $.ajax({
            beforeSend:(xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url:`${universityData.root_url}/wp-json/wp/v2/note/`,
            type:'POST',
            data:newNoteData,
            success:(response)=>{
                $(".new-note-title, .new-note-body").val('')
                $(`  <li data-id="${response.id}">
                <input readonly type="text" class="note-title-field" value="${response.title.raw}">
                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>

            </li>`).prependTo("#my-notes").hide().slideDown()
            },
            error:(e)=>{
                if(e.responseText == "Note limit reached"){
                    $(".note-limit-message").addClass("active")
                }
            }
        })
    }

    editNote(e){
        let currentNote = $(e.target).parent("li");
        if(currentNote.data("state") == "editable"){
            this.makeNoteReadOnly(currentNote)
        }
        else{
            this.makeNoteEditable(currentNote)
        }
    }

    makeNoteReadOnly(currentNote){
        currentNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit')
        currentNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field")
        currentNote.find(".update-note").removeClass("update-note--visible")
        currentNote.data("state", "cancel")
    }

    makeNoteEditable(currentNote){
        currentNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel')
        currentNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field")
        currentNote.find(".update-note").addClass("update-note--visible").fadeIn()
        currentNote.data("state", "editable")
    }

    updateNote(e){
        
        let currentNote = $(e.target).parent("li");

        let updatedNoteData = {
            'title': currentNote.find(".note-title-field").val(),
            'content' : currentNote.find(".note-body-field").val()
        }

        $.ajax({
            beforeSend:(xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url:`${universityData.root_url}/wp-json/wp/v2/note/${currentNote.data("id")}`,
            type:'POST',
            data:updatedNoteData,
            success:(response)=>{
                this.makeNoteReadOnly(currentNote)
            },
            error:(e)=>{
                console.log(e)
            }
        })

    
    }

    deleteNote(e){
        let currentNote = $(e.target).parent("li");

        $.ajax({
            beforeSend:(xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url:`${universityData.root_url}/wp-json/wp/v2/note/${currentNote.data("id")}`,
            type:'DELETE',
            success:(response)=>{
                currentNote.slideUp();
                if(response.userNotesCount < 100){
                    $(".note-limit-message").removeClass("active")

                } 
            },
            error:(e)=>{
                console.log(e)
            }
        })

    }
}

export default MyNotes;