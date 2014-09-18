<?php

Abstract Class baseController {

/*
 * @registry object
 */
protected $registry;

function __construct($registry) {
	$this->registry = $registry;
        session_start();
}

/**
 * @all controllers must contain an index method
 */
abstract function index();
}

?>
