<?php
    class plugin 
	{
		private $events = array();
		private $return = array();
		private $hooks = array();
		private $plugins = array();
		
		function __construct()
		{
			
		}
                public function page($id)
                {
                    $this->page = $id;
                }
		public function get_event_count($eventName)
		{
			return count($this->events[$eventName]);
		}
		/**
		 * Attach Hook - Allows a plugin author to create a function and attach that function to fire when that hook is called using trigger_hook().
		 * @return void
		 * @author Cody 
		 */
		public function attach_hook($eventName, $callback) {
        if (!isset($this->events[$eventName])) {
        	$this->events[$eventName] = array();}
			$this->events[$eventName][] = $callback;
			//return $callback;
    	}
		/**
		 * Trigger Hook - Allows a plugin author to attach a function to fire at this point in the script using attach_hook().
		 * @return void
		 * @author Cody 
		 */
		public function trigger_hook($eventName, $data = null, $array = FALSE ) {
			$this->hooks[] = $eventName;
			//echo $eventName." ".count($this->events[$eventName])."\n";
			if(isset($this->events[$eventName]) )
			{
				$ret_data = FALSE;
				foreach ($this->events[$eventName] as $callback) {
					
					$func_data = call_user_func($callback, $data);
					
					if($array)
					{
						$ret_data[] .= $func_data;
					}
					else
					{
						$ret_data = $func_data;
					}
					//echo $callback.": ".print_r($ret_data)."</br>";
				}
				return $ret_data;
			}
			else 
			{
				if(!is_array($data))
				{
					return $data;
				}
				else if(isset($data['value']))
				{
					return $data['value'];		
				}
				else {
					return $data;
				}
			}
        	
		}
		
	function parse_content($type)
	{
		return "Type: ".$type;
	}
	function register($name)
	{
		$this->plugins[] .= $name;
	}
	public function set_setting($name, $key, $value)
	{
		$this->plugins[$name][$key]=$value;
	}
	public function get_setting($name, $key)
	{
		if(in_array($name, array_keys($this->plugins)))
		{
			return $this->plugins[$name][$key];
		}
		else {
			return FALSE;
		}
	}
		/**
		 * Create a new block type.
		 * @param typeName - Name the block
		 * @param config_HTML - the html codes that will be inserted into the block list when dropping a block of this type onto the page.
		 * @return void
		 * @author Cody 
		 */
		public function get_event_list()
		{
			return $this->events;
		}
		public function get_hooks()
		{
			return $this->hooks;
		}
		/**
		 * Check to see if the current user is an administrator of the website.
		 *
		 * @return Booleen
		 * @author  
		 */
		public function is_user_admin()
		{
			$db = new database;
			$db->select('is_admin')->from('users')->where("id", "=", $_SESSION['user_id'])->go(FALSE);
			if ($db->data['is_admin'] == TRUE)
			{
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>