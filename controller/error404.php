<?php

Class error404Controller Extends baseController {
var $user = array();
    public function __construct($registry) {
        parent::__construct($registry);
        if($_SESSION['user'])
        {
            $this->user=$_SESSION['user'];
            $this->registry->template->assign("logged",TRUE);
            $this->registry->template->assign("username",$_SESSION['user']['name']);
        }
        else {
            $this->registry->template->assign("logged",FALSE);
            
        }
        $this->registry->template->assign("base_url",$this->registry->config['Site']['url']);
    }
public function index() 
{
        $this->registry->template->assign("content","We counld not find the page you're looking for");
        $this->registry->template->assign("arrow", 1);
        $this->registry->template->assign("header", "404 Error! Page Down!");
        $this->registry->template->assign("title", "Mining Grid :: Page Down!");
	$this->registry->template->draw('vintage/page');
}


}
?>
