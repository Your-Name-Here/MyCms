<?php
class user {
    var $id;
    var $username;
    var $energy;
    var $name;
    var $db;
    var $primaryClass;
    var $primaryLvl;
    var $earnings;
    var $XP = array();
    var $levels = array();
    
    function __construct($identifier="") {
        $this->db = new db();
        if(strlen($identifier)<5) //Must be an ID
        {
            $this->db->select()->from('users')->where('id', '=', $identifier)->limit(1)->go();
            $this->id = $this->db->data['id'];
        }
        else // Must be a SLID
        {
            $this->db->select()->from('users')->where('slid', '=', $identifier)->limit(1)->go();
            $this->id = $this->db->data['id'];
        }
        if ($this->db->count > 0)
        {
            $this->get_all_info($this->id);
        }
        else
        {
            
        }
    }
    public function login($username,$password) // TODO: Not working 
    {
        $db=$this->db;
        $db->select()->from('users')->where('sl_name','=',$username)->go();
        if($db->count>0 && sha1($password)==$db->data['password'])
        {
            $this->id = $db->data['id'];
            $this->update_activity();
            $username= explode(' ', $db->data['sl_name']);
            $_SESSION['user']['id']=$db->data['id'];
            $_SESSION['user']['slid']=$db->data['slid'];
            $_SESSION['user']['name']=$username[0];
            $this->user=$_SESSION['user'];
            //header("location: ../Profile/View/".$username[0].'/');
            return $username[0];
        }
        else
        {
            return FALSE;
        }
    }
    public function register()
    {
        
    }
    public function logout()
    {
        session_destroy();
	unset($_SESSION);
	header('location: ../');
    }
    public function exists($username)
    {
        $this->db->select()->from('users')->where("sl_name",'=',$username)->limit(1)->go();
        if($this->db->data>0)
        {
            return TRUE;
        }
        return FALSE;
    }
    public function get_all_info()
    {
        $this->db->select()->from('users')->where('id','=',$this->id)->limit(1)->go();
        $return = $this->db->data;
        $this->energy = $return['energy'];
        $this->activity = $return['activity'];
        $this->username = $return['sl_name'];
        $this->name = $return['login_name'];
        $this->earnings = $return['winnings'];
        $this->slid = $return['slid'];
        $this->XP['mining'] += $return['miner_xp'];
        $this->XP['crafting'] += $return['crafter_xp'];
        $this->XP['extracting'] += $return['extract_xp'];
        $this->levels['mining'] += $this->calc_lvl($this->XP['mining']);
        $this->levels['crafting'] += $this->calc_lvl($this->XP['crafting'], 'crafter');
        $this->levels['extracting'] += $this->calc_lvl($this->XP['extracting'], 'extract');
        $this->db->select()->from('user_inv')->where('user_id','=',$this->id)->limit(1)->go();
        $this->XP['searching'] = $this->db->data['search_xp'];
        $this->get_primary_class();
        $return['inventory']=$this->db->data;
        return $return;
    }
    public function get_credentials($id)
    {
        
    }
    private function calc_lvl($xp,$class = "miner")
	{
		$this->db->select()->from($class."_levels")->go();
		$last_lvl = 0;
		$counter=0;
		while($counter < $this->db->count)
		{
			$exp = mysql_result($this->db->result, $counter, "exp");
			if ($xp > $exp)
			{
				$last_lvl++;
			}
			else {
				return $last_lvl;
			}
			$counter++;
		}
	}
        public function get_title($level)
        {
            $this->db->select("title")->from('title')->where('id','=', $level)->go();
            return $this->db->data['title'];
        }
        private function doublemax($mylist){ 
            $maxvalue=max($mylist); 
            while(list($key,$value)=each($mylist)){ 
                if($value==$maxvalue)$maxindex=$key; 
            }
            $this->primaryLvl = $maxvalue;
            $this->primaryClass = $maxindex;
        }
        public function get_primary_class()
        {
            $classes = array("Miner"=>$this->levels['mining'],"Crafter"=>$this->levels['crafting'],"Extractor"=>$this->levels['extracting']);
            $max = $this->doublemax($classes);
        }
        public function update_activity()
        {
            $this->db->update("users")->set("activity",time())->where('id','=', $this->id)->go();
        }
        
}
?>