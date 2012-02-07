<?php
use com\paypal\ipn\sample\SampleModel;
use com\paypal\ipn\sample\IPNToAC2DMHandler;
use com\paypal\ipn\InstantPaymentNotification;

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
	$ipn = new InstantPaymentNotification();
	$ipn->setIPNHandler( new IPNToAC2DMHandler( new SampleModel() ) );
	$ipn->listen( $_POST );
}