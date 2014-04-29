<?php
class pages extends controller { 
	function help()
	{
        $this->loadView( "includes/header" );
		$this->loadView( "pages/help" );
        $this->loadView( "includes/footer" );
	}

	function error()
	{
        $this->loadView( "includes/header" );
		$this->loadView( "pages/error" );
        $this->loadView( "includes/footer" );
	}
}
