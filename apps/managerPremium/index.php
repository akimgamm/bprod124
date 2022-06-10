<?

require_once (__DIR__.'/crest.php');

//put an example below

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");

$rsUser = CUser::GetByID(830);
$arUser = $rsUser->Fetch();
echo "<pre>"; print_r($arUser); echo "</pre>";

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>


<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>



 BX.rest.callMethod(
'user.get', { 
        "ID":"830"
  }
,function(result) {
        if (result.error())
          console.error(result.error());
        else
          console.dir(result.data());
      });

	  //console.log(test);


    function application() {}
    app = new application();

    // BX.rest.callMethod(
    //   "crm.deal.list", 
    //   { 
    //       // order: { "STAGE_ID": "DESC" },
    //       filter: { 
    //         "CATEGORY_ID": 12,
    //         // "STAGE_ID": 
    //      },
    //       // select: [ "ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID" ]
    //   }, 
    //   function(result) 
    //   {
    //       if(result.error())
    //           console.error(result.error());
    //       else
    //       {
    //           console.dir(result.data()); 			
    //           if(result.more())
    //               result.next();						
    //       }
    //   }
    // );

    BX.rest.callMethod(
      "crm.dealcategory.stage.list", {
        id: 12
      },
      function(result) {
        if (result.error())
          console.error(result.error());
        else
          console.dir(result.data());
      }
    );

    //UF_CRM_1632459978



    application.prototype.getAllDeals = function(mainCategories) {
      return new Promise(function(resolve) {

        let dealsQuery = [];

        for (let i = 0; i < mainCategories.length; i++) {

          BX.rest.callMethod(
            "crm.deal.list", {
              order: {
                // "STAGE_ID": "ASC"
              },
              filter: {
                "STAGE_ID": "C" + mainCategories[i] + ":WON",
                "CATEGORY_ID": mainCategories[i],
              },
              // select: [ "ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID" ] 
            },
            function(result) {
              if (result.error())
                console.error(result.error());
              else {
                if (result.more()) {
                  result.next();
                  dealsQuery.push(result.data());

                } else {
                  dealsQuery.push(result.data());
                  // console.log(deals,i,mainCategories.length);
                  let deals = [];

                  for (var arr in dealsQuery) {
                    let dealsQueryArrEl = dealsQuery[arr];
                    for (var key in dealsQueryArrEl) {
                      deals.push(dealsQueryArrEl[key]);
                    }
                  }

                  console.log(deals);
                  resolve(deals);
                }
                // deals = result.data();

              }
            }
          );

        }

      });
    }

    application.prototype.directDeal = function(data) {
      return new Promise(function(resolve) {

        let opportunity = data.OPPORTUNITY; //Сумма контракта
        let retailPrice = data.UF_CRM_1632475128; //розничная цена
        let premium; //Премия
        let managerPremium;
        let departmentFund;

        console.log(data.OPPORTUNITY);
        if(opportunity>retailPrice) {
          premium = opportunity*0.01;
          departmentFund = premium*0.5;
          managerPremium = premium*0.5;
          alert(managerPremium);
        }
        
      });
    }

    application.prototype.chooseDealType = function(data) {
      return new Promise(function(resolve) {

        console.log(data);
        switch (data.UF_CRM_1632459978) {
          case "84":
            //Прямая сделка
            app.directDeal(data);

            break;
          case "85":
            //Общая сделка
            app.customDeal(data);

            break;
          case "86":
            app.investDeal(data);
            break;

            //Инвест сделка
        }



      });
    }

    application.prototype.getManager = function(data) {
      return new Promise(function(resolve) {
        let managerID = data.ASSIGNED_BY_ID;
        console.log(managerID);

        // BX24.callMethod('user.get', {"ID": 830});
        // BX24.callMethod('user.get', params );
        // BX24.callMethod('user.get', {"ID": 1});


      })
    
    }

    application.prototype.getDeal = function(deals) {
      return new Promise(function(resolve) {

        let dealsQuery = [];

        for (let i = 0; i < deals.length; i++) {
          let dealID = deals[i].ID;
          console.log(dealID);

          BX.rest.callMethod(
            "crm.deal.get", {
              id: dealID
            },
            function(result) {
              if (result.error())
                console.error(result.error());
              else {
                app.getManager(result.data());
                app.chooseDealType(result.data());
              }
            }
          );




        }

      });
    }


    let mainCategories = [17];
    app.getAllDeals(mainCategories).then(function(deals) {

      app.getDeal(deals);

    });
  </script>
</body>

</html>