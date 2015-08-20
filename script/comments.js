/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// The list of highlighted text
var hlData = new Array();

function checkSelection() {
  var selObj = window.getSelection();
  var nodes = new Array();
  nodes[0] = document.getElementById('title');
  nodes[1] = document.getElementById('description');
  nodes[2] = document.getElementById('reflection');
  nodes[3] = document.getElementById('concepts');
  nodes[4] = document.getElementById('referenceList');
  nodes[5] = document.getElementById('whatNext');
  nodes[6] = document.getElementById('notes');
  alert("There are " + selObj.rangeCount + " Selections");
  var idx = 0;
  var startIdx = hlData.length;
  for (var i = 0; i < selObj.rangeCount; i++) {
    var range = selObj.getRangeAt(i);
    var container = findContainer(range, nodes);
    if (container != null) {
      hlData[startIdx + idx] = container;
      // alert("adding container " + idx);
      idx++;
    } else {
      alert("Can't use this one");
    }
  }
  selObj.removeAllRanges();
  //
}

function clearSelections() {
  alert("clearing");
  hlData = new Array();
  var selObj = window.getSelection();
  selObj.removeAllRanges();
}

function rehilight() {
  if (hlData != null) {
    var selObj = window.getSelection();
    selObj.removeAllRanges();
    for (var i = 0; i < hlData.length; i++) {
      container = hlData[i];

      //alert("Selection is " + container.path1 + " offset=" + container.offset1 +
      //  " " + container.path2 + "offset=" + container.offset2);
      var startN = getElementByXpath(container.path1);
      //alert("start node is " + startN.singleNodeValue.nodeName);
      var endN = getElementByXpath(container.path2);
      //alert("end node is " + endN.singleNodeValue.nodeName);
      var range = document.createRange();
      range.setStart(startN.singleNodeValue, container.offset1);
      range.setEnd(endN.singleNodeValue, container.offset2);
      selObj.addRange(range);
    }
  }
}

function getElementByXpath(path) {
  return document.evaluate(path, document.body, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
}

function findContainer(range, nodes) {
  var highlight = {"path1": "", "offset1": range.startOffset, "path2": "", "offset2": range.endOffset};
  if (range != null) {
    var n1 = range.startContainer;
    highlight.path1 = getPathTo(n1);
    var c1 = null;
    while (n1 != null && c1 == null) {
      //alert("checking " + n1.nodeName);
      var c1 = isInCategory(n1, nodes);
      n1 = n1.parentNode;
      //alert("n1 is now " + n1);
    }
    if (c1 != null) {
      //alert("container for the start is " + c1.id);
      var n2 = range.endContainer;
      //alert(getPathTo(n2));
      highlight.path2 = getPathTo(n2);
      var c2 = null;
      while (n2 != null && c2 == null) {
        //alert("checking " + n2.nodeName);
        var c2 = isInCategory(n2, nodes);
        n2 = n2.parentNode;
        //alert("n2 is now " + n2);
      }
      if (c1 === c2) {
        //alert("container for the end is " + c2.id + " to string is " + range.toString());
        return(highlight);
      }
    }
  }
  return null;
}

function getPathTo(element) {
  //alert("path to " + element.nodeName);
  var name = element.nodeName;
  if (name === "#text") {
    name = "text()";
  }
  if (element.id !== '' && typeof element.id !== 'undefined') {
    //alert("returning " + name + " id=" + element.id);
    return '//' + name + '[@id="' + element.id + '"]';
  }
  if (element === document.body) {
    //alert("returning " + name);
    return '//' + name;
  }

  var ix = 0;
  var siblings = element.parentNode.childNodes;
  //alert("There are " + siblings.length + " siblings of parent " + element.parentNode.nodeName);
  for (var i = 0; i < siblings.length; i++) {
    var sibling = siblings[i];
    //alert("sibling type " + sibling.nodeType + " idx=" + i + " is " + sibling.nodeName + " ix=" + ix);
    if (sibling === element) {
      var parentPath = getPathTo(element.parentNode);
      //alert("returning parent " + parentPath + " plus " +'/'+name+'['+(ix+1)+']');
      return parentPath + '/' + name + '[' + (ix + 1) + ']';
    }
    if (/*sibling.nodeType===1 &&*/ sibling.nodeName === element.nodeName) {
      ix++;
    }
  }
}

function isInCategory(n, nodes) {
  for (var i = 0; i < nodes.length; i++) {
    if (n == nodes[i]) {
      return nodes[i];
    }
  }
  return null;
}



