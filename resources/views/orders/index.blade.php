<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ЗАКАЗЫ') }}
        </h2>
    </x-slot> 
	
 
 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"> 
				{{ $html->table() }}
                </div>
            </div>
        </div>
    </div>
    
 	 
	 <script>
	    function delOrder(id){
			alert('===='+id);
		}
		//$(document).ready(function() {
		//  $.noConflict();    
		//  $('#products').DataTable({
		//	 ajax: '',
		//	 serverSide: true,
		//	 processing: true,
		//	   columns: [
		//		 {data: 'id', name: 'id'},
		//		 {data: 'name', name: 'name'},
		//		 {data: 'price', name: 'price'},            
		//	   ]
		//   });
		//})
		
	 </script>
	 
	
	  {{ $html->scripts() }}
	
</x-app-layout>
  