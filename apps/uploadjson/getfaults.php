<?


$queryUrl = "https://dstinfo.ru/breaks/bitr.php";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl,
  CURLOPT_POSTFIELDS => [],
));

$result = curl_exec($curl);
curl_close($curl);
// print_r($result);

$result = json_decode($result, 1);




echo "125";
?>

<!-- <div id="table1">
  //Let jQuery AJAX Change This Text
</div>
<button id='getRecords'>Get Records</button> -->


<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="//api.bitrix24.com/api/v1/"></script>

<script>
  BX24.init(function() {

  });



  // $(document).ready(function() {
  //       $('#getRecords').click(function() {
  //         var response = '';
  //         $.ajax({
  //           type: 'POST',
  //           url: "Records.php",
  //           async: false,
  //           success: function(text) {
  //             $('#table1').html(text);
  //           }
  //         });
  //       });

  //     });

  //     var arr = <?php // echo $result; 
                    ?>;

  //     console.log(arr);



  //     var id = prompt("Введите ID"); BX24.callMethod(
  //       "crm.deal.get", {
  //         id: id
  //       },
  //       function(result) {
  //         if (result.error())
  //           console.error(result.error());
  //         else
  //           console.dir(result.data());
  //       }
  //     );

  var arr = <?php echo $result; ?>;


  console.log(arr);

  // let arr = ['1n','2n','3n','4n','5n','6n','7n','8n','9n','10n','11n','12n','13n','14n'];
  // var params = {
  //   'IBLOCK_TYPE_ID': 'lists',
  //   'IBLOCK_ID': '75',
  //   'FILTER': {
    
  //   },
 
  // };
  // BX24.callMethod(
  //   'lists.section.get',
  //   params,
  //   function(result) {
  //     if (result.error())
  //       alert("Error: " + result.error());
  //     else
  //       console.log(result.data());
  //   }
  // );

  // for(var key in arr) {
  //   var params = {
  //       'IBLOCK_TYPE_ID': 'lists',
  //       'IBLOCK_ID': '75',
  //       'ELEMENT_CODE': 'TG12_17_element_'+key,
  //       'FIELDS': {
  //           'NAME': "TG12 "+arr[key].nomer,
  //           'IBLOCK_SECTION_ID': 579
  //       }
  //   };
  //   BX24.callMethod(
  //       'lists.element.add',
  //       params,
  //       function(result)
  //       {
  //           if(result.error())
  //               alert("Error: " + result.error());
  //           else
  //           console.log(1);
  //               // alert("Success: " + result.data());
  //       }
  //   );
  // }

  // for(var key in arr) {
  //   var params = {
  //       'IBLOCK_TYPE_ID': 'lists',
  //       'IBLOCK_ID': '74',
  //       'ELEMENT_CODE': arr[key].title,
  //       'FIELDS': {
  //           'NAME': arr[key].title,
  //           'IBLOCK_SECTION_ID': 579
  //       }
  //   };
  //   BX24.callMethod(
  //       'lists.element.add',
  //       params,
  //       function(result)
  //       {
  //           if(result.error())
  //               alert("Error: " + result.error());
  //           else
  //           console.log(1);
  //               // alert("Success: " + result.data());
  //       }
  //   );
  // }

  $.getJSON( "https://jsonplaceholder.typicode.com/todos", function( data ) {
// let d = JSON.parse(data);
console.log(data);//check data coming properly or not 

//do rest of the coding accordingly
});

$.getJSON( "https://dstinfo.ru/breaks/bitr.php", function( data ) {
// let d = JSON.parse(data);
console.log(data);//check data coming properly or not 

//do rest of the coding accordingly
});

$.getJSON( "https://dstinfo.ru/breaks/bitr.php", { name: "John", time: "2pm" } )
  .done(function( json ) {
    console.log( "JSON Data: " , json );
  })
  .fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
});



</script>