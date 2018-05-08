<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

function command($sql)
{
	$args = func_get_args();
	array_shift($args);
 
	if(!empty($args)) {
		foreach($args as &$arg) {
			$arg = escape($arg);
		}
		$sql = call_user_func_array('sprintf', array_merge(array($sql), $args));
	}
 
	return Yii::app()->db->createCommand($sql);
}

//$count = SELECT COUNT(*) FROM `table`;
$res = Yii::app()->db->createCommand()
    ->select('count(*) as cnt')
    ->from('ra')
    ->queryRow();
$count = $res['cnt'];

$zapros = array();
while (count($zapros) < 3) {
  $zapros[] = '(SELECT id_ra, name, logo FROM `ra` LIMIT '.rand(0, $count).', 1)';
}

$zapros = implode(' UNION ', $zapros);

//$zapros = mysql_query($zapros);
$posts = command($zapros)->queryAll();

//foreach($posts as $post) {
//}
?>
<h3 style="text-align:center">Клиенты</h3>  
<div id="gallery-panel"></div>
<script type="text/javascript">
var gallery = {
	columns: 4,
	message: true,
	width: 160,
	height: 130,

	data: <?php echo CJSON::encode($posts);?> 
}


gallery.show = function() {
  sb = new StringBuilder();
  var w = gallery.width;
  sb.append('<div class="gallery-row">');
  for(i=0; i<gallery.data.length;i++)
  {
	sb.append('<div class="gallery-block" >');
	sb.append('<a href="/site/showra&agency='+gallery.data[i].id_ra+'"><img src="/content/'+gallery.data[i].logo+'" width="'+gallery.width+'" height="'+gallery.height+'"></a>');
	sb.append('<div class="gallery-caption" style="width:'+w+'">'+gallery.data[i].name+'</div>');
	sb.append('</div>');
  }                         
  sb.append('</div>');
  $("#gallery-panel").html(sb.toString());  
}


// Initializes a new instance of the StringBuilder class
// and appends the given value if supplied
function StringBuilder(value)
{
    this.strings = new Array("");
    this.append(value);
}

// Appends the given value to the end of this instance.
StringBuilder.prototype.append = function (value)
{
    if (value)
    {
        this.strings.push(value);
    }
}

// Clears the string buffer
StringBuilder.prototype.clear = function ()
{
    this.strings.length = 1;
}

// Converts this instance to a String.
StringBuilder.prototype.toString = function ()
{
    return this.strings.join("");
}

$(document).ready(function() {
    gallery.show();

	});

</script>
