<?php

Class indexController Extends baseController {

    public function __construct($registry) {
        parent::__construct($registry);
    }
public function index() {
        /*** set a template variable ***/
        $this->registry->template->assign("arrow", 1);
        $this->registry->template->assign("title", "MyCMS");
        $this->registry->template->assign("content", 'Thank you for choosing MyCMS. Installation Complete.');
	/*** load the index template ***/
        $this->registry->template->draw('Default');
}

}

?>
