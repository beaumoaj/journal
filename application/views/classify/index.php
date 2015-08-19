<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <?php include __VIEW_PATH . '/common/head.php' ?>
        <?php include __VIEW_PATH . '/common/dojoHead.php' ?>
        <script>
            //var map = new Array();
            dojo.require("dijit.form.Select");
            dojo.require("dojo.store.JsonRest");
            dojo.require("dojo.data.ItemFileReadStore");
            dojo.require("dijit.form.Textarea");
            dojo.require("dijit.Editor");
            dojo.require("dijit._editor.plugins.AlwaysShowToolbar");
            dojo.ready(function () {
                //require(["dijit.form.Select",
                //    "dojo/_base/lang",
                //    "dojo.store.JsonRest",
                //    "dojo.data.ItemFileReadStore",
                //    "dijit/form/Textarea", "dojo/domReady!"], function (Select, lang, JsonRest, ItemFileReadStore, Textarea) {
                // create store instance referencing data from states.json

                var store = new dojo.store.JsonRest({
                    target: "/classify/entries/"
                });
                var commentstore = new dojo.store.JsonRest({
                    target: "/classify/comments/"
                });
                var categoryStore = new dojo.data.ItemFileReadStore({
                    url: "/category",
                });

                var currentList;
                var currentIndex;
                var cStudent = null;
                var viewedStudent = null;
                var previousStudent = null;
                var cJournalEntry;
                var cJournal;
                /*
                 store.get(15).then(function (object) {
                 // use the object with the identity of 15
                 alert(object[0].title);
                 });
                 */
                /*
                 var textarea = new dijit.form.Textarea({
                 name: "comment",
                 //value: "Comment",
                 style: "width:100%;"
                 }, "comment");
                 //alert("Textarea is " + textarea);
                 */
                // Make our editor
                var myEditor = new dijit.Editor({
                    height: '100px',
                    extraPlugins: [dijit._editor.plugins.AlwaysShowToolbar]
                }, dojo.byId('comment'));
                myEditor.startup();
                //alert(dijit.byId('comment'));
                myEditor.watch('value', function () {
                    value = myEditor.get('value');
                    //alert("new comment " + value);
                    if (/* value != "" && */ cStudent != null && currentList.length > 0) {
                        //alert("sending to commentstore");
                        commentstore.put({
                            comment: value,
                            entry: cJournalEntry
                        });//,{overwrite: true});
                    } else {
                        if (value != "") {
                            alert("You need to be viewing a journal entry to comment on it!");
                            myEditor.set("value", "");
                            //textarea.set("value", "");
                        } else {
                            //alert("I am not going to save 0");
                        }

                    }
                });



                getEntries = function (journal, student) {
                    cJournal = journal;
                    previousStudent = viewedStudent;
                    viewedStudent = student;
                    cStudent = student;
                    if (previousStudent != null) {
                        var pStudentElem = document.getElementById('cell' + previousStudent);
                        pStudentElem.className = 'editable';
                    }
                    if (viewedStudent != null) {
                        var vStudentElem = document.getElementById('cell' + viewedStudent);
                        vStudentElem.className = 'selected';
                    }
                    currentList = new Array();
                    var query = "?journal=" + journal + "&author=" + student;
                    store.query(query).then(function (results) {
                        elem = document.getElementById('journal_entry');
                        if (results.length > 0) {
                            // use the query results returned from the server
                            //message = "<button id='prev' onClick='goPrev();'>Previous</button>";
                            message = '';
                            //message += "<button id='next' onClick='goNext();'>Next</button>";
                            message += '<h1>Entries for ' + student + '</h1>';
                            message += "<div id='entry'></div>";
                            for (obj in results) {
                                currentList.push(results[obj]);
                                //message += results[obj].title + "<br/>";
                            }
                            currentIndex = 0;
                            elem.innerHTML = message;

                            display(currentList[0]);
                        } else {
                            select.set("value", "");
                            display(null);
                            elem.innerHTML = "<h1>Entries for " + viewedStudent + "</h1><p>This student has no journal entries</p>";
                        }
                    });
                }

                var map = new Array();
                var select = new dijit.form.Select({
                    name: "categorySelect",
                    //placeHolder: "Please select a category",
                    store: categoryStore,
                    sortByLabel: false,
                    maxHeight: -1 // tells _HasDropDown to fit menu within viewport
                }, "categorySelect");

                select.store.fetch({
                    query: {id: "*"},
                    onItem: function (item, request) {
                        map[select.store.getValue(item, "id")] =
                                select.store.getValue(item, "description");
                    }
                });

                select.startup();

                select.on("change", function () {
                    //alert("value is now [" + this.get("value") + "]");
                    dojo.byId('categoryDescribe').innerHTML = map[this.get("value")];
                    if (/* this.get("value") != "" && */ cStudent != null && currentList.length > 0) {
                        //alert("storing value");
                        store.put({
                            classification: this.get("value"),
                            entry: cJournalEntry
                        });//,{overwrite: true});
                        var img = document.getElementById('img' + cStudent);
                        img.src = '/classify/count?journal=' + cJournal + '&student=' + cStudent + "&" + Math.random();
                    } else {
                        //alert("NOT storing value");
                        if (this.get("value") != "") {
                            alert("You need to be viewing a journal entry");
                            select.set("value", "");
                        } else {
                            //alert("I am not going to save 0");
                        }

                    }
                });


                goPrev = function () {
                    if (currentIndex > 0) {
                        currentIndex--;
                        display(currentList[currentIndex]);
                    }

                }
                goNext = function () {
                    if (currentIndex < currentList.length - 1) {
                        currentIndex++;
                        display(currentList[currentIndex]);
                    }

                }

                function display(entry) {
                    //alert(entry.title);
                    countElem = document.getElementById('countEntry');
                    if (currentList.length > 0) {
                        countElem.innerHTML = cStudent + ":[id:"+
			entry.id + "] Entry " + (currentIndex + 1) + " of " + currentList.length;
                    } else {
                        countElem.innerHTML = "No Entries";
                    }
                    if (entry) {
                        elem = document.getElementById('entry');

                        cStudent = entry.author;
                        cJournalEntry = entry.id;
                        store.get(cJournalEntry).then(function (object) {
                            // use the object with the identity of 15
                            if (object.length > 0) {
                                //alert("classification " + object[0].classification);
                                //dijit.byId( 'my_select' ).attr( 'value', String( object[0].classification ) );
                                select.set("value", String(object[0].classification));
                                //textarea.set("value", String(object[0].comment));
                            } else {
                                //alert("No classification yet.");
                                select.set("value", "");
                                //textarea.set("value", "");
                            }
                        });
                        commentstore.get(cJournalEntry).then(function (object) {
                            // use the object with the identity of 15
                            var editor = dijit.byId('comment');
                            //alert(editor);
                            if (object.length > 0) {

                                //alert("classification " + object[0].classification);
                                //dijit.byId( 'my_select' ).attr( 'value', String( object[0].classification ) );
                                editor.set("value", String(object[0].comment));
                            } else {
                                //alert("No classification yet.");
                                editor.set("value", "");
                            }
                        });

                        message = '<b>Title:</b> <br/>' +
                                '<div id="title" class="viewable">' + entry.title + '</div>' +
                                '<b>Description:</b> <br/>' +
                                '<div id="description" class="viewable">' + entry.description + '</div>' +
                                '<b>Reflection:</b><br/>' +
                                '<div id="reflection" class="viewable">' + entry.reflection + '</div>' +
                                '<b>Concepts:</b> <br/>' +
                                '<div id="concepts" class="viewable">' + entry.concepts + '</div>' +
                                '<b>What Next:</b> <br/>' +
                                '<div id="whatNext" class="viewable">' + entry.whatNext + '</div>' +
                                '<b>References:</b> <br/>' +
                                '<div id="referenceList" class="viewable">' + entry.referenceList + '</div>' +
                                '<b>Notes:</b> <br/>' +
                                '<div id="notes" class="viewable">' + entry.notes + '</div>';
                        elem.innerHTML = message;
                    } else {
                        cStudent = null;
                        cJournalEntry = -1;
                        //elem.innerHTML = "<h1>Entries for " + viewedStudent + "</h1><p>This student has no journal entries</p>";

                        //elem.innerHTML = '<p>No entries for this student</p>';
                    }
                }

                //store.put({foo: "bar"}, {id: 13}); // store the object with the given identity

                //store.remove(3); // delete the object
            });
        </script>
        <title>View Journals</title>
    </head>

    <body class="claro">

        <?php include __VIEW_PATH . '/common/header.php' ?>

        <div id="side_bar">
            <?php echo $model->sidebarContent; ?>
        </div>
        <div id="main_content">
            <div class="classify_panel">
                <table>
                    <tr><th class="category">Category</th><th class="category">Description</th></tr>
                    <tr>
                        <td class="category"><div id="categorySelect"></div></td>
                        <td class="category"><div id="categoryDescribe"></div></td></tr>
                    <tr><td class="category"><button id='prev' onClick='goPrev();'>Previous</button><button id='next' onClick='goNext();'>Next</button></td>
                        <td class="category"><div style="width:700px;min-height:100px;" id="comment"></div>
                            <!--div data-dojo-type="dijit.Editor" id="comment" data-dojo-props="onChange:function(){updateEditor(arguments[0])}">
      <p>This instance is created from a div directly with default toolbar and plugins</p --></td></tr>
                </table>
                <div id="countEntry"></div>
            </div>
            <div id="journal_entry"></div>   
        </div>


    </body>
</html>
