<?php
if($this->model){
    echo CHtml::activeTextArea($this->model, $this->field, $this->htmlOptions);
}else{
    echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
}
?>