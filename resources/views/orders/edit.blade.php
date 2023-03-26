   <style>
    .tabs {
      max-width: 90%;
      margin-left: auto;
      margin-right: auto;
    }

    .tabs {
      border: 1px solid lightgray;
    }

    .tabs__nav {
      display: flex;
      flex-wrap: wrap;
      list-style-type: none;
      background: #f8f8f8;
      margin: 0;
      border-bottom: 1px solid lightgray;
    }

    .tabs__link {
      padding: 0.5rem 0.75rem;
      text-decoration: none;
      color: black;
      text-align: center;
      flex-shrink: 0;
      flex-grow: 1;
    }

    .tabs__link_active {
      background: lightgray;
      cursor: default;
    }

    .tabs__link:not(.tabs__link_active):hover,
    .tabs__link:not(.tabs__link_active):focus {
      background-color: #efefef;
    }

    .tabs__content {
      padding: 1rem;
    }

    .tabs__pane {
      display: none;
    }

    .tabs__pane_show {
      display: block;
    }
  </style>
  
  
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href='{{ route("orders")}}'> << </a>  {{ __('Редактирование  заказа') }}
        </h2>
    </x-slot> 
	 @if(Session::has('message'))
		  <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show col-md-12" role="alert">
            <span style="color:red"> {{ Session::get('message') }}</span>
            <button  type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span  onclick="offErr()" aria-hidden="true">&times;</span>
            </button>
          </div>
	  @endif
	  @if (count($errors) > 0)
         <div class="alert alert-danger" style="text-align: center;" role="alert">
	 	   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span  onclick="offErr()" aria-hidden="true">&times;</span>
           </button>
           @foreach ($errors->all() as $error)
            <b style="color:red">  {{ $loop->iteration.") - ". $error }}</b><hr>
           @endforeach
         </div>
       @endif
  <div class="tabs">
    <div class="tabs__nav">
      <a class="tabs__link tabs__link_active" href="#content-1">Реквизиты</a>
      <a class="tabs__link" id="produkts-tab" href="#content-2">Товары заказа</a>
      <a class="tabs__link" href="#content-3">Доставка</a>
    </div>
    <div class="tabs__content">
      <div class="tabs__pane tabs__pane_show" id="content-1">
		 <div class="py-12">
 	        <form id="formOrder" enctype="multipart/form-data" method="POST"  action="{{route('orders.update', ['id'=>$order->id ]) }}">
	           {{csrf_field()}}
			   <span>&nbsp;&nbsp;&nbsp;&nbsp; Дата </span>&nbsp;&nbsp;<input type="text" name="date" value="{{ $order->date }}"  >&nbsp;&nbsp; &nbsp;&nbsp; </br> </br>
			   <span> Телефон </span><input type="text" name="phone" value="{{$order->phone}}" >&nbsp;&nbsp; &nbsp; </br> </br>  
               <span>&nbsp;&nbsp;&nbsp; Email &nbsp; </span>  <input type="text" name="email" value="{{ $order->email}}"  > &nbsp;&nbsp; &nbsp; </br> </br>    			  
			   <input type="hidden"  value="{{ $order->total }}"  id="totalOrder"  name="total" > <!-- в js вложен. part.tab_products считаем сумму сюда -->
               <input type="hidden"  value="{{ $order->prod_ids }}"  id="prodID"  name="prod_ids" >			   
		   </form>			 
        </div> 
      </div>
      <div class="tabs__pane" id="content-2">
        @include('orders.part.tab_products')
      </div>
      <div class="tabs__pane" id="content-3">
	    @include('orders.part.tab_addres')
     </div>
    </div>
	<div class="row">
      <div class="col-md-12" ><!-- этот заполняем на js во вложенном в табе товары -->
       <h6>  &nbsp; &nbsp;&nbsp; Сумма заказа ___ <span id="summaDockumenta"> {{ $order->total }} (руб.)</span> &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;            
            <input form="formOrder" type="submit" style="background-color:#e5e7eb; color: black; border: 2px solid #4CAF50; cursor:pointer" value="&nbsp;&nbsp; Оформить заказ &nbsp;&nbsp;" >
	   </h6> 
      </div>  
  </div>
  </div>

    <script>
	     function offErr(){ $('.alert').fadeOut(1000);	}  
			   
			var $tabs = function (target) {
			  var
				_elemTabs = (typeof target === 'string' ? document.querySelector(target) : target),
				_eventTabsShow,
				_showTab = function (tabsLinkTarget) {
				  var tabsPaneTarget, tabsLinkActive, tabsPaneShow;
				  tabsPaneTarget = document.querySelector(tabsLinkTarget.getAttribute('href'));
				  tabsLinkActive = tabsLinkTarget.parentElement.querySelector('.tabs__link_active');
				  tabsPaneShow = tabsPaneTarget.parentElement.querySelector('.tabs__pane_show');
				  // если следующая вкладка равна активной, то завершаем работу
				  if (tabsLinkTarget === tabsLinkActive) {
					return;
				  }
				  // удаляем классы у текущих активных элементов
				  if (tabsLinkActive !== null) {
					tabsLinkActive.classList.remove('tabs__link_active');
				  }
				  if (tabsPaneShow !== null) {
					tabsPaneShow.classList.remove('tabs__pane_show');
				  }
				  // добавляем классы к элементам (в завимости от выбранной вкладки)
				  tabsLinkTarget.classList.add('tabs__link_active');
				  tabsPaneTarget.classList.add('tabs__pane_show');
				  document.dispatchEvent(_eventTabsShow);
				},
				_switchTabTo = function (tabsLinkIndex) {
				  var tabsLinks = _elemTabs.querySelectorAll('.tabs__link');
				  if (tabsLinks.length > 0) {
					if (tabsLinkIndex > tabsLinks.length) {
					  tabsLinkIndex = tabsLinks.length;
					} else if (tabsLinkIndex < 1) {
					  tabsLinkIndex = 1;
					}
					_showTab(tabsLinks[tabsLinkIndex - 1]);
				  }
				};

			  _eventTabsShow = new CustomEvent('tab.show', { detail: _elemTabs });

			  _elemTabs.addEventListener('click', function (e) {
				var tabsLinkTarget = e.target;
				// завершаем выполнение функции, если кликнули не по ссылке
				if (!tabsLinkTarget.classList.contains('tabs__link')) {
				  return;
				}
				// отменяем стандартное действие
				e.preventDefault();
				_showTab(tabsLinkTarget);
			  });

			  return {
				showTab: function (target) {
				  _showTab(target);
				},
				switchTabTo: function (index) {
				  _switchTabTo(index);
				}
			  }
			};
			$tabs('.tabs');
    </script>
</x-app-layout>