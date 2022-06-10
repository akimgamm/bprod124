document.addEventListener("DOMContentLoaded", function(event) {
  console.log("DOM fully loaded and parsed");
  app.run();
  // setTimeout(app.run, 10000);

  // let timerId = setInterval(() => app.run(), 10000);
  //alert(123)
  let stageProj = document.getElementsByClassName('stage' + '1' + '-project' + '209');
  console.log('111111111111111111111111111111111111111111111');
  console.log(stageProj);

const example2Left = document.getElementById("example2-left");
const example2Right = document.getElementById("example2-right");

  new Sortable(example2Left, {
    group: 'shared', // set both lists to same group
    animation: 150
});

new Sortable(example2Right, {
    group: 'shared',
    animation: 150,
    onEnd: function (/**Event*/evt) {
      var itemEl = evt.item;  // dragged HTMLElement
      evt.to;    // target list
      evt.from;  // previous list
      evt.oldIndex;  // element's old index within old parent
      evt.newIndex;  // element's new index within new parent
      evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
      evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
      evt.clone // the clone element
      evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
      alert(21)
    },
});


  // BX24.openApplication(
  //       {
  //          'opened': true,
  //          'bx24_width': 450,// int
  //          'bx24_label': {
  //              'bgColor':'pink', // aqua/green/orange/brown/pink/blue/grey/violet
  //              'text': 'my task',
  //              'color': '#07ff0e',
  //          },
  //          'bx24_title': 'my title', // str
  //          //'bx24_leftBoundary': 300, //int
  //      },
  //      function()
  //      {
  //          console.log('Application closed!')
  //      }
  //  );

});