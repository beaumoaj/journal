/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var wiring = new Array();

function wire($key, $value) {
    wiring[$key] = $value;
}

require(["dijit/form/Select",
    "dojo/data/ObjectStore",
    "dojo/store/JsonRest",
    "dojo/dom",
    "dojo/domReady!"
], function (Select, ObjectStore, JsonRest) {

    var store = new JsonRest({
        target: "/classification/"
    });

    var os = new ObjectStore({objectStore: store});

    var s = new Select({
        store: os
    }, "target");
    s.startup();

    s.on("change", function () {
        var other = wiring[this.get("value")];
        var node = dom.byId(other);
        node.innerHTML = "explanation " + other;
        console.log("my value: ", this.get("value"), "other: ", other);
    });
    
});


