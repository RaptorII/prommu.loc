<h3><?=$this->pageTitle?></h3>
<div class="row">
	<div class="col-xs-12">
		<?
			$this->widget(
					'ext.elFinder.ElFinderWidget', 
					array('connectorRoute' => '/elfinder/connector')
				);
		?>	
	</div>
</div>
<style type="text/css">
	.el-finder-dialog{
		z-index: 900;
		margin-left: 50%;
		top: 100px!important;
		left: -300px!important;	
	}
	.el-finder-dialog.el-finder-dialog-info{
		left: -190px!important;	
	}
</style>