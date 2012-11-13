<?php
class SubmitOnceComponent extends Object {
	
	var $name = 'SubmitOnce';
	
	var $Controller;
	var $Settings;
	var $SecureToken;
	
	var $token;
	
	var $components = array( 'Session', 'Auth', 'RequestHandler' );
	
	function initialize( &$controller, $settings = array() ) {
		
		$this->Controller 	= $controller;
		
		if ( isset( $this->Controller->Security->validatePost ) && $this->Controller->Security->validatePost ) {
			$this->Controller->Security->disabledFields = array_merge( $this->Controller->Security->disabledFields, array( '_SubmitOnce' ) );
		}
		
		$this->Settings   	= $settings;
		$this->SecureToken 	= ClassRegistry::init( 'SecureToken' );
		
	}
	
	function startup( &$controller ) {
		

		
	}
	
	function create() {
		
		if ( ! isset( $this->Controller->data[ $this->Settings['model'] ]['_SubmitOnce'] ) ) {
			
			$token	= $this->SecureToken->tokenCreate( $this->Auth->user('id') );
			
			$this->Session->write( "SecureToken.{$token}.redir", $this->Controller->referer() );
			
			$this->Controller->data[ $this->Settings['model'] ]['_SubmitOnce'] = $token;
		
		}
		
	}
	
	function process() {
		
		if ( $this->RequestHandler->isAjax() )
			return true;
		
		if ( ! $this->RequestHandler->isPost() ) {
			$token	= $this->SecureToken->tokenCreate( $this->Auth->user('id') );
			$this->Session->write( "SecureToken.{$token}.redir", $this->Controller->here );
			$this->Controller->data[ $this->Settings['model'] ]['_SubmitOnce'] = $token;
			return;
		}
		
		if ( isset( $this->Controller->data[ $this->Settings['model'] ]['_SubmitOnce'] ) )
			$token 	 = $this->Controller->data[ $this->Settings['model'] ]['_SubmitOnce'];
		else
			return;
		
		
		$this->token = $token;
		
		
		$checked = $this->SecureToken->tokenIsValid( $token, $this->Auth->user('id') );
		
		if ( $checked ) {
			
			$this->SecureToken->tokenMarkUsed( $token, $this->Auth->user('id') );
			
		} else {
			
			$this->Session->setFlash( $this->Settings['errmsg'], 'default', array( 'class' => 'failure' ) );
			$this->Controller->redirect( $this->Session->read( "SecureToken.{$token}.redir" ) );
			
		}
		
		
	}
	
	function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
		
		$token = $this->token;
		
		if ( $this->Session->check( 'SecureToken.overrideRedir' ) ) {
			
			$this->Session->delete( 'SecureToken.overrideRedir' );
			
		} elseif ( $this->Session->check( "SecureToken.{$token}.redir" ) ) {
			
			$this->Session->delete( "SecureToken.{$token}.redir" );
			$this->Session->write( 'SecureToken.isRedirect', true );
			$this->Session->delete( 'SecureToken.overrideRedir' );
			
		} else {
			
			$this->Session->write( 'SecureToken.isRedirect', false );
			$this->Session->delete( 'SecureToken.overrideRedir' );
			
		}
			
		
	}
		
}
?>