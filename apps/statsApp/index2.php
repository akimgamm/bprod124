<?

require_once(__DIR__ . '/crest.php');
class CRestCurrent extends CRest
{
   protected static $dataExt = [];
   protected static function getSettingData()
   {
      $return = static::expandData(file_get_contents(__DIR__ . '/settings.json'));
      if(is_array($return))
      {
         if(!empty(static::$dataExt))
         {
            $return['access_token'] = htmlspecialchars(static::$dataExt['AUTH_ID']);
            $return['domain'] = htmlspecialchars(static::$dataExt['DOMAIN']);
            $return['refresh_token'] = htmlspecialchars(static::$dataExt['REFRESH_ID']);
            $return['application_token'] = htmlspecialchars(static::$dataExt['APP_SID']);
         }
         else
         {
            $return['access_token'] = htmlspecialchars($_REQUEST['AUTH_ID']);
            $return['domain'] = htmlspecialchars($_REQUEST['DOMAIN']);
            $return['refresh_token'] = htmlspecialchars($_REQUEST['REFRESH_ID']);
            $return['application_token'] = htmlspecialchars($_REQUEST['APP_SID']);
         }

      }

      return $return;
   }

   public static function setDataExt($data)
   {
      static::$dataExt = $data;
   }
}



$result = CRestCurrent::call('user.current');


?>


﻿<!doctype html>
<html lang="ru">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Рейтинг (пример 2)</title>

  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <!-- <div id="app" class="container-fluid">
    <div class="alert alert-success" role="alert" id="user-name"><i class="fa fa-spinner fa-spin"></i></div>
  </div> -->

  <style>
    .loader {
      display: none;
    }
    .lds-grid {
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
      vertical-align: middle;
    }

    .lds-grid div {
      position: absolute;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: #52bfff;
      animation: lds-grid 1.2s linear infinite;
    }

    .lds-grid div:nth-child(1) {
      top: 8px;
      left: 8px;
      animation-delay: 0s;
    }

    .lds-grid div:nth-child(2) {
      top: 8px;
      left: 32px;
      animation-delay: -0.4s;
    }

    .lds-grid div:nth-child(3) {
      top: 8px;
      left: 56px;
      animation-delay: -0.8s;
    }

    .lds-grid div:nth-child(4) {
      top: 32px;
      left: 8px;
      animation-delay: -0.4s;
    }

    .lds-grid div:nth-child(5) {
      top: 32px;
      left: 32px;
      animation-delay: -0.8s;
    }

    .lds-grid div:nth-child(6) {
      top: 32px;
      left: 56px;
      animation-delay: -1.2s;
    }

    .lds-grid div:nth-child(7) {
      top: 56px;
      left: 8px;
      animation-delay: -0.8s;
    }

    .lds-grid div:nth-child(8) {
      top: 56px;
      left: 32px;
      animation-delay: -1.2s;
    }

    .lds-grid div:nth-child(9) {
      top: 56px;
      left: 56px;
      animation-delay: -1.6s;
    }

    @keyframes lds-grid {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
  <script type="text/javascript" src="js/application.js"></script>
  <script src="//api.bitrix24.com/api/v1/"></script>

  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

  <!-- <a
  data-fancybox="gallery"
  href="https://lipsum.app/id/61/1600x1200"
  data-caption="Second image"
></a> -->

  <div class="row">
    <!-- <label class="label" for="nomer">Номер машины</label> -->
    <!-- <input class="classical-input" type="number" id="nomer"> -->

    <label class="label" for="faultCategotySelect">Категория неисправности</label>
    <select class="classical-select" name="" id="faultCategorySelect">
      <option value="">Все</option>
    </select>

    <label class="label label-n" for="faultSelect">Неисправность</label>
    <select class="classical-select" name="" id="faultSelect">
      <option value="">Все</option>
    </select>


    <label class="label label-n2" for="culpritSelect">Виновник</label>
    <select class="classical-select" name="" id="culpritSelect">
      <option value="">Все</option>
    </select>

    <div class="types">
      <label id="choose-type" class="choose-type label" for="">Тип:</label>

      <div class="classical-dropdown faults-types-wrap">

        <div class="faults-type-label">Все</div>

        <div id="dropClasses" class="classical-dropdown-content">


          <!-- <div class="faults-type">

            <div id="faults-type-title" class="faults-type-title">
              Бульдозеры
            </div>

            <div id="faults-type-checkboxes" class="faults-type-checkboxes">
              <input value="16" class="class-check mashineClass" type="checkbox"><label for="">9 класс</label>
              <input value="18" class="class-check mashineClass" type="checkbox"><label for="">10 класс</label>
            </div>

          </div> -->


        </div>

        </form>
      </div>
    </div>



  </div>



  <div class="row">

    <label class="label" for="dealCategorySelect">Категория сделки</label>
    <select class="classical-select" name="" id="dealCategorySelect">
      <option value="">Все</option>
    </select>

    <div class="faults-date-label ">
      <label class="label label-n" for="dateFrom">Выбрать дату от</label>
    </div>
    <div class="faults-date-input">
      <input value="" class="date-input" type="date" name="dateFrom" id="dateFrom">
    </div>

    <div class="faults-date-label faults-date-label__2">
      <label class="label label-n2" for="dateTo">До</label>
    </div>
    <div class="faults-date-input">
      <input value="" class="date-input" type="date" name="dateTo" id="dateTo">
    </div>

    <div class="filterblock-button-wrap">
      <!-- <a id="reload" class="faults-a classical-a" href="https://akimdst.bitrix24.ru/marketplace/app/4/">Сбросить</a> -->

      <!-- <a class="faults-a classical-a classical-a-xls" href="/sdm/faults/?xls&amp;">Скачать Excel</a> -->
    </div>
  </div>

  <div class="row">
    <button class="btn" id="send" class="b">Найти</button>
    <button class="btn" id="reload" class="b">Сбросить</button>

    <!-- <div id="t" class="t">
  
    </div> -->

    <div class="operating-time-wrap">
      <div class="faults-operating-time-label ">
        <label class="label label-n" for="dateFrom">Наработка от</label>
      </div>
      <div class="faults-operating-time-input">
        <input value="" class="classical-input" type="number" name="operatingTimeFrom" id="operatingTimeFrom">
      </div>

      <div class="faults-operating-time-label faults-operating-time-label__2">
        <label class="label label-n2" for="dateTo">До</label>
      </div>
      <div class="faults-operating-time-input">
        <input value="" class="classical-input" type="number" name="operatingTimeTo" id="operatingTimeTo">
      </div>


      <!-- <button class="btn" id="send" class="b">Найти</button>
      <button class="btn" id="reload" class="b">Сбросить</button> -->
    </div>



  </div>

  <div class="row">

  </div>


  <div class="loader">

    <div class="lds-grid">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>

    <div style="display: inline-block; vertical-align: middle;" class="loader-titles">
      <div class="row">Загрузка графика.</div>
      <div class="row">
        График откроется в новой вкладке
      </div>
    </div>
  </div>




  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.js"></script> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <div style="position: relative;
  left: -86px;" class="faults-graph-wrap">
    <div id="g-chart"></div>
    <!-- <canvas id="bar-chart"></canvas> -->
  </div>

  <script type="text/javascript">

  </script>


  <script>

    $(document).ready(function () {


      BX24.init(function () {

        app.initStats();

      });

    });

  </script>

</body>

</html>