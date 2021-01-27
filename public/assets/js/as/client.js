$('#from_date').datepicker({
    orientation: 'bottom',
    startView: 'years',
    format: 'dd-mm-yyyy'
});

$('#to_date').datepicker({
    orientation: 'bottom',
    startView: 'years',
    format: 'dd-mm-yyyy'
});


$('#import_date').datepicker({
    orientation: 'bottom',
    startView: 'years',
    format: 'dd-mm-yyyy'
});

$('#sale_stage_id').chosen({
	  allow_single_deselect: true,
	  width: '100%'
});

$('#assign_user_id').chosen({
	  allow_single_deselect: true,
	  width: '100%'
});
$('#campaign_id').chosen({
	  allow_single_deselect: true,
	  width: '100%'
});
$('#reassign_user_id').chosen({
	  allow_single_deselect: true,
	  width: '100%'
});

$('#range_time').chosen({
	  allow_single_deselect: true,
	  width: '100%'
});

$('#report_type').chosen({
	  allow_single_deselect: true,
	  width: '100%'
});

if ($('#client-form').length) {
    CKEDITOR.replace( 'description', {
    filebrowserBrowseUrl: "{{ route('ckfinder_browser') }}",

	} );
}


$('#select_all').click(function(){
	$('input:checkbox[class=select_client]').not(this).prop('checked', this.checked);
});



$('#export-client-form').on('submit', function() {
	$('#search_form input, #search_form select').each(function(index){  
		$("<input />").attr("type", "hidden")
          .attr("name", $(this).attr('name'))
          .attr("value", $(this).val())
          .appendTo("#export-client-form");
    });
    return true;
}); 

$('#reassign-client-form').on('submit', function( event ) {
	event.preventDefault();
    var selectedIds = [];
	$("input:checkbox[class=select_client]:checked").each(function(){
	    selectedIds.push($(this).data('id'));
	});
	
	var data = {
		'reassign_user_id': $('#reassign_user_id').val(),
		'client_ids': selectedIds
	};
	
	$.post("clients/assign-client", data, function(result){
    	location.reload();
  	});
});

$('.btnDelete').on('click', function(event){
	var selectedIds = [];
	$("input:checkbox[class=select_client]:checked").each(function(){
	    selectedIds.push($(this).data('id'));
	});
	var data = {
		'client_ids': selectedIds
	};
	
	$.post("clients/delete-bulk", data, function(result){
    	location.reload();
  	});
});
