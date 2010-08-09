
		//stationClick Function that gets passed $station->tag, $count->station, $count->p_in
		//Needs to take you to page for station

function stationClick(tag,name,id,p_in){
	if(confirm("Are you sure you'd like to delete '"+name+"'?")){
		new Ajax.Request('/delete.php?id='+id,{onFailure:function(t){alert(t.responseText);},onSuccess:function(t){suc(t,tag,name,id,p_in);}})
	}
}

function suc(t,tag,name,id,p_in){
	$('c_'+id).style.display='none';
}

		//stationOver Another function on onmouseover that displays transparent box with station name
		//Need to add onmouseover thingy to the svg thingy at the bottom of this document

function stationOver(tag,name,id,p_in){
	alert(name);
}
