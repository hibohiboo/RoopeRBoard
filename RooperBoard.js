$(document).ready(function(){

	var tmp=$("#mystery");
	if(tmp.prop('checked')==true){
		$('.kasi').css("display","table-cell");
	}
	//$("#mystery");
	tmp.click(function(){
		if($(this).prop('checked')){
			$('.kasi').css("display","table-cell");
		}else{
			$('.kasi').css({'display':'none'});
		}
	});
	
	tmp=$("#haunted");
	if(tmp.prop('checked')==true){
			$('#ctr20').css("display","table-row");
			$('#ctr21').css("display","table-row");
	}
	tmp.click(function(){
		if($(this).prop('checked')){
			$('#ctr20').css("display","table-row");
			$('#ctr21').css("display","table-row");
		}else{
			$('#ctr20').css("display","none");
			$('#ctr21').css("display","none");
		}
	});
	
	
	for(var i=0;i<22;i++){
		var box=$("#u"+i);
		if(box.prop('checked')==true){
				$("#p"+i).prop('disabled',false);
				$("#d"+i).prop('disabled',false);
				$("#k"+i).prop('disabled',false);
				$("#y"+i).prop('disabled',false);
				$("#h"+i).prop('disabled',false);
				$("#a"+i).prop('disabled',false);
				$("#rr"+i).prop('disabled',false);
				$("#rw"+i).prop('disabled',false);
				$("#ri"+i).prop('disabled',false);
				$("#rA"+i).prop('disabled',false);
				$("#rB"+i).prop('disabled',false);
				$("#rC"+i).prop('disabled',false);
		}
		
		box.data("i",i).click(function(){
			var i=$(this).data("i");
			if($(this).prop('checked')){
				$("#p"+i).prop('disabled',false);
				$("#d"+i).prop('disabled',false);
				$("#k"+i).prop('disabled',false);
				$("#y"+i).prop('disabled',false);
				$("#h"+i).prop('disabled',false);
				$("#a"+i).prop('disabled',false);
				$("#rr"+i).prop('disabled',false);
				$("#rw"+i).prop('disabled',false);
				$("#ri"+i).prop('disabled',false);
				$("#rA"+i).prop('disabled',false);
				$("#rB"+i).prop('disabled',false);
				$("#rC"+i).prop('disabled',false);
			}else{
				$("#p"+i).prop('disabled',true);
				$("#d"+i).prop('disabled',true);
				$("#k"+i).prop('disabled',false);
				$("#y"+i).prop('disabled',true);
				$("#h"+i).prop('disabled',true);
				$("#a"+i).prop('disabled',true);
				$("#rr"+i).prop('disabled',true);
				$("#rw"+i).prop('disabled',true);
				$("#ri"+i).prop('disabled',true);
				$("#rA"+i).prop('disabled',true);
				$("#rB"+i).prop('disabled',true);
				$("#rC"+i).prop('disabled',true);
			}
		});
	}

});

function box_click(cnt){
	if($('#usecheck_'+cnt).attr('checked')){
		$('#schar'+cnt).attr({'disabled':false});

	}else{
		$('#schar'+cnt).attr({'disabled':true});
	}
}


