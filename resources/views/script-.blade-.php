<style>.iti.iti--allow-dropdown.iti--separate-dial-code{width: 100%;}</style>
<?php $id=Auth::user()->id;?>
<script type="text/javascript">
function update_user_notifications_badge(){		
jQuery.ajax({
  url: "{{ url('/get_user_inbox_count') }}",
  type: "POST",
  data: {
	  "_token": "{{ csrf_token() }}",
	 id: {{$id}},
	 },
  
}).done(function(resp) {
    if(resp == 0 && resp <= 0){
        jQuery('#admin_notifications>a>span.count_notifications').hide();
    }else if(resp == 2){
        jQuery('#admin_notifications>a>span.count_notifications').html(1);
    }else{
        jQuery('#admin_notifications>a>span.count_notifications').html(resp);
    }
});
	}
function update_admin_notifications_badge(){		
jQuery.ajax({
  url: "{{ url('/get_admin_inbox_count') }}",
  type: "POST",
  data: {
	  "_token": "{{ csrf_token() }}",
	 id: {{$id}},
	 },
  
}).done(function(resp) {
    if(resp == 0 && resp <= 0){
        jQuery('#admin_notifications>a>span.count_notifications').hide();
    }else if(resp == 2){
        jQuery('#admin_notifications>a>span.count_notifications').html(1);
    }else{
        jQuery('#admin_notifications>a>span.count_notifications').html(resp);
    }
});
	}
	
		setTimeout(	update_user_notifications_badge(), 1000);
	setTimeout(	update_admin_notifications_badge(), 1000);
	
			jQuery(function(){

		setTimeout(	update_user_notifications_badge(), 1000);
	setTimeout(	update_admin_notifications_badge(), 1000);
				
			});	
			
			
			
				 jQuery(".read_it").on('click',function(){
					 let the_id = jQuery(this).data('id'), user = jQuery(this).data('user'), the_bolding_class = jQuery(this).closest('tr.New').removeClass('New');
					 
					console.log('ID: '+the_id+' User: '+user);
				 
				 
jQuery.ajax({
  url: "{{ url('/upd_msg_status') }}",
  type: "POST",
  data: {
	  "_token": "{{ csrf_token() }}",
	 id: the_id,
	 user_type: user
                  },
  
}).done(function(resp) {
	update_admin_notifications_badge();
	update_user_notifications_badge();
  console.log( resp );
});

				
			});
			
			
     jQuery(".validate_number").bind('keyup blur',function(){
         var value=parseInt($(this).val());
         if(value>10000000)
         {
             $(this).val('');
         }
         else if(value<1)
         {
             $(this).val('');
         }	



     jQuery(".validate_number_z").bind('keyup blur',function(){
         var value=parseInt($(this).val());
         if(value>10000000)
         {
             $(this).val('');
         }
         else if(value<0)
         {
             $(this).val('');
         }
         
     });
    jQuery('.alpha-only').bind('keyup blur',function(){ 
        //console.log('reaching....');
        var node = $(this);
        node.val(node.val().replace(/[^a-zA-Z]/g,'') ); 
    });
     

			
/**************###***********/
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
        customPlaceholder: function (
            selectedCountryPlaceholder,
            selectedCountryData
        ) {
            return "e.g. " + selectedCountryPlaceholder;
        },
    });
     var input = document.querySelector("#contact_iso");
    window.intlTelInput(input, {
        separateDialCode: true,
        customPlaceholder: function (
            selectedCountryPlaceholder,
            selectedCountryData
        ) {
            return "e.g. " + selectedCountryPlaceholder;
        },
    });
</script>
