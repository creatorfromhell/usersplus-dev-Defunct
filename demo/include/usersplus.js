/**
 * Created by Daniel Vidmar.
 * Date: 8/26/14
 * Time: 5:08 PM
 * Version: Beta 1
 * Last Modified: 8/26/14 at 5:08 PM
 * Last Modified by Daniel Vidmar.
 */
function removeData(div, id) {
    var dataField = document.getElementsByName(div)[0];
    var dataString = dataField.value;
    var data = dataString.split(',');
    var newString = "";
    for(i = 0; i < data.length; i++) {
        if(data[i] != id) { newString += data[i]; }
        if(i < (data.length - 1) && i != 0) { newString += ","; }
    }
    dataField.value = newString;
}

function addData(div, id) {
    var dataField = document.getElementsByName(div)[0];
    var dataString = dataField.value;
    if(dataString != null && dataString != "") { dataString += ","; }
    dataString += id;
    dataField.value = dataString;
}

function onDragOver(event) {
    event.preventDefault();
}

function onDrag(event) {
    event.dataTransfer.setData("dragdata", event.target.id);
}

function onDrop(event, field, remove) {
    event.preventDefault();
    var div = event.dataTransfer.getData("dragdata");
    event.target.appendChild(document.getElementById(div));
    var dataID = div.split('-')[1];
    var targetID = event.target.id;
    if(remove == "remove") {
        removeData(field, dataID);
    } else {
        addData(field, dataID);
    }
}

function switchPage(event, currentPage, nextPage) {

    slideOut(currentPage);
    slideIn(nextPage);

    event.stopPropagation();
    return false;
}

function slideOut(id) {
    var element = document.getElementById(id);
    element.style.top = '-450px';
    element.style.opacity = '0';
    setTimeout(
        function add() {
            element.style.display = 'none';
        }, 200);
}

function slideIn(id) {
    var element = document.getElementById(id);
    element.style.top = '250px';
    setTimeout(
        function add() {
            element.style.display = 'block';
            element.style.opacity = '1';
        }, 200);

    setTimeout(
        function add() {
            element.style.top = '0px';
        }, 215);
}