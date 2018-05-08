<h4 class="bb">СЛУЧАЙНЫЕ АНКЕТЫ</h4>
	<script type="text/javascript" language="javascript">
		$(function() {

//			$('#carousel ul').carouFredSel({
//				prev: '#prev',
//				next: '#next',
//				pagination: "#pager",
//				auto: true,
//				scroll: 1000,
//				pauseOnHover: true
//			});

		});



function showResume(id)
{
   $("#vid").val(id);
   $("#form").attr("action","/site/ShowResumePublic");
   $("#btn_submit").click();

}
		</script>

		<style type="text/css" media="all">

			#carousel {
				margin: 0 0 30px 60px;
				width: 820px;
				position:relative;
			}
			#carousel ul {
				margin: 0;
				padding: 0;
				list-style: none;
				display: block;
			}
			#carousel li {
				font-size: 16px;
				color: #999;
				text-align: center;
				width: 190px;
				/*height: 198px;*/
				padding: 0;
				padding-bottom:10px;
				margin: 6px;
				display: block;
				float: left;
        border: 1px solid #D0D0D0;
				background: #fff;
				position:relative;
			}

			#carousel li img {
				width:160px;
				height:200px;
				margin-top:14px;
			}

			#carousel li a {
				width:160px;
				height:200px;
				position:absolute;
				display:block;
				z-index:2;
				top:14px;
				left:16px;
				background: transparent url('images/carousel_shine.png') no-repeat 0 0;
				text-indent:-999em;
			}

			.clearfix {
				float: none;
				clear: both;
			}

			#carousel .prev, #carousel .next {
				margin-left: 10px;
				width:15px;
				height:21px;
				display:block;
				text-indent:-999em;
				background: transparent url('images/carousel_control.png') no-repeat 0 0;
				position:absolute;
				top:70px;
			}
			#carousel .prev {
				background-position:0 0;
				left:-30px;
			}
				#carousel .prev:hover {
					left:-31px;
				}
			#carousel .next {
				background-position: -18px 0;
				right:-20px;
			}
				#carousel .next:hover {
					right:-21px;
				}
			#carousel .pager {
				margin:0 auto;
				text-align: center;
			}
			#carousel .pager a {
				margin: 0 5px 0 0;
				text-decoration: none;
				display:inline-block;
				width:8px;
				height:8px;
				background: transparent url('images/carousel_control.png') no-repeat -2px -32px;
				text-indent:-999em;
			}
			#carousel .pager a.selected {
				text-decoration: underline;
				background: transparent url('images/carousel_control.png') no-repeat -12px -32px;
			}
		.scont {
       			width: 163px;
			height: 52px;
			background:#EBE6E0;
			text-align:center;
			border: 10px solid #fff;
			margin:4px 4px 0px 4px;
			padding-top:4px;
			}

		.rating {
			width:84px;
			height:13px;
			margin: 0 auto;
			background: url('images/rt.png');

		</style>

			<div id="carousel">
				<ul>

<?php
//  Yii::app()->getClientScript()->registerCoreScript('jquery');
//  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.carouFredSel-5.2.3-packed.js', CClientScript::POS_HEAD);

		$result = Yii::app()->db->createCommand()
    	->select('r.id, r.id_user, r.photo, r.firstname, r.lastname, r.avg_rating')
    	->from('resume r')
      //->where('RAND() < 0.1')
//    	->order('RAND()')
    	->order('avg_rating DESC')
      ->limit(8)
    	->queryAll();

foreach($result as $row)
{
   echo '<li>';
   if($row['photo']=='' || $row['photo']=='!/man.png')
      echo '<img src="/images/man.png">';
   else {
      if(substr($row['photo'],0,1)=='!')
        echo '<img src="'.str_replace('!','http://',$row['photo']).'">';
      else
        echo '<img src="/content/'.$row['photo'].'">';
   }
   echo '<a href="#" onclick="showResume('.$row['id'].')">'.$row['firstname'].' '.$row['lastname'].'</a><div class="scont">'.$row['firstname'].' '.$row['lastname'];
   echo '<div class="productRate" style="margin: 0 auto"><div style="width: '.$row['avg_rating'].'%"></div></div> </div></li>';
//'<div class="rating"></div></div></li>';
}

?>
        </ul>
				<div class="clearfix"></div>
				<a id="prev" class="prev" href="#">&lt;</a>
				<a id="next" class="next" href="#">&gt;</a>
				<div id="pager" class="pager"></div>
     </div>


<form id="form" method="post">
<input type="hidden" id="vid" name="vid">
<?php echo CHtml::submitButton('submit',array("style"=>"display:none", "id"=>"btn_submit")); ?>
</form>
