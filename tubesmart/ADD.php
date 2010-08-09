<?PHP
require_once ('functions.php');
if(isset($_GET['name'])){ 
if(isset($_GET['px'])&&isset($_GET['px'])&&isset($_GET['line'])){
	$db->table="stations";
	$insert['name']=$_GET['name'];
	$insert['tag']= strtolower(str_replace(' ', '', $_GET['name']));
	$insert['line']= strtolower(str_replace(' ', '', $_GET['line']));
	$insert['x']=(int)$_GET['px'];
	$insert['y']=(int)$_GET['py'];
	switch ($_GET['type']){
		default:
		case 0:
	$insert['type']='major';//major, minor, disabled;
		break;
		case 1:
	$insert['type']='minor';//major, minor, disabled;
		break;
		case 2:
	$insert['type']='disabled';//major, minor, disabled;
		break;
	}
	$insert['type'];//major, minor, disabled;
	$db->insert_single($insert);
}else{ header("HTTP/1.1 Bad Request");echo "Not enough data passed.";}
die();}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Just click.</title>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.1.0/prototype.js"></script> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.3/scriptaculous.js"></script> 


<script type="text/javascript">
Event.observe(window, 'load', init);

var l_type;

function init(){
	$('track').observe('click', proClick);
}

function proClick(e){
	if(name=prompt("Station Name")){
		if(type=prompt("Station Type (0=major;1=minor;2=disabled;)",l_type)){
			l_type=type;
		url="ADD.php?name="+name+"&px="+e.pointerX()+"&py="+ e.pointerY()+"&type="+ type +"&line=<?PHP echo $_GET['line']; ?>";
		new Ajax.Request(url,{
			onSuccess:function(t){
				//
			},
			onFailure:function(t){
				alert("e: "+t.responseText);
			}
		});
	}}
}

//alert("hi");
</script>
</head>

<body>
<img src="/assets/tube-map.jpg" width="2079" height="1386" id="track" />
</body>
</html>
