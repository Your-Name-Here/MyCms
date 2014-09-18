<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Helper methods to aid in CMS specific tasks available to all scripts including
 * plugins.
 *
 * @author computer
 */
class CMS {
    
    private $registry;
    
    function __construct($registry) {
        $this->registry = $registry;
    }
    
    public function get_global_setting($setting)
    {
        return $this->registry->$setting;
    }
    public function set_global_setting($setting, $value)
    {
        $this->registry->$setting = $value;
    }
}
?>