<?php

class SubmitOnceHelper extends FormHelper
{

    var $initialized = false;
    var $helpers = array(
        'Session',
		'Html'
    );
	
	var $Session;
	
	function create($model = null, $options = array()) {
		
		echo( parent::create( $model, $options ) );

		$out = null;
		
		echo( $this->hidden( '_SubmitOnce' ) );
		
			
		
	}
	
}