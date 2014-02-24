<?php
if (!defined('_IN_YELAGO')) die();

class User
{
	static $_user=null;
	
	public $_facebook;
	
	private $_fb_id;
	private $_fb_access_token;
	private $_fb_name;
	private $_fb_first_name;
	private $_fb_middle_name; //-> pas les infos niji
	private $_fb_last_name;
	private $_fb_link;
	private $_fb_username;
	private $_fb_gender;
	private $_fb_timezone;
	private $_fb_locale;
	private $_fb_languages; // serialized
	private $_fb_verified;
	private $_fb_updated_time;
    private $_fb_email;
	private $_fb_access_token_validity;
	private $_fb_is_app_user;
	private $_fb_devices; //ok - serialized
	private $_fb_likes; //serialized
	
	// champs oubliés (et puis pas oublié d'abord, mais plutot pas encore utilisés... mauvaise langue!)
	private $_fb_favorites_athletes;
	private $_fb_favorites_teams;
	private $_fb_inspirational_people;
	private $_fb_sports;
	
	public function get_instance()
	{
		if (self::$_user===null)
		{
			self::$_user = new User();
			require_once(_LIBRARIES.'/facebook/facebook.php');
			$params = array(
				'appId'=>_YEL_FACEBOOK_APP_ID,
				'secret'=>_YEL_FACEBOOK_APP_SECRET
			);
			self::$_user->_facebook = new Facebook($params);
		}
		return self::$_user;
	}	
	
	/***GETTERS***/
	
	public function get_hello()
	{
		return 'hello';
	}
	
	public function get_user_fb_id()
	{
		//return self::$_user->_fb_id;
        return $this->_fb_id;
	}
	
	public function get_user_access_token()
	{
		//return self::$_user->_fb_access_token;
		return $this->_fb_access_token;
	}
	
	//////// VAL ////////// 
	//edit:pudel - en fait a terme on recup de la db plutot que de l'obj
	// J'adore le php! 
	public function get_user_infos()
	{
		/*
		return $user_infos = array(
			'fb_name' => self::$_user->_fb_name,
			'fb_first_name' => self::$_user->_fb_first_name,
		 	'fb_blabla' => self::trop chiant à lister
		 	....
		 	....
			);
		               O  O
		  				||
		  				\/
		*/	
					
		$user_infos = array('_fb_id', '_fb_access_token', '_fb_name', '_fb_first_name', '_fb_email');
		foreach ($user_infos as $value)
		{
			$array_user_infos["$value"] = self::$_user->$value;
		}
		 return $array_user_infos; // pas bon, faut stocker le tab dans l'objet puis faire un $this->...
	}
	//*/ 
	
	
	
	public function get_token_validity()
	{
		return $this->_fb_access_token_validity['data'];
	}
	
	public function get_languages()
	{
		return unserialize($this->_fb_languages);
	}
	public function get_user_likes()
	{
		return $this->_fb_likes;
	}
	
	/***SETTERS***/
	
	public function _set_user_from_token($access_token)
	{
		//token validity check
		$core = Core::get_instance();
		$this->_fb_access_token_validity = $core->debug_access_token($access_token);
		
		$params = array(
		    'method' => 'GET',
		    'path' => '/me',
		    'access_token' => $access_token
		);
		try
		{
			$graph_result = self::$_user->_facebook->api('/me','GET',array('access_token'=>$access_token));
			if(isset($graph_result['id']))
			{
                //sets the extended access_token
				self::$_user->_set_extended_access_token($access_token);
				self::$_user->_set_is_app_user();
                
                $this->_fb_id = $graph_result['id']; 
				$this->_fb_name = $graph_result['name'];
				$this->_fb_first_name = $graph_result['first_name'];
				$this->_fb_middle_name = $graph_result['middle_name'];
				$this->_fb_last_name = $graph_result['last_name'];
				$this->_fb_link = $graph_result['link'];
				$this->_fb_username = $graph_result['username'];
				$this->_fb_gender = $graph_result['gender'];
				$this->_fb_timezone = $graph_result['timezone'];
				$this->_fb_locale = $graph_result['locale'];
				$this->_fb_languages = serialize($graph_result['languages']);
				$this->_fb_verified = $graph_result['verified'];
				$this->_fb_updated_time = $graph_result['updated_time'];
                $this->_fb_email = $graph_result['email'];
				
				$this->_fb_devices = serialize(self::$_user->_facebook->api('/me?fields=devices','GET',array('access_token'=>$access_token)));
				
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(FacebookApiException $fae)
		{
			return $fae->getMessage();
		}
	}

	private function _set_extended_access_token($access_token)
	{
		self::$_user->_facebook->setAccessToken($access_token);
		self::$_user->_facebook->setExtendedAccessToken();
		self::$_user->_fb_access_token = self::$_user->_facebook->getAccessToken();
	}
    
	private function _set_is_app_user($fb_id)
	{
		if(!isset($fb_id))
		{
			$app_install = self::$_user->_facebook->api('/me?fields=installed','GET');
		}
		else
		{
			$app_install = self::$_user->_facebook->api('/'.$fb_id.'?fields=installed','GET');
		}
		if ($app_install['installed'])
		{
			$this->_fb_is_app_user = true;
		}
		else 
		{
			$this->_fb_is_app_user = false;
		}
	}
	
	public function _set_user_likes($fb_id,$access_token,$limit,$after)
	{
		try
		{
			if (!isset($after))
			{
				$this->_fb_likes = self::$_user->_facebook->api('/'.$fb_id.'/likes?limit='.$limit,'GET',array('access_token'=>$access_token));
			}
			else
			{
				$this->_fb_likes = self::$_user->_facebook->api('/'.$fb_id.'/likes?limit='.$limit.'&after='.$after,'GET',array('access_token'=>$access_token));
			}
			$data['fb_likes'] = serialize($this->_fb_likes);
			
			$mysql = MySQL::get_instance();
			
			$db_id = $mysql->getAll("SELECT id FROM user WHERE fb_id=?",array($this->_fb_id));
			
			$mysql->AutoExecute('user',$data,'UPDATE','id = '.$db_id);
			
			return true;
		}
		catch(FacebookApiException $fae)
		{
			return $fae->getMessage();
		}
	}
	
    public function _db_insert_user()
    {
        $data['fb_id'] = $this->_fb_id;
        $data['fb_access_token'] = $this->_fb_access_token;
        $data['fb_name'] = $this->_fb_name;
        $data['fb_first_name'] = $this->_fb_first_name;
        $data['fb_middle_name'] = $this->_fb_middle_name;
        $data['fb_last_name'] = $this->_fb_last_name;
        $data['fb_link'] = $this->_fb_link;
        $data['fb_username'] = $this->_fb_username;
        $data['fb_gender'] = $this->_fb_gender;
        $data['fb_timezone'] = $this->_fb_timezone;
        $data['fb_locale'] = $this->_fb_locale;
        $data['fb_languages'] = $this->_fb_languages;
        $data['fb_verified'] = $this->_fb_verified;
        $data['fb_updated_time'] = $this->_fb_updated_time;
        $data['fb_email'] = $this->_fb_email;
		
		$data['fb_devices'] = $this->_fb_devices;
		$data['is_app_user'] = $this->_fb_is_app_user;
        
        $mysql = MySQL::get_instance();
        
        $presence = $mysql->getAll("SELECT id,fb_id, status FROM user WHERE fb_id=?",array($this->_fb_id));
        if ($presence)
        {
			if(!$presence[0]['status']>1)
			{
				$data['status'] = 1;
			}
        	$data['date_update'] = date('Y-m-d H:i:s');
            $mysql->AutoExecute('user',$data,'UPDATE','id = '.$presence[0]['id']);
			return 'updated user with id : '.$presence[0]['id'];
        }
        else
        {
        	$data['status'] = 1;
        	$data['date_insert'] = date('Y-m-d H:i:s');
            $mysql->AutoExecute('user',$data,'INSERT');
			$insert_id = $mysql->Insert_ID();
			return 'inserted user with id : '.$insert_id;
        }
               
        
    }
    
}
?>