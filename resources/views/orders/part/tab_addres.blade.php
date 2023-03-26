 <?php
    $adress = ''; 
	$geometka = '56.32,  44.00'; $geometkajs = [  '56.32',  '44.00' ];
	$mapzoom = 10;
	$mapcenter = $geometka; $mapcenterjs = $geometkajs; 
	if (isset($order->addres)){
		$adress = $order->addres;
		$geometka = $order->geometka;
		if($geometka){
		  $geoArr = explode(',',$geometka);
		  $geometkajs =  [ $geoArr[0], $geoArr[1] ];
		  $mapzoom = $order->mapzoom;
		  $mapcenter = $order->mapcenter;
		  $geoCentrArr = explode(',',$mapcenter);
		  $mapcenterjs =  [ $geoCentrArr[0], $geoCentrArr[1] ];
		}
	}
	if (count($errors) > 0){ //!!! ПРи ошибки гео сделать чтобы не сбрасывался
		$adress = old('addres');
		$geometka = old('geometka');
		$mapzoom = old('mapzoom');
		$mapcenter = old('mapcenter');
	}
 ?>
<!-- <script src="https://api-maps.yandex.ru/2.0-stable/?apikey=YOUR_APY_KEY&load=package.full&lang=ru-RU" type="text/javascript"></script>	-->
 <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
 <span> Адрес </span>
 <input form="formOrder" style="width:48%" type="text" name="addres" value="{{ $adress }}" > &nbsp;&nbsp; &nbsp; 
  </br></br>
  <div class="row">
     <div  class="col-md-4" >
	    <p> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; Установите место доставки на карте </p>
		
<div id="coord_form" style="display:none">
	<p><label>Коорд. метки: &nbsp;&nbsp; </label>
	   <input id="latlongmet"   name="geometka" value='{{ $geometka }}' form="formOrder" />
	</p>
	<p><label>&nbsp;&nbsp;Масштаб: &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; </label>
	   <input id="mapzoom"   name="mapzoom" value="{{ $mapzoom }}" form="formOrder" />
	</p> 
	<p><label>Центр карты:  &nbsp;&nbsp; </label>
	   <input id="latlongcenter"   name="mapcenter" value="{{ $mapcenter }}" form="formOrder" />
	</p>
</div>

     </div>
     <div  class="col-md-8" >
	    
        <div id="myMap" style="background-color:#dfe3df; width: 700px; height: 320px;"></div>
	 </div>
  </div>
  
  <script>
    var myMap, myPlacemark 
	var coords = <?php echo json_encode($geometkajs, JSON_NUMERIC_CHECK) ?>;
    var mapcenter =	<?php echo json_encode($mapcenterjs, JSON_NUMERIC_CHECK) ?>;
	var mapzoom = "{{ $mapzoom }}";
    ymaps.ready(init);

	function init() {
		myMap = new ymaps.Map ('myMap', {
			center: mapcenter,// напр. [55.75, 37.61],
			zoom: mapzoom, //напр. 10
			behaviors: ['default', 'scrollZoom']//Изменять масштаб будим скролом мышки
		});
	    myPlacemark = new ymaps.Placemark(coords, { //напр. coords == [55.76, 37.64]
           iconContent: 'Место доставки', 
           balloonContent: 'Адрес доставки - ' 
        }, { preset: 'twirl#blueStretchyIcon',
		     draggable: true
		});
		//Определяем элемент управления поиск по карте	
		//let SearchControl = new ymaps.control.SearchControl({noPlacemark:true}); - ГЛЮК
		myMap.controls
		  //.add(SearchControl) - ГЛЮК
          .add('mapTools')       // стандартные кнопки
          .add('typeSelector')   // переключатель типа карты
         //.add('zoomControl');   // изменение масштаба
		  
		myMap.geoObjects.add(myPlacemark); 
        //Отслеживаем событие перемещения метки
			myPlacemark.events.add("dragend", function (e) {			
			coords = this.geometry.getCoordinates();
			savecoordinats();
			}, myPlacemark);
        //Отслеживаем событие щелчка по карте
			myMap.events.add('click', function (e) {        
            coords = e.get('coordPosition');
			savecoordinats();
			});	
       	//Отслеживаем событие выбора результата поиска - ГЛЮК
	    //SearchControl.events.add("resultselect", function (e) {
		//coords = SearchControl.getResultsArray()[0].geometry.getCoordinates();
		//savecoordinats();
	    //});
       	//Ослеживаем событие изменения области просмотра карты - масштаб и центр карты
	   myMap.events.add('boundschange', function (event) {
          if (event.get('newZoom') != event.get('oldZoom')) {		
            savecoordinats();
          }
	      if (event.get('newCenter') != event.get('oldCenter')) {		
            savecoordinats();
          } 
	   });	
	
	}
	//Функция для передачи полученных значений в форму
	function savecoordinats (){	
		var new_coords = [coords[0].toFixed(4), coords[1].toFixed(4)];	
		myPlacemark.getOverlay().getData().geometry.setCoordinates(new_coords);
		document.getElementById("latlongmet").value = new_coords;
		document.getElementById("mapzoom").value = myMap.getZoom();
		var center = myMap.getCenter();
		var new_center = [center[0].toFixed(4), center[1].toFixed(4)];	
		document.getElementById("latlongcenter").value = new_center;	
	}
	
  </script>
  
    