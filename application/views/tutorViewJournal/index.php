<?php
/*
  Document   : home
  Created on : 12-Sep-2011, 22:52:57
  Author     : David Bennett
 */
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <?php include __VIEW_PATH . '/common/head.php' ?>
        <?php include __VIEW_PATH . '/common/dojoHead.php' ?>
        <script type="text/javascript">
            var wiring = new Array();
            function wire($key, $value) {
                wiring[$key] = $value;
            }

            require(["dijit/form/Select",
                "dojo/data/ObjectStore",
                //"dojo/data/ItemFileWriteStore", 
                //"dojo/store/DataStore",
                "dojo/store/JsonRest",
                "dojo/store/Memory",
                "dojo/store/Cache",
                "dojo/dom",
                "dojo/domReady!"
            ], function (Select, ObjectStore, JsonRest, Memory, Cache, dom) {
                //var datastore = new ItemFileWriteStore({url:"/tutorViewJournal/getClassifications"});
                //var store = new DataStore({store: datastore});
                var rstore = new JsonRest({
                    target: "/tutorViewJournal/getClassifications"
                });
                //store.get();
                var memoryStore = new Memory();
                var store = new Cache(rstore, memoryStore);
                // Get an object by identity ERROR HERE
                //store.get(id).then(function (item) {
                // item will be the DB item
                //});

                //var store = new Memory({data: [
                //        {id: "1", label: "Pre-structural", text: "here students are simply acquiring bits of unconnected information, which have no organisation and make no sense."},
                //        {id: "2", label: "Unistructural", text: "simple and obvious connections are made, but their significance is not grasped"},
                //        {id: "3", label: "Multistructural", text: "a number of connections may be made, but the meta-connections between them are missed, as is their significance for the whole."},
                //        {id: "4", label: "Relational", text: "the student is now able to appreciate the significance of the parts in relation to the whole."},
                //      {id: "5", label: "Extended abstract", text: "the student is making connections not only within the given subject area, but also beyond it, able to generalise and transfer the principles and ideas underlying the specific instance."
                //        }
                //    ]
                //});

                var os = new ObjectStore({objectStore: store});
                var s = new Select({
                    store: os
                }, "starget");
                s.startup();
                //var props = "";
                //for (prop in restStore.query({id:"0"})) {
                //    props = props + ", " + prop + "=" + store[prop];
                //}
                //alert("Store is " + restStore);

                s.on("change", function () {
                    //var other = wiring[this.get("value")];
                    //var node = dom.byId(other);
                    //node.innerHTML = "explanation " + other;
                    console.log("my value: ", this.get("value"));//, " label ", store.query({id: this.get("value")})[0].text);
                });
            });
            // code to hand data and events
        </script>
    </head>

    <body class="claro">

        <?php include __VIEW_PATH . '/common/header.php' ?>

        <div id="side_bar">
            <?php echo $model->sidebarContent; ?>
        </div>

        <div id="main_content">
            <?php
            $breadcrumbs->draw();
            ?>

            <h1><?php echo $model->title; ?></h1>

            <?php
            if (!isset($_SESSION['username'])) {
                ?>
                <p>Please login first.</p>
                <?php
            } else {
                echo '<p>' . $this->model->content . '</p>';
            }
            ?>


        </div>

        <?php include __VIEW_PATH . '/common/footer.php' ?>

    </body>

</html>