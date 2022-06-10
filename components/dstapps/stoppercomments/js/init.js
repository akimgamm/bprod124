document.addEventListener("DOMContentLoaded", function (event) {



  console.log("DOM fully loaded and parsed");

  //Подсказки
  
  let tooltipElem;

  document.onmouseover = function (event) {
    let target = event.target;

    // если у нас есть подсказка...
    let tooltipHtml = target.dataset.tooltip;
    if (!tooltipHtml) return;

    // ...создадим элемент для подсказки

    tooltipElem = document.createElement('div');
    tooltipElem.className = 'tooltip';
    tooltipElem.innerHTML = tooltipHtml;
    document.body.append(tooltipElem);

    // спозиционируем его сверху от аннотируемого элемента (top-center)
    let coords = target.getBoundingClientRect();

    let left = coords.left + (target.offsetWidth - tooltipElem.offsetWidth) / 2;
    if (left < 0) left = 0; // не заезжать за левый край окна

    let top = coords.top - tooltipElem.offsetHeight - 5;
    top += 50;
    if (top < 0) { // если подсказка не помещается сверху, то отображать её снизу
      top = coords.top + target.offsetHeight + 5;
      top -= 50;
    }

    tooltipElem.style.left = left + 'px';
    tooltipElem.style.top = top + 'px';
  };

  document.onmouseout = function (e) {

    if (tooltipElem) {
      tooltipElem.remove();
      tooltipElem = null;
    }

  };

//код исполняем, только когда DOM загружен 

// BX.SidePanel.Instance.open("https://dstural124.ru/workgroups/group/210/tasks/task/view/81139/");

// console.log(BX.SidePanel.Instance.isOpen());








  Elements.redesignBitrixDOM();
  Application.run();

  // new Sortable(example2Right, {
  //   group: 'shared',
  //   animation: 150,
  //   onEnd: function (/**Event*/evt) {
  //     var itemEl = evt.item;  // dragged HTMLElement
  //     evt.to;    // target list
  //     evt.from;  // previous list
  //     evt.oldIndex;  // element's old index within old parent
  //     evt.newIndex;  // element's new index within new parent
  //     evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
  //     evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
  //     evt.clone // the clone element
  //     evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
  //     alert(21)
  //   },
  // });



});