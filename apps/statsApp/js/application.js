// our application constructor
function application() {
}

application.prototype.flatten = function (arr) {
  return arr.reduce(function (flat, toFlatten) {
    return flat.concat(Array.isArray(toFlatten) ? flatten(toFlatten) : toFlatten);
  }, []);
}

application.prototype.collect = function () {
  var ret = {};
  var len = arguments.length;
  for (var i = 0; i < len; i++) {
    for (p in arguments[i]) {
      if (arguments[i].hasOwnProperty(p)) {
        ret[p] = arguments[i][p];
      }
    }
  }
  return ret;
}

application.prototype.getDealCategories = function () { //Получаем направления сделок(для заполнения селекта)
  return new Promise(function (resolve) {

    BX24.callMethod(
      "crm.dealcategory.list",
      {
        order: { "SORT": "ASC" },
        filter: { "IS_LOCKED": "N" },
        select: ["ID", "NAME", "SORT"]
      },
      function (result) {
        if (result.error())
          console.error(result.error());
        else {
          resolve(result.data());
          cats = result.data();
          // console.dir(result.data());			
          if (result.more())
            result.next();
        }
      }
    );

  });
};

application.prototype.getSectionNames = function (sectionID) { //Получаем выборку элементов списка неисправностей раздела, где SectionID -- Id раздела разделов
  let faultsArr = [];
  console.log(sectionID);
  return new Promise(function (resolve) {

    let params = {
      'IBLOCK_TYPE_ID': 'lists',
      'IBLOCK_ID': '74',
      // 'ELEMENT_ID': sectionID
      'FILTER': {
        'PROPERTY_255': sectionID //раздел
      }

    };

    BX24.callMethod(
      'lists.element.get', params, (result) => {
        if (result.error()) {
          alert(`Error:${result.error()}`);
        } else {


          if (result.more()) {
            result.next();
          }


          console.log(result.data());

          for (var key in result.data()) {
            faultsArr.push(result.data()[key]);
          }

          console.log(faultsArr);





          resolve(faultsArr);


          // resolve(result.data());

        }
      }
    );

  });
};

application.prototype.getFaultSections = function () { //Получаем разделы
  return new Promise(function (resolve) {
    params = {
      'IBLOCK_TYPE_ID': 'lists',
      'IBLOCK_ID': '77',
    };

    BX24.callMethod(
      'lists.element.get',
      params,
      function (result) {
        if (result.error())
          alert("Error: " + result.error());
        else
          // console.log(result.data());
          resolve(result.data())
      }
    );
  })
}

application.prototype.fillSelect = function (dataToFill) { //Заполнение селектов


  for (var key in dataToFill.faultObj) {
    var opt = dataToFill.faultObj[key];
    var el = document.createElement("option");
    el.textContent = opt;
    el.value = key;
    faultCategorySelect.appendChild(el);
  }

  for (var key in dataToFill.culpritOptions) {
    var opt = dataToFill.culpritOptions[key];
    var el = document.createElement("option");
    el.textContent = opt;
    el.value = key;
    culpritSelect.appendChild(el);
  }

  for (var key in dataToFill.dealCategoriesOptions) {
    var opt = dataToFill.dealCategoriesOptions[key].NAME;
    var el = document.createElement("option");
    el.textContent = opt;
    el.value = dataToFill.dealCategoriesOptions[key].ID;
    dealCategorySelect.appendChild(el);
  }


  let typeO = Object.keys(dataToFill);
  console.log(dataToFill, typeO);
  for (var key in dataToFill.typesClasses) {
    if (key == 'i') continue;
    let type = dataToFill.typesClasses[key];
    dropClasses.innerHTML += `<div id="faults-type-title" class="faults-type-title">${key}</div>`;
    for (var key1 in type) {
      dropClasses.innerHTML += `<div class="fault-class-wrap"><input value="${type[key1].id}" class="class-check mashineClass" type="checkbox"><label for="">${type[key1].name}</label></div>`;
    }

  }

  let classInputs = document.getElementsByClassName('mashineClass'); //Чекбоксы классов

  classInputs = Array.prototype.slice.call(classInputs);
  console.log(classInputs);

  for (var key in classInputs) {
    classInputs[key].addEventListener('change', () => {
      let checkCount = 0;

      for (var key2 in classInputs) {
        if ($(classInputs[key2]).prop("checked")) {
          $('#dropClasses').css("display", "block");
          $('.faults-type-label').css("display", "none");
          checkCount++;
        }

        if (checkCount == 0) {
          $('#dropClasses').attr('style', "")
          $('.faults-type-label').css("display", "block");

        }
      }

    });
  }

  faultCategorySelect.addEventListener("change", () => {

    $(faultSelect).children('option:not(:first)').remove();

    let selectedFaultCategory = $(faultCategorySelect).val();
    let categoryArr = dataToFill.sectionNamesArr[selectedFaultCategory];

    console.log(categoryArr);


    for (var key in categoryArr) {

      var opt = categoryArr[key].NAME;
      var el = document.createElement("option");
      el.textContent = opt;
      el.value = categoryArr[key].ID;
      faultSelect.appendChild(el);
    }

  });

}

application.prototype.getClasses = function (mainSections) {
  return new Promise(function (resolve) {
    let typesClassesObj = {};

    for (let i = 0; i < mainSections.length; i++) {

      let parentId = mainSections[i].ID;
      let parentName = mainSections[i].NAME;

      typesClassesObj[parentName] = [];
      if (typesClassesObj.i == undefined) {
        typesClassesObj.i = 0;
      }

      params = {
        'IBLOCK_TYPE_ID': 'lists',
        'IBLOCK_ID': '75',
        'FILTER': {
          'SECTION_ID': parentId
        }
      };

      BX24.callMethod(
        'lists.section.get',
        params,
        function (result) {
          if (result.error()) {
            alert("Error: " + result.error());
          }
          else {

            let childCategories = result.data();

            console.log(result.data(), "parentId");
            for (var key in result.data()) {
              let childName = result.data()[key].NAME;
              let childID = result.data()[key].ID;
              // t.innerHTML += childName;
              typesClassesObj[parentName].push({ name: childName, id: childID });

            }

            typesClassesObj.i++;

            if (typesClassesObj.i == mainSections.length) {
              console.log(typesClassesObj, "КЛАССЫ");

              resolve(typesClassesObj);
            }

          }

        }
      );
    }

  });
}

application.prototype.getTypesAndClasses = function () {
  return new Promise(function (resolve) {
    params = {
      'IBLOCK_TYPE_ID': 'lists',
      'IBLOCK_ID': '75',
      'FILTER': {
        'SECTION_ID': "null"
      }
    };

    BX24.callMethod(
      'lists.section.get',
      params,
      function (result) {
        if (result.error())
          alert("Error: " + result.error());
        else
          console.log(result.data(), "34 блок машин");
        resolve(result.data())
      }
    );
  });
}

application.prototype.getSelectData = function (result) { //Вызываем промисы и заполняем селекты

  let sectionNamesArr = {};
  let faultObj = {};

  console.log(result.data());

  let dataToFill = {
    faultCategoriesOptions: result.data().PROPERTY_254.DISPLAY_VALUES_FORM,
    culpritOptions: result.data().PROPERTY_229.DISPLAY_VALUES_FORM
  };

  console.log(dataToFill);

  let faultCategoriesOptions = result.data().PROPERTY_254.DISPLAY_VALUES_FORM;
  let culpritOptions = result.data().PROPERTY_229.DISPLAY_VALUES_FORM;

  let faultCategoriesOptionsKeys = Object.keys(faultCategoriesOptions);
  console.log(faultCategoriesOptionsKeys.shift());
  console.log(faultCategoriesOptionsKeys);

  app.getDealCategories().then(function (dealCategoriesOptions) {


    dataToFill.dealCategoriesOptions = [];

    for (var key in dealCategoriesOptions) {

      switch (dealCategoriesOptions[key].NAME) {
        case 'Гарантийное обслуживание':
        case 'Армия-Сервис':
        case 'ЧЗРМ - Сервис':
        case 'Опции и обкатка':
        case 'admin':
          dataToFill.dealCategoriesOptions.push(dealCategoriesOptions[key]);
      }

    }

    return dataToFill;

  }).then(function (dataToFill) {

    app.getFaultSections().then(function (faultOptions) {

      console.log(faultOptions);

      for (var key in faultOptions) {
        let sectionID = faultOptions[key].ID;
        let faultCategoryKey = faultCategoriesOptionsKeys[key];

        faultObj[sectionID] = faultOptions[key].NAME;
        console.log(faultObj);

        app.getSectionNames(sectionID).then(function (sectionNames) {

          console.log(sectionNames);

          sectionNamesArr[sectionID] = sectionNames;
          console.log(sectionNamesArr);
        })

      }

      dataToFill.sectionNamesArr = sectionNamesArr;
      dataToFill.faultObj = faultObj;

      return dataToFill;

    }).then(function (dataToFill) {

      app.getTypesAndClasses().then(function (mainSections) {

        app.getClasses(mainSections).then(function (typesClassesObj) {

          let D9 = typesClassesObj['Бульдозеры'][3];
          let D10 = typesClassesObj['Бульдозеры'][0];
          let D12 = typesClassesObj['Бульдозеры'][4];
          let D15 = typesClassesObj['Бульдозеры'][2];
          let D20 = typesClassesObj['Бульдозеры'][1];

          let TG35 = typesClassesObj['Трубоукладчики'][3];
          let TG50 = typesClassesObj['Трубоукладчики'][2];

          typesClassesObj['Бульдозеры'][0] = D9;
          typesClassesObj['Бульдозеры'][1] = D10;
          typesClassesObj['Бульдозеры'][2] = D12;
          typesClassesObj['Бульдозеры'][3] = D15;
          typesClassesObj['Бульдозеры'][4] = D20;

          typesClassesObj['Трубоукладчики'][2] = TG35;
          typesClassesObj['Трубоукладчики'][3] = TG50;

          dataToFill.typesClasses = typesClassesObj;
          console.log(dataToFill.typesClasses);

          return dataToFill;

        }).then(function (dataToFill) {
          app.fillSelect(dataToFill); //Заполняем селекты данными
        });
      })
    })
  })
}

application.prototype.resizeFrame = function () {

  let FrameWidth = document.getElementsByTagName("body")['0'].offsetWidth;

  var currentSize = BX24.getScrollSize();
  minHeight = currentSize.scrollHeight;

  if (minHeight < 800) minHeight = 780;
  BX24.resizeWindow(1500, minHeight);
}


application.prototype.clearChart = function () { //Очищаем график перед каждым новым построением
  if (window.myChart instanceof Chart) {
    window.myChart.destroy();
  }

  $("canvas#bar-chart").remove();
  $("div#g-chart").remove();
  // $("div.faults-graph-wrap").append('<canvas id="bar-chart"></canvas>');
  $("div.faults-graph-wrap").append('<div id="g-chart"></div>');
  // var ctx = document.getElementById("bar-chart").getContext("2d");

  // app.resizeFrame();
}

application.prototype.drawChart = function (faultLabels, faultCount, stats) { //Отрисовка графика

  function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

  let colorScheme = [];

  for (let i = 0; i < stats.arr.length; i++) {
    stats.arr[i].push(getRandomColor());
    colorScheme.push(getRandomColor());
  }

  stats.faultLabels.unshift('');
  stats.faultCount.unshift('');
  stats.arr.unshift(["Element", "Неисправностей", { role: "style" }]);

  app.clearChart();

  google.charts.load("current", { packages: ['corechart'] });
  google.charts.setOnLoadCallback(drawGoogleChart);

  let test = stats.arr.join(',');
  console.log(test);

  function drawGoogleChart() {
    var data = google.visualization.arrayToDataTable(
      stats.arr
    );

    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
      {
        calc: "stringify",
        sourceColumn: 1,
        type: "string",
        role: "annotation"
      },
      2]);

    let barWidth = stats.arr.length == 2 ? { groupWidth: "25%" } : { groupWidth: "215%" };

    var options = {
      title: "Количество неисправностей",
      width: 2200,
      height: 600,
      bar: barWidth,
      legend: { position: "none" },
      vAxis: { format: '0' }
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("g-chart"));
    // chart.draw(view, options); //ФУНКЦИЯ ОТРИСОВКИ ГРАФИКА
  }

  localStorage.setItem('faultGraphStats', JSON.stringify(stats));
  console.log(localStorage.getItem('faultGraphStats'));

  window.open('https://dstural24.ru/apps/statsApp/js/redirect.php', '_blank');
  $('.loader').css("display", "none");



}

application.prototype.getParsedCarNumber = function (data) {
  console.log(data)
  return new Promise(function (resolve) {
    let i = 0;
    let parsedCarNumbers = [];
    for (var key in data) {
      var params = {
        'IBLOCK_TYPE_ID': 'lists',
        'IBLOCK_ID': 76,
        'ELEMENT_ID': data[key].PROPERTY_247
      };
      BX24.callMethod(
        'lists.element.get',
        params,
        function (result) {
          if (result.error())
            alert("Error: " + result.error());
          else
            console.log(result.data());
          parsedCarNumbers.push(result.data()[0]);

          i++;
          if (i == data.length) {
            console.log(parsedCarNumbers);
            resolve(parsedCarNumbers);

          }

        }
      );


    }
  })

}

application.prototype.initStats = function () {


  // let dbd = {
  //   'function': "sendBitrix",
  //   'params': {

  //   }

  // }
  // $.getJSON("https://dstinfo.ru/breaks/bitrAPI.php", dbd)
  //   .done(function (json) {
  //     console.log(json, "jjjjjjjjjjjjjj");

  //     json = json.result;

  //     for (var key in json) {
  //       console.log(json[key]);
  //       var params = {
  //             'IBLOCK_TYPE_ID': 'lists',
  //             'IBLOCK_ID': '74',
  //             'ELEMENT_CODE': key+"el_"+json[key].catID,
  //             'FIELDS': {
  //                 'NAME': json[key].name,
  //                 'PROPERTY_255': json[key].catID,
  //               }
  //             }

  //         BX24.callMethod(
  //             'lists.element.add',
  //             params,
  //             function(result)
  //             {
  //                 if(result.error())
  //                     console.log("Error: " + result.error());
  //                 else
  //                   console.log("Success: " + result.data());
  //             }
  //         );


  //     }
  //   })



  var params = {
    'IBLOCK_TYPE_ID': 'lists',
    'IBLOCK_ID': '74',
  };
  BX24.callMethod(
    'lists.field.get',
    params,
    function (result) {
      if (result.error())
        alert("Error: " + result.error());
      else
        console.log(result.data(), "ЗДЕСЬ ЗДЕСЬ");
    }
  );


  params = {
    'IBLOCK_TYPE_ID': 'lists', //Тип инфоблока (группа инфоблоков)
    'IBLOCK_ID': '72', //ID списка со всеми записями о неисправностях
    'FILTER': {
      // 'PROPERTY_227': faultCategoryValue,
      // 'PROPERTY_240': faultValue,
      // 'PROPERTY_229': culpritValue,
      // 'PROPERTY_231': dealCategoryValue,
      // '>=PROPERTY_226': operatingTimeFromValue,
      // '<=PROPERTY_226': operatingTimeToValue,
      // '>=DATE_CREATE': dateFromValue,
      // '<=DATE_CREATE': dateToValue,
    }
  };

  BX24.callMethod(
    'lists.element.get', params, (result) => {
      if (result.error()) {
        alert(`Error:${result.error()}`);
      } else {

        console.log(result.data(), "res");
      }
    }
  );

  var params = {
    'IBLOCK_TYPE_ID': 'lists',
    // 'IBLOCK_CODE': 'rest_1'
  };
  BX24.callMethod(
    'lists.get',
    params,
    function (result) {
      if (result.error())
        alert("Error: " + result.error());
      else
        console.log(result.data());
    }
  );

  var params = {
    'IBLOCK_TYPE_ID': 'lists',
    'IBLOCK_ID': '74'
  };
  BX24.callMethod(
    'lists.field.get',
    params,
    function (result) {
      if (result.error())
        alert("Error: " + result.error());
      else
        console.log(result.data());
    }
  );

  app.resizeFrame();

  faultCategorySelect.addEventListener('change', () => {

    if ($('#faultCategorySelect option:selected').text() == 'Все') {
      $('#faultSelect option:first').text('Все');
    } else {
      $('#faultSelect option:first').text('Выберите неиспр.');
    }

  });

  send.addEventListener("click", () => {
    app.getResult();
  });

  reload.addEventListener('click', () => {

    // nomer.prop('selectedIndex', 0);
    $(faultCategorySelect).prop('selectedIndex', 0);
    $(faultSelect).prop('selectedIndex', 0);
    $(culpritSelect).prop('selectedIndex', 0);
    $(dealCategorySelect).prop('selectedIndex', 0);
    $(dateFrom).value = '';
    $(dateTo).value = '';

    $(faultSelect).children('option:not(:first)').remove();
    $('#faultSelect option:first').text('Все');

    app.clearChart();

  })

  params = {
    'IBLOCK_TYPE_ID': 'lists', //Тип инфоблока (группа инфоблоков)
    'IBLOCK_ID': '72', //ID списка со всеми записями о неисправностях
  };

  BX24.callMethod(
    'lists.field.get',
    params,
    function (result) {
      if (result.error()) {

        alert("Error: " + result.error());

      } else {
        app.getSelectData(result);
      }

    }
  );

}

application.prototype.getCarNumbers = function (key) {
  return new Promise(function (resolve) {
    let carNumbers = [];

    let classInputs = $('.mashineClass:checkbox:checked');
    classInputs = Array.prototype.slice.call(classInputs);

    console.log(classInputs);

    if (classInputs.length != 0) {
      let i = 0;

      for (var key in classInputs) {
        let classID = classInputs[key].value;
        console.log(classID);

        // params = {
        //   'IBLOCK_TYPE_ID': 'lists', //Тип инфоблока (группа инфоблоков)
        //   'IBLOCK_ID': '75', //ID Список машин
        //   'FILTER': {
        //     'SECTION_ID': classID,
        //   }
        // };

        params = {
          'IBLOCK_TYPE_ID': 'lists', //Тип инфоблока (группа инфоблоков)
          'IBLOCK_ID': '76', //ID Список машин
          'FILTER': {
            'PROPERTY_248': classID,
          }
        };

        BX24.callMethod(
          'lists.element.get', params, (result) => { //Вызов метода для получения элементов инфоблока
            if (result.error()) {
              alert(`Error:${result.error()}`);
            } else {


              if (result.more()) {
                result.next();
                // console.log(result.data());

              };

              console.log(result.data());


              let resultData = result.data();

              for (var key in resultData) {
                let carNumber = resultData[key].NAME;
                carNumbers.push(carNumber.slice(carNumber.length - 4));
                // console.log(carNumbers);
              }

              i++;

              if (i == classInputs.length) {
                resolve(carNumbers);
              }
            }
          }
        );

      }
    } else {
      resolve([]);
    }

  });
}

application.prototype.getWithTypesOrNot = function (data, carNumbers) {
  return new Promise(function (resolve) {
    let i = 0;
    let filteredData = [];

    console.log(carNumbers);

    app.getParsedCarNumber(data).then(function (carNum) {

      console.log(carNum); //По айди машин из списка результатов неипсравностей по сделке получили массив элементов машин с названиями из списка машин 


      for (var key in carNum) {
        let carName = carNum[key].NAME;

        if (carNumbers.includes(carName.slice(carName.length - 4))) {
          filteredData.push(data[key]);
          // filteredData.push(carNum);
          console.log(carNum[key].NAME, "ADD");

        }

        i++;

        console.log("i:", i, " dl:", data.length)
        if (i == data.length) {

          if (carNumbers.length != 0) {

            console.log(filteredData, "filteredData");

            resolve(filteredData); //С типами и классами

          } else {
            resolve(data); //Просто данные
          }
        }
      }
    });
  })
}

application.prototype.getDbCats = function (faultValue) {

  let catsArr = [];

  return new Promise(function (resolve) {
    if (faultValue != "") {
      console.log(String(faultValue), faultValue.length);
      faultParams = {
        'IBLOCK_TYPE_ID': 'lists', //Тип инфоблока (группа инфоблоков)
        'IBLOCK_ID': '74', //ID списка со всеми записями о неисправностях
        'ELEMENT_ID': String(faultValue)
        // 'FILTER': {
        //   'PROPERTY_240': faultValue,
        // }
      };


      BX24.callMethod(
        'lists.element.get', faultParams, (result) => {
          if (result.error()) {
            alert(`Error:${result.error()}`);
          } else {


            if (result.more()) {
              result.next();
            } else {
            }

            catsArr.push(result.data());
            console.log(catsArr);

            if (result.data().length == 1) {
              let faultNameArr = result.data();
              for (var key in faultNameArr) {
                faultNameArr[key] = faultNameArr[key].NAME.split('] ').pop();
              }


              console.log(faultNameArr);

              console.log(result.data());
              resolve(faultNameArr);

            } else {
              faultNameArr = [];
              console.log(faultNameArr);
              resolve(faultNameArr);

            }
          }
        }
      );
    } else {
      resolve("");
    }
  });
}

application.prototype.countAndDraw = function (data, json) {
  let parsedValues = [];

  let i = 0;

  for (let key in data) {

    var faultObj = data[key].PROPERTY_240;
    console.log(data[key], key);
    var faultKeys = Object.keys(faultObj); //получаем ключи объекта в виде массива
    let faultId = faultObj[faultKeys[0]];

    var getStats = function () {
      return new Promise(function (resolve) {

        var params = {
          'IBLOCK_TYPE_ID': 'lists',
          'IBLOCK_ID': '74',
          'ELEMENT_ID': faultId //неисправность
        };

        BX24.callMethod(
          'lists.element.get',
          params,

          function (result) {
            if (result.error()) {
              alert("Error: " + result.error());
            }
            else {

              console.log(result.data(), 'zzzzzzz')

              result.data().forEach(el => {
                //console.log(el.NAME.split("] ")[1]);
                let name = el.NAME.split("] ")[1];
                parsedValues[name] = (parsedValues[name] || 0) + 1; //Отслеживанием кол-во вхождений для каждой неисправности
              })

              i++;

              if (i == data.length) {

                console.log(parsedValues, "До");

                if (Object.keys(json.result).length > 0) {
                  for (var key in parsedValues) {
                    for (var keyname in json.result) {
                      if (key == keyname) {
                        parsedValues[key] += +json.result[keyname];
                        console.log(key, "совпало");
                      } else {
                        parsedValues[keyname] = +json.result[keyname];
                      }
                    }
                  }
                }

                console.log(parsedValues, "После");

                let faultCount = Object.values(parsedValues);
           
                console.log(faultCount, 'До');
      

                faultCount.sort((a, b) => b - a);
                let faultLabels = [];

                for(let i = 0; i < faultCount.length; i++) {

                  for(var pKey in parsedValues) {
                    if(parsedValues[pKey] == faultCount[i] && (faultLabels.indexOf( pKey ) == -1)) {

                      faultLabels[i] = pKey;
                      
                    }
                  }
                }

  


                console.log(faultCount, 'После');



                let stats = {
                  faultLabels: faultLabels,
                  faultCount: faultCount,
                  arr: [],
                  resultArr: [],
                };

                console.log(stats, i, data.length);
                for (var key in faultLabels) {
                  stats.arr.push([faultLabels[key], faultCount[key]]);
                }

                for (var key in stats.arr) {
                  stats.resultArr.push(stats.arr[key]);
                }

                // app.drawChart(stats.faultLabels, stats.faultCount, stats);
                resolve(stats);
              }

            }

          }
        );

      })
    }

    getStats().then(function (stats) {

      console.log(stats, "123"); //Заполняем селекты данными
      app.drawChart(stats.faultLabels, stats.faultCount, stats);

    });


  }

}

application.prototype.countAndDrawOnlyJSON = function (json) {
  parsedValues = {};

  for (var keyname in json.result) {
    parsedValues[keyname] = +json.result[keyname];
  }

  console.log(parsedValues, "После");


  let faultCount = Object.values(parsedValues);

  faultCount.sort((a, b) => b - a);
  let faultLabels = [];

  for(let i = 0; i < faultCount.length; i++) {

    for(var pKey in parsedValues) {
      if(parsedValues[pKey] == faultCount[i] && (faultLabels.indexOf( pKey ) == -1)) {
        faultLabels[i] = pKey;
      }
    }
  }


  let stats = {
    faultLabels: faultLabels,
    faultCount: faultCount,
    arr: [],
    resultArr: [],
  };

  for (var key in faultLabels) {
    stats.arr.push([faultLabels[key], faultCount[key]]);
  }

  for (var key in stats.arr) {
    stats.resultArr.push(stats.arr[key]);
  }

  // console.log(stats, "stats stats stats", result.data());
  console.log(parsedValues)
  app.drawChart(stats.faultLabels, stats.faultCount, stats);
}

application.prototype.getResult = function () {

  $('.loader').css("display", "block");

  app.clearChart();

  function getParsedData(data, json) {

    app.getCarNumbers().then(function (carNumbers) {

      console.log(carNumbers); //ТУТ номера машин

      if (carNumbers.length > 0 && data.length > 0 && Object.keys(json.result).length > 0) { //если фильтруем по типу и есть данные в обоих базах

        app.getWithTypesOrNot(data, carNumbers).then(function (filteredData) { //только для списка

          console.log(filteredData, data.length); //отфильтрованные элементы списка (фильтрация по типу)

          if (filteredData.length > 0) {
            app.countAndDraw(filteredData, json);
          } else {
            app.countAndDrawOnlyJSON(json);
          }

          console.log(json, "тут сделки");


        });

      } else if (data.length == 0 && Object.keys(json.result).length == 0) { //Если нет данных по выборке фильтра

        app.clearChart();
        alert("Результаты по запросу не найдены");

      } else if (data.length == 0 && Object.keys(json.result).length > 0) { //Если есть только по серверной базе
        // getParsedData(result.data(), json);
        console.log("Только json без списка");
        app.countAndDrawOnlyJSON(json);
      } else {
        console.log("else", data, json)
        app.countAndDraw(data, json)
      }//if

    })
  } //getParsedData

  let nomerValue = null;
  let faultCategoryValue = document.getElementById('faultCategorySelect').value;
  let faultCategoryValueText = $("#faultCategorySelect option:selected").text();;
  // console.log(faultCategoryValueText);

  let faultValue = document.getElementById('faultSelect').value;
  let culpritValue = document.getElementById('culpritSelect').value;
  let culpritValueText = $("#culpritSelect option:selected").text();

  let dealCategoryValue = document.getElementById('dealCategorySelect').value;
  let dealCategoryValueText = $("#dealCategorySelect option:selected").text();

  let dateFromValue = document.getElementById('dateFrom').value;
  let dateToValue = document.getElementById('dateTo').value;
  let operatingTimeFromValue = document.getElementById('operatingTimeFrom').value;
  let operatingTimeToValue = document.getElementById('operatingTimeTo').value;

  if (dateToValue.length != 0) {
    let dateFromArr = dateToValue.split('-');
    let dateToArr = dateToValue.split('-');

    console.log(dateToArr);

    dateToArr[2] = +dateToArr[2] + 1;

    dateToValue = dateToArr.join("-")
  }

  let dbParams;
  let classInputsArr = [];

  app.getDbCats(faultValue).then(function (faultNameArr) {

    var classInputsQuery = document.getElementsByClassName('mashineClass');

    if (classInputsQuery.length != 0) {
      for (var i = 0; i < classInputsQuery.length; ++i) {
        if (classInputsQuery[i].checked) {
          classInputsArr.push(classInputsQuery[i].value);
        }
      }
    }

    if (classInputsArr.length == 0) {
      classInputsArr = "";
    }

    dbParams = { //Объект для запроса к базе
      'function': "getDBMalfunctions",
      'params': {
        'forbidden': "false",
        'faultCategory': faultCategoryValueText,
        'culprit': culpritValueText,
        'dealCategory': dealCategoryValueText,
        'worktimeFrom': operatingTimeFromValue,
        'worktimeTo': operatingTimeToValue,
        'dateFrom': dateFromValue,
        'dateTo': dateToValue,
        'faultName': faultNameArr,
        'typeClass': classInputsArr,
      }
    }


    if (faultCategoryValueText == "Все" && culpritValueText == "Все" && dealCategoryValueText == "Все" && operatingTimeFromValue == "" && operatingTimeToValue == "" && dateFromValue == "" && dateToValue == "" && faultNameArr == "" && classInputsArr == "") {
      dbParams['params']['forbidden'] = "true";

    } else {
      dbParams['params']['forbidden'] = "false";

    }


    // console.log(Object.keys(dbParams['params']).length);

    // let count = 0;
    // for (var key in dbParams['params']) {
    //   if (dbParams['params'][key] == "") {
    //     count++;
    //   }

    //   if (count == Object.keys(dbParams['params']).length-1) {
    //     dbParams['params']['forbidden'] = "true";

    //   }
    // }

    return dbParams;

  }).then(function (dbParams) {

    console.log(dbParams);

    $.getJSON("https://dstinfo.ru/breaks/bitrAPI.php", dbParams)
      .done(function (json) {
        console.log("JSON Data: ", json);
        params = {
          'IBLOCK_TYPE_ID': 'lists', //Тип инфоблока (группа инфоблоков)
          'IBLOCK_ID': '72', //ID списка со всеми записями о неисправностях
          'FILTER': {
            // 'PROPERTY_254': 9422,
            'PROPERTY_254': faultCategoryValue,
            'PROPERTY_240': faultValue,
            'PROPERTY_229': culpritValue,
            'PROPERTY_231': dealCategoryValue,
            '>=PROPERTY_226': operatingTimeFromValue,
            '<=PROPERTY_226': operatingTimeToValue,
            '>=DATE_CREATE': dateFromValue,
            '<=DATE_CREATE': dateToValue,
          }
        };



        console.log(params);

        BX24.callMethod(
          'lists.element.get', params, (result) => {
            if (result.error()) {
              alert(`Error:${result.error()}`);
            } else {

              console.log(result.data(), params, result.data().length > 0 && Object.keys(json.result).length > 0);

              getParsedData(result.data(), json);


            }
          }
        );
      })



  }) //передаю id конкретной неисправности 3696 -- жгут
}

// create our application
app = new application();