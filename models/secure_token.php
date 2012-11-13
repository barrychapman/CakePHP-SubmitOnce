<?php
class SecureToken extends AppModel {
	
	var $name = 'SecureToken';
	
	
	
	
	function tokenUsed( $token, $uid ) {
		
		$found = $this->find( 'first', array( 'conditions' => array( 'SecureToken.token' => $token, 'SecureToken.user_id' => $uid, 'SecureToken.is_used' => 1 ) ) );
		
		return $found;
		
	}
	
	function tokenIsValid( $token, $uid ) {
		
		$found = $this->find( 'first', array( 'conditions' => array( 'SecureToken.token' => $token, 'SecureToken.user_id' => $uid, 'SecureToken.is_used' => 0 ) ) );
		
		return $found;
		
	}
	
	function tokenCreate( $uid ) {
		
		$exists = false;
		
		do {
			
			$token 		= Security::hash( time() . $uid, null, true );
			$exists 	= $this->find( 'first', array( 'conditions' => array( 'SecureToken.token' => $token ), 'contain' => false ) );
			
		} while ( $exists );
		
		
		$save	= array(
			'SecureToken' => array(
				'token' 	=> $token,
				'user_id'	=> $uid,
				'is_used'	=> 0
				)
			);
		
		if ( $this->save( $save ) ) {
			
			return $token;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function tokenMarkUsed( $token, $uid ) {
		
		
		if ( $found = $this->tokenIsValid( $token, $uid ) ) {
			
			$found['SecureToken']['is_used'] = 1;
			
			return $this->save( $found );
			
		} else {
			
			return false;
			
		}
		
		
		
	}
	
}
?>