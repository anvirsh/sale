
 <style>
* {
  margin: 0;
  outline: none !important;
  box-sizing: border-box;
}

input {
  padding: 8px 10px;
  border-radius: 3px;
  border: 1px solid #b0e086;
  box-shadow: 0 1px 5px rgba(10, 70, 10, 0.2);
  transition: all .3s ease;
}
input:focus {
  border: 1px solid #97b46c;
}

.autocomplete-wrap {
  position: relative;
   
}

.autocomplete-input {
  width: 100%;
  background: #eee;
}

.autocomplete-list {

  position: absolute;
  top: 100%;
  border: 1px solid #97b46c;
  width: 100%;
  display: none;
  z-index: 9999;
  max-height: 400px;
  overflow-y: auto;
  background: #eee;

}

.autocomplete-wrap.active .autocomplete-list {
  display: block;
  border-radius: 0 0 3px 3px;
}

.autocomplete-item {
  padding: 8px 10px;
  cursor: pointer;
  transition: all .3s ease;
}
 
.autocomplete-wrap.active .autocomplete-input {
  border-radius: 3px 3px 0 0;
  border-bottom: 1px solid transparent;
}

</style>
			  
			 <table class="table table-hover table-bordered">
          <thead class="thead-light">
            <tr>
							<th>Наименование</th>
		          <th>Количество</th>
							<th>Цена за ед.</th>
							<th>Сумма</th>
		          </tr>
           </thead>
           <tbody id="boditab"> 
              <tr id="dmorow">
					     <td style="cursor: pointer;"><span> - </span></td>
               <td style="cursor: pointer;"><span> - </span></td>
						   <td style="cursor: pointer;"><span> - </span></td>
						   <td style="cursor: pointer;"><span> - </span></td>
						   <td>	- </td>
					   </tr>
          </tbody>
       </table>

       <hr>
        
        <div class="form-group row">
          <div class="col-sm-4" id="divAutocompit" style="margin-left:2%">
            <input type="text"  id="autocomplete" onkeyup="autoComp(this)" class="form-control"  name="name" autocomplete="off"   placeholder="вводите по буквам товар">
                     
            <select id="selctTovar" class="form-control" style="display:none;" onchange="setInput()" >
              <option   value="a1">выберите из списка</option>
            </select>

          </div>
         <h3 onclick="showSelect(this)" title="Выбор товара из списка или ввод по буквам названия" style="cursor: pointer;"> v </h3>
           
          <label id="lblForKolvo" for="inputCountNomnklt" class="col-sm-1 col-form-label" style="display: none;">Кол-во</label>
          <div  class="col-sm-1">
          	  <input type="text"  id="inputCountNomnklt" class="form-control" style="display: none;" placeholder="0">
           </div>
           <div  class="col-sm-3" id="newInputs">
          	 <button id="cmdAddInTabl" type="button" class="btn btn-default" onclick="addInputTovar()" style="display: none;">Добавить в таблицу</button>
          </div>

        </div>
        


 
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> -->

<!-- <script src="{{asset('js/mayscript.js')}}"></script> -->
<!-- <script src="{{asset('js/bootstrap/js/bootstrap.min.js')}}"></script> -->
<!-- <script src="{{asset('js/ui/jquery-ui.js')}}"></script> -->        
<script> 
<?php  if(!isset($arrTovars)){ $arrTovars =[];} ?>;// $arrTovars может и прилететь если это edit
<?php  if (count($errors) > 0){ $arrTovars = old();} ?>;//
let oldInputs = <?php echo json_encode($arrTovars, JSON_NUMERIC_CHECK) ?>;//Сюда получим ошибки валидации
 // let masscat = {"nomclts": ["гайка","гайка2","гайка3","гайка4",  ]};
 // Autocomplete('#autocomplete', masscat); //- аутокомплит так работает сразу когда не в фунции
let masscat = <?php echo json_encode($products, JSON_NUMERIC_CHECK) ?>;
let idTovar = 0; // сохранит выбранный товар его id
let nameTovar = "Не выбрано"; // сохранит выбранный товар его название
let priseTovar = 0; // цена товара
let summaDockumenta = 0; // сумма документа всего
let indexN = 0; // сохранит индекс поля формы атрибута name[]
let flagSelect = false; //флаг скажет мы в автокомплите или в селекте
let kolvoInTab = 0; //Для показа сколько позицый товаров в таблице в заголовке Таба (Товары(4))
let prodIDs ='';//Ин-строка где (id_name_кол-во_цена)__(id_name_кол-во_цена)... (для хронения в базе)

function showSelect(elem){
   if(flagSelect == false){
      $("#autocomplete").css({"display":"none"});
      $("#selctTovar").fadeIn();
      flagSelect = true;
	  $(elem).text('v');
   } else {
      $("#selctTovar").css({"display":"none"});
      $("#autocomplete").fadeIn();
      flagSelect = false;
	  $(elem).text('||');
   }      

}


function addInputTovar(idt='', nameT='', colvo='',summa='') { //Создаём скрытые текст-поля формы с товаром,(готовим к отправке на сервер) и пишим строки в таблицу для наглядности
   //let parent = document.getElementById("newInputs");
   //  масссив-input === https://ru.stackoverflow.com/questions/851723/%D0%98%D0%BD%D0%BF%D1%83%D1%82%D0%BE%D0%B2-%D1%81-%D0%BE%D0%B4%D0%B8%D0%BD%D0%B0%D0%BA%D0%BE%D0%B2%D1%8B%D0%BC-%D0%B8%D0%BC%D0%B5%D0%BD%D0%B5%D0%BC-%D0%BA%D0%B0%D0%BA-%D0%B8%D1%85-%D0%BF%D1%80%D0%B8%D0%BD%D1%8F%D1%82%D1%8C-%D0%B8-%D1%81%D0%BE%D1%85%D1%80%D0%B0%D0%BD%D0%B8%D1%82%D1%8C
   //Текст-поля вставляем скрытые со значениями
   if(idt == ''){
	   idt = idTovar;    nameT = nameTovar; 
	   summa = priseTovar; //Всегда за еденицу (когда из old() получаем там сууму-делим-на-колво там нет за енц.)
	   colvo = $("#inputCountNomnklt").val();	    
	}
   //***anv !!! - В наименовании товара не должно быть сочетания '_;' - (нижнееПодчёркивание+точкаСзапятой 
   //nameT = !!! заменить все сочетания '_;' в названии на '_:'(на тире с двоеточием) Здесь для перестраховки есть в php
   nameT.split('_;').join('_:'); // '_;'-Это служебный разделитель, нужен в коде, может и в .csv поэтому заменим на '_:'
   if(colvo < 1) { alert ("Количество не указано!"); return;}//выход 0 товаров добавляем
   bodiTab = document.querySelector("#boditab");
   if (indexN == 0){bodiTab.innerHTML = '';}//очистим таблицу при первом добавлении товара
   let divInputs = document.createElement('div'); //див для ряда(тройки) скрытых тексто-полей
   divInputs.setAttribute('id', "divInputs"+indexN);
   let elem = document.createElement('input'); //скрытое текст-поле id товара ----------------
   elem.setAttribute("form", "formOrder");
   elem.setAttribute('type', "text");
   elem.setAttribute('name', "tovar["+indexN+"][tovar_id]");
   elem.setAttribute('value', idt );
   divInputs.appendChild(elem); 
   let elem1 = document.createElement('input'); //скрытое текст-поле nametovar товара ----------------
   elem1.setAttribute("form", "formOrder");
   elem1.setAttribute('type', "text");
   elem1.setAttribute('name', "tovar["+indexN+"][nametovar]");
   elem1.setAttribute('value', nameT );
   divInputs.appendChild(elem1);    
   let elem2 = document.createElement('input'); //скрытое текст-поле кол-во ----------------------------------
   elem2.setAttribute("form", "formOrder");
   elem2.setAttribute('type', "text");
   elem2.setAttribute('name', "tovar["+indexN+"][count]");
   elem2.setAttribute('value', colvo );
   divInputs.appendChild(elem2);
   let elem3 = document.createElement('input'); //скрытое текст-поле сумма за позицию ---------
   elem3.setAttribute("form", "formOrder");
   elem3.setAttribute('type', "text");
   elem3.setAttribute('name', "tovar["+indexN+"][summa]");
   elem3.setAttribute('value', colvo * summa );
   divInputs.appendChild(elem3);
   document.querySelector("#newInputs").appendChild(divInputs); //вставили скрытые поля 3щт.=====
   divInputs.style.display = "none";// скроем див с новыми полями
   //Вставляем строку в таблицу с названием товара
   let rowTab = document.createElement('tr'); // ряд таблицы создали
   rowTab.setAttribute('id', "tabRow"+indexN);
   let y1Row =  '<td style="cursor: pointer;"><span><a href="#" style="color:#111">'+nameT+'</a></span></td>'; //ячейку наименование товара
   let y2Row =  '<td><span style="color:#111">'+ colvo + '</span></td>';
   let y3Row =  '<td><span style="color:#111">'+ summa + ' руб.</span></td>';
   let y4Row =  '<td><span id="ySumm'+indexN+'" style="color:#111">'+ colvo * summa+'</span> руб.</td>';
   let y5Row =  '<td><button class="m-0 p-0 border-0 bg-transparent" onclick="deletTovar('+indexN+','+idt+')"title="Удалить"><i class="far fa-trash-alt text-default"> X </i></button></td>';
   rowTab.innerHTML = y1Row + y2Row +y3Row + y4Row + y5Row;
   bodiTab.appendChild(rowTab);
   bodiTab.appendChild(divInputs);//div с инпутом вставляем в таблицу т.к. будим искать старые значения при err-валидации
   $("#autocomplete").val('');//
   $("#inputCountNomnklt").val('');//
   indexN++//увеличим индекс для следующего ряда инпутов
   summaDockumenta = summaDockumenta + (colvo * summa);
   totalOrder = document.getElementById('totalOrder');
   totalOrder.setAttribute('value', summaDockumenta );//В скрытое поле пишим сумму документа
   if(prodIDs == ''){
	   prodIDs = idt+'_;'+nameT+'_;'+colvo+'_;'+summa; //Собираем id-товара, кол-во, цену в ин-строку
	} else {  
       prodIDs = prodIDs+'__;'+idt+'_;'+nameT+'_;'+colvo+'_;'+summa;
	}    
   $('#prodID').val(prodIDs);//Ин-строка где (id__name_кол-во_цена)__;(id_name_кол-во_цена)... (для хронения в базе)
   $('#summaDockumenta').text(summaDockumenta + '  (руб.)');// сумма документа
   $("#inputCountNomnklt").fadeOut("500");
   $("#lblForKolvo").fadeOut("500");
   $("#cmdAddInTabl").fadeOut("500");
   kolvoInTab++; //на одну позицию увеличим, для показа в табе в скобках
   $('#produkts-tab').text("Товары ("+kolvoInTab+")");
   	  // console.log('+++add+++');
	  // console.log(prodIDs);

}

  function deletTovar(inN, idTvr) {
     if(confirm("Удалить этот товар из таблицы ?")){
       summaDockumenta = summaDockumenta -  $('#ySumm'+inN).text();// сумму из ячейки вычитаем из общей
       $('#tabRow'+inN).remove();// ряд в таблице улалим
       $('#divInputs'+inN).remove();//ряд скрытых полей товара удалим
       //let rowSumma = $('#ySumm'+inN).text();// сумму из ячейки бирём для вычитания из общей
	   //Теперь надо и из скрыт-тест-поля prodID убрать инфу о товаре (та инфа что пишим в prod_ids в базе)
	   let arrProdIds = prodIDs.split('__;');//Разбили на массив напр.[0]=2_tovr_4_50 (id=2, имя=tovr, кол-во=4,цена-ед=50)
	   $.each(arrProdIds,function(index,item){  
           let arrProd = item.split('_;');//Разбили на под-массив где первая цыфра это id товара
		   if(arrProd[0] == idTvr) { 
			  arrProdIds.splice(index, 1);  // начиная с индекса index , удалить 1 элемент
			  return false; // прервать выполнение цикла
           }
       });
	   prodIDs = arrProdIds.join('__;') ;//Соединяем назад в ин-строку массив
       $('#prodID').val(prodIDs);//В скрыт-поле ин-строку где (id_кол-во_цена)__(id_кол-во_цена)... (для хронения в базе)
	   //console.log('---del---- . . id = '+idTvr);
	   //console.log(prodIDs);
	   $('#totalOrder').val(summaDockumenta);//В скрыт-текст-поле суммы документа
       $('#summaDockumenta').text(summaDockumenta + '  (руб.)');// сумма документа
       kolvoInTab--; //на одну позицию уменьшим, для показа в табе в скобках
       $('#produkts-tab').text("Товары ("+kolvoInTab+")");
       //console.log("сумма- "+ $('#ySumm'+inN).text());
     }
  }

 function autoComp(e){ 
  let cmdKey = window.event.keyCode;  
  let fWord = e.value; //console.log(e.value);
  if ((fWord.length < 2)&&(cmdKey != 8)&&(cmdKey != 32)&&(cmdKey != 46)){ return;}
  if(masscat){Autocomplete('#autocomplete', masscat);}
  if (fWord.length > 1){ e.focus();}//фокус уходит почемуто оставим его если не стёрто всё
  //console.log(window.event.keyCode);
}


function setInput(id=0, pricerozn=0, name=""){ // вставим в текст поле добавления
     if (id == 0){
        id = $("#selctTovar").val();
        name = $("#selctTovar").find('option:selected').data('name') ; 
        pricerozn = $("#selctTovar").find('option:selected').data('price');
     }
     idTovar = id; // сохранит выбранный товар его id
     nameTovar = name;
     priseTovar = pricerozn;
     $("#inputCountNomnklt").fadeIn("500");
     $("#inputCountNomnklt").val("1");
     $("#lblForKolvo").fadeIn("500");
     $("#cmdAddInTabl").fadeIn("500");
     //console.log(id + "=="+ name);
}
 
////////////////////////////////////////////
 
const Autocomplete = (selector, data) => { 

  let inputs = document.querySelectorAll(selector);

  function ciSearch(what = '', where = '') {
    what = what+"";//***anv от глюка если строка с цыфр начинается, так к строке приводим
    //console.log(what + " = " + where);
    return where.toUpperCase().search(what.toUpperCase());
  }
  inputs.forEach(input => {
    input.classList.add('autocomplete-input');
    let wrap = document.createElement('div');
    wrap.className = 'autocomplete-wrap';
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);

    let list = document.createElement('div');
    list.className = 'autocomplete-list';
    wrap.appendChild(list);

    let matches = [];
    let listItems = [];
    let focusedItem = -1;

    function setActive(active = true) {
      if(active)
        wrap.classList.add('active');
      else
        wrap.classList.remove('active');
    }

    function focusItem(index) {
      if(!listItems.length) return false;
      if(index > listItems.length - 1) return focusItem(0);
      if(index < 0) return focusItem(listItems.length - 1);
      focusedItem = index;
      unfocusAllItems();
      listItems[focusedItem].classList.add('focused');
    }
    function unfocusAllItems() {
      listItems.forEach(item => {
        item.classList.remove('focused');
      });
    }
    function selectItem(index) {
      if(!listItems[index]) return false;
      input.value = listItems[index].innerText;
      setActive(false);
    }
    input.addEventListener('input', () => {
      let value = input.value;
      if(!value.length) return setActive(false);
      list.innerHTML = '';
      listItems = [];
      if(value.length > 1){//Если набрали уже больше одной буквы
         data.data.forEach((dataItems, index) => {
         	 let dataItem = dataItems.name;//***anv здесь не простой массив с name, а есть id и другие поля
           let search = ciSearch(value, dataItem);
           if(search === -1) return false;
           matches.push(index);

          let parts = [
            dataItem.substr(0, search),
            dataItem.substr(search, value.length),
            dataItem.substr(search + value.length, dataItem.length - search - value.length)
          ];

          let item = document.createElement('div');
          item.className = 'autocomplete-item';
          item.innerHTML = parts[0] + '<strong>' + parts[1] + '</strong>' + parts[2];
          list.appendChild(item);
          listItems.push(item);

          item.addEventListener('click', function() {
            selectItem(listItems.indexOf(item));
            setInput(dataItems.id, dataItems.price, item.innerText); // вызываем фунцию вставки в поле пред добавлением
          });

        });
	  }
      if(listItems.length > 0) {
        focusItem(0);
        setActive(true);
      }
      else setActive(false);

    });

	 // для перемещения стрелками клавиатуры
     input.addEventListener('keydown', e => {

      let keyCode = e.keyCode;
      if(keyCode === 40) { // arrow down
        e.preventDefault();
        focusedItem++;
        focusItem(focusedItem);
      } else if(keyCode === 38) { //arrow up
        e.preventDefault();
        if(focusedItem > 0) focusedItem--;
        focusItem(focusedItem);
      } else if(keyCode === 27) { // escape
        setActive(false);
      } else if(keyCode === 13) { // enter
        selectItem(focusedItem);
        findCategor(input.value); // вызываем фунцию при вводе ентер
        //console.log(input.value);
      }
      
    });  /////////////////////////////////////////////////

    document.body.addEventListener('click', function(e) {
      if(!wrap.contains(e.target)) setActive(false);
      //console.log("вне клик");
    });

  });

}
 
 $(document).ready(function(){ 
     document.body.addEventListener('click', function(e) {
      let elKlik = e.target.attributes.id; // id элемента по которому кликнули
     });
	 // console.log("===");
	 //console.log(masscat);
     // ниже заполним выпадающий список товаром
      $("#selctTovar").empty(); //очистим выпадающий список и заполним
      $("#selctTovar").append('<option value="">выберите из списка</option>');// пустой в начало списка
        masscat.data.forEach(function(entry) { 
          $("#selctTovar").append('<option data-name="'+entry.name+'" value="'+entry.id+'" data-price="'+entry.price+'">'+entry.name+'</option>');
       }); 
     let oldTotalOhterErr = $('#totalOrder').val();//Из скрытого поля берёмстарое значение после ошибки валидации
	 //console.log('-----=');
	 //console.log(oldTotalOhterErr);
	 if(oldTotalOhterErr){  
		$('#summaDockumenta').text(oldTotalOhterErr+'  (руб.)');//В надпись сумма заказа
	 }
	  //Пройдём ошибки валидации из old() там поля товаров из таблицы, востоновим их на форме
	  
	 if(oldInputs.tovar){
		for (key in oldInputs.tovar){// обходим все поля
		    let tid = oldInputs.tovar[key]['tovar_id'];
			let nametovar = oldInputs.tovar[key]['nametovar'];
			let colvo = oldInputs.tovar[key]['count'];
			let summa = oldInputs.tovar[key]['summa'];  
			addInputTovar(tid, nametovar, colvo, (summa/colvo));
            // console.log(key + ': ' + tid+'_;'+nametovar+'_;'+colvo+'_;'+summa);
		}
     }
      //console.log(e.target.attributes.id);  
});
	
	
</script>
 