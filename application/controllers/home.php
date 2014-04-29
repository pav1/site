<?php
class home extends controller {

	function index()
	{
        $this->loadView( "includes/header" );
        $this->loadView( "home" );
        $this->loadView( "includes/footer" );

	}
}
