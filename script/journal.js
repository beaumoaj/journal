dojo.require("dijit.Dialog");
dojo.require("dijit.form.Button");
dojo.require("dijit.Editor");
dojo.require("dijit._editor.plugins.AlwaysShowToolbar");
dojo.require("dojox.editor.plugins.SafePaste");
dojo.require("dijit._editor.plugins.FontChoice");
dojo.ready(function () {
//require(["dojo/dom-construct"]);
    var explanations = {
        title: "The title identifies the key issue being written about. If you have multiple issues consider writing multiple entries. (I always write multiple entries under the same date for different issues. It makes it easier to locate issues later when I want to refer back to a topic.",
        description: "Describe what prompted you to write this entry. This is what you did or what you experienced. It could also describe the primary themes from a lecture or reading.",
        reflection: "Describe what you gained from or what challenged you from this experience or event. Also consider how it has influenced your understanding or how it may have presented a problem?",
        concepts: "Here you might be looking for key concepts or issues related to the topic. Why are these concepts important or why are they causing difficulty?",
        whatNext: "This part of the entry might be considering the possibilities for reinforcing what you think you have learnt or considering what you might do to address any issues that need following up. It may also raise some possibilities for new avenues of learning.",
        referenceList: "Always record a bibliography entry for any references that you may have used for the entry.",
        notes: "Don’t be afraid to add reminder notes to yourself. These may not relate directly to the entry but they might be reminders stimulated by your thinking."
    };
//require(["dojo/ready"], function (ready) {
//    ready(function () {
    // This function won't run until the DOM has loaded and other modules that register
    // have run.
    // Create the dialog
    secondDlg = new dijit.Dialog();
    secondDlg.attr('title', 'Edit');
    // Create a container div
    var div = dojo.create("div", {id: 'dialog'});
    dojo.create("div", {id: 'explain'}, div);
    // Add a text area
    dojo.create("div", {id: 'editContent'}, div);
    // Add a save button
    //new dijit.form.Button({label: 'Save', onClick: dialogSave}).placeAt(div, 'last');
    // Add the content to the dialog
    secondDlg.attr("content", div);
    secondDlg.attr("style", "width: 80%");
    // Turn the textarea into a rich text editor
    //tinyMCE.execCommand("mceAddControl", true, "editContent");
    //tinymce.EditorManager.execCommand('mceAddEditor', true, "editContent");
//        });
//        });
    var secondDlg;
    var theSection;
    var theContentID;
    /*
    function dialogSave() {
        // Get the editor
        //var ed = tinyMCE.get('editContent');
        var input = document.forms['entryForm'][theSection];
        //input.value = ed.getContent();
        input.value = myEditor.get("value");
        var divElement = dojo.byId(theContentID);
        //divElement.innerHTML = ed.getContent();
        divElement.innerHTML = myEditor.get("value");
        secondDlg.hide();
    }
    */

// Init the rich text editor
    var myEditor = new dijit.Editor({
        height: '100px',
        extraPlugins: [dijit._editor.plugins.AlwaysShowToolbar,
            dojox.editor.plugins.SafePaste,
            'fontName', 'fontSize', 'formatBlock'
        ]
    }, dojo.byId('editContent'));
    myEditor.startup();
    myEditor.watch('value', function () {
        editorText = myEditor.get('value');
        var input = document.forms['entryForm'][theSection];
        input.value = editorText;
        var divElement = dojo.byId(theContentID);
        divElement.innerHTML = editorText;
        //secondDlg.hide();
        //alert("new comment " + value);
    });


    /*
     tinymce.init({
     selector: "textarea",
     plugins: [
     "advlist autolink lists link image charmap print preview anchor",
     "searchreplace visualblocks code fullscreen",
     "insertdatetime media table contextmenu paste moxiemanager"
     ],
     toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
     });
     */
//dojo.addOnLoad(function() {
//});

    edit = function (section) {
        dirty = true;
        var contentID = section;
        // Get the content
        //var content = dojo.byId(contentID).innerHTML;
        var content = dojo.byId(contentID);
        var explainDiv = dojo.byId('explain');
        //alert("Explanation: " + explanations[section]);
        explainDiv.innerHTML = explanations[section];
        //alert("content is " + content);
        //var content2 = dojo.byId('editContent').innerHTML;
        //("content2 is " + content2);
        var ta = dojo.byId('editContent');
        // Make info available to save function
        theSection = section;
        theContentID = contentID;
        secondDlg.show();
        // Update content and title of dialog
        //tinyMCE.get('editContent').setContent(content);
        myEditor.set("value", content.innerHTML);
        secondDlg.attr('title', 'Edit: ' + section);
        // Show the dialog
    }
});
        