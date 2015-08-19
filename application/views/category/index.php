<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <?php include __VIEW_PATH . '/common/dojoHead.php' ?>
        <title>Testing</title>
    </head>

    <body class="claro">
        <div id="categorySelect"></div><div id="categoryDescribe"></div>

        <script>
            var map = new Array();
            dojo.require("dijit.form.Select");
            dojo.require("dojo.data.ItemFileReadStore");

            dojo.ready(function () {
                // create store instance referencing data from states.json
                var categoryStore = new dojo.data.ItemFileReadStore({
                    url: "/category",
                });
                // create Select widget, populating its options from the store
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
                    dojo.byId('categoryDescribe').innerHTML = map[this.getValue()];
                    //alert(this.getValue() + " " + map[this.getValue()]);
                });
            });
        </script>
    </body>
</html>