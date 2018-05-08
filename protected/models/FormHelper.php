<?php
/** ............ FormHelper..............
 */
class FormHelper {

  public static function TextField($name, $val, $label, $placeholder='', $class='', $style='', $ico='', $isError=0) {
    $cl = '';
    $st = '';
    $ph = '';
    $ic = '';
    if(strlen($class)>0) $cl = ' class="'.$class.'"';
    if(strlen($style)>0) $st = ' style="'.$style.'"';
    if(strlen($placeholder)>0) $ph = ' placeholder="'.$placeholder.'"';
    if(strlen($ico)>0) $ic = '<span class="add-on"><i class="'.$ico.'"></i></span>';
    if($isError==1) {
      $label.=' <span id="'.$name.'_err" class="err badge badge-important">!</span>';
    }

    return '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append">
			        	<input type="text" name="'.$name.'" id="'.$name.'" '.$ph.$cl.$st.' value="'.$val.'" >'.$ic.'
			        </div>
		        </div>';
  }


  public static function TextArea($name, $val, $label, $placeholder='', $class='', $style='', $isError=0) {
    $cl = '';
    $st = '';
    $ph = '';
    if(strlen($class)>0) $cl = ' class="'.$class.'"';
    if(strlen($style)>0) $st = ' style="'.$style.'"';
    if(strlen($placeholder)>0) $ph = ' placeholder="'.$placeholder.'"';
    if($isError==1) {
      $label.=' <span id="'.$name.'_err" class="err badge badge-important">!</span>';
    }

    return '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append">
			        	<textarea name="'.$name.'" id="'.$name.'" '.$ph.$cl.$st.'>'.$val.'</textarea>
			        </div>
		        </div>';
  }


  public static function Hidden($name, $val) {
    return '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$val.'">';
  }

  public static function SubmitPanel() {
    return '<div class="modal-footer">
			<a href="'.Yii::app()->homeUrl.'" class="btn btn-inverse" >Отмена</a>
      <input type="submit" id="btn_submit" value="Сохранить" class="btn btn-primary">
    </div>';
  }


  public static function ListRadioDB($name, $label, $table_name, $isError=0) {
      if($isError==1) {
            $label.=' <span id="'.$name.'_err" class="err badge badge-important">!</span>';
      }

      $res = Yii::app()->db->createCommand()
          ->select('key, name')
          ->from($table_name)
          ->order('key')
          ->queryAll();

      $ret = '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append">';
      foreach($res as $r) {
          $ret.='<label class="radio" name="lb_'.$name.'" id="lb_'.$name.'_'.$r['key'].'">'.$r['name'].'<input type="radio" value="'.$r['key'].'" name="'.$name.'"  id="'.$name.'_'.$r['key'].'"/></label>';
      }

	  $ret.='</div></div>';
      return $ret;
  }


  public static function ListRadioMemo($name, $label, $arr, $isError=0) {
      if($isError==1) {
            $label.=' <span id="'.$name.'_err" class="err badge badge-important">!</span>';
      }

      $ret = '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append">';
      foreach($arr as $key => $value) {
            $ret.='<label class="radio" name="lb_'.$name.'" id="lb_'.$name.'_'.$key.'">'.$value.'<input type="radio" value="'.$key.'" name="'.$name.'" id="'.$name.'_'.$key.'" /></label>';
      }

      $ret.='</div></div>';
      return $ret;
  }

    public static function ListCheckBoxDB($name, $label, $table_name, $isError=0) {
        if($isError==1) {
            $label.=' <span id="'.$name.'_err" class="err badge badge-important">!</span>';
        }

        $res = Yii::app()->db->createCommand()
            ->select('key, name')
            ->from($table_name)
            ->order('key')
            ->queryAll();

        $ret = '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append">';
        foreach($res as $r) {
            $ret.='<label class="checkbox" name="lb_'.$name.'" id="lb_'.$name.'_'.$r['key'].'">'.$r['name'].'<input type="checkbox" value="'.$r['key'].'" name="'.$name.'"  id="'.$name.'_'.$r['key'].'"/></label>';
        }

        $ret.='</div></div>';
        return $ret;
    }
    
    public static function ListCheckBoxDBMetro($name, $label, $id_city, $isError=0) {
        if($isError==1) {
            $label.=' <span id="'.$name.'_err" class="err badge badge-important">!</span>';
        }

        $res = Yii::app()->db->createCommand()
            ->select('id_metro, station_name')
            ->from('metro')
            ->order('id_metro')
            ->where('id_city=:id_city', array(':id_city'=>$id_city))
            ->queryAll();

        $ret = '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append" id="metro_lst">';
        foreach($res as $r) {
            $ret.='<label class="checkbox">'.$r['station_name'].'<input type="checkbox" value="'.$r['id_metro'].'" name="'.$name.'"  id="'.$name.'_'.$r['id_metro'].'"/></label>';
        }

        $ret.='</div></div>';
        return $ret;
    }
    
    
    public static function ListCheckBoxMetro($label) {
        $ret = '<div class="control-group">
			        <label class="control-label">'.$label.'</label>
			        <div class="controls input-append" id="metro_lst">';
        $ret.='</div></div>';
        return $ret;    
    }

/**
 *  ShowFieldTextField  - отображает поле
 *
 **/
  public static function ShowField($name, $label, $class='', $style='') {
    $cl = '';
    $st = '';

    if(strlen($class)>0) $cl = ' class="'.$class.'"';
    if(strlen($style)>0) $st = ' style="'.$style.'"';

    return '<div class="row">
              <div class="span3">
			         <label>'.$label.'</label>
              </div>
			        <div class="span4">
			        	<div id="'.$name.'" '.$st.' ><div '.$cl.'></div></div>
			        </div>
		        </div>';
  }


  public static function ImageUpload($name, $val, $label, $isShowImage=1) {
    $img = '';
    if($isShowImage==1) {
        $img = '<div class="control-group">
      <label class="control-label"></label>
      <div class="controls input-append" id="mlogo">
          <img src="/content/'.$val.'">
      </div></div>';
    }
    return $img.'<div class="control-group">
      <label class="control-label">'.$label.'</label>
      <div class="controls input-append">
        <input type="text" name="'.$name.'" id="'.$name.'" value="'.$val.'">
        <input id="fileToUpload" type="file" name="fileToUpload" style="display:none" onchange="ajaxFileUpload()"/>
        <img id="loading" src="'.Yii::app()->homeUrl.'images/loading.gif" style="display:none;">
        <button class="btn btn-success" id="buttonUpload" onclick="return Upload();" title="Загрузить">Загрузить</button>
      </div>
    </div>';
  }


  public static function ImageUploadScript(){
      return '<script>
      function Upload()
      {
        $("input[type=\'file\'").trigger("click");
        return false;
      }

    	function ajaxFileUpload()
    	{
        if($("#fileToUpload").val()=="")
        {
           $("input[type=\'file\'").trigger("click");
           return;
        }

    		$("#loading")
    		.ajaxStart(function(){
    			$(this).show();
    		})
    		.ajaxComplete(function(){
    			$(this).hide();
    		});

    		$.ajaxFileUpload
    		(
    			{
    				url:"'.Yii::app()->homeUrl.'uploads/doajaxfileupload.php",
    				secureuri:false,
    				fileElementId:"fileToUpload",
    				dataType: "json",
    				data:{name:"logan", id:"id"},
    				success: function (data, status)
    				{
    					if(typeof(data.error) != "undefined")
    					{
    						if(data.error != "")
    						{
    							alert(data.error);
    						}else
    						{
                  var im = \'<img src="/content/\'+data.name+\'">\';
                  $("#mlogo").html(im);
                  $("#logo_filename").val(data.name);
                  $("#photo_name").val(data.name);
    						}
    					}
    				},
    				error: function (data, status, e)
    				{
    					alert(e);
    				}
    			}
    		);

    		return false;
    	}
      </script>';
  }
}
?>