'use strict'
	var nicEditorParams = {
				maxHeight: 200, 
				buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul'] 
			},
			nicEditorDesc = new nicEditor(nicEditorParams);

jQuery(function($){
	var myCodeMirror = initMirror();
	// only for author
	if($('#description-edit').is('*'))
	{
		nicEditorDesc.addInstance('description-edit');
		nicEditorDesc.setPanel('description-edit-panel');
	}
	//
	function initMirror()
	{
    return CodeMirror.fromTextArea(
      document.getElementById('transform-code'),
      {
        lineNumbers: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 2
      }
    );
	}
	// tags
	$('.tags_block').on('input','input',function(){
		this.value=this.value.replace(/\s+/gi,'');
	});
	$('.tags_block').on('blur','input',function(){
		if(this.value.length<3)
			this.value = '';
	});
	$('.tags_block .btn').click(function(){
		$('.tags_block').append('<input type="text" name="tags[]" class="form-control">');
	});
});