<?php
class ProjectStaff extends Project {
	/**
	*	@param $type number - user`s type
	*	@param $arr array - ['photo','isman','logo']
	* @param $size string - ['small', 'medium', 'big']
	*	@return string
	*/
	public static function getPhoto($type, $arr, $size) {
		$src = DS . 
			($type==2 ? MainConfig::$PATH_APPLIC_LOGO : MainConfig::$PATH_EMPL_LOGO)
			. DS;
		if($type==2) { // applicant
			if($arr['photo'])
				switch ($size) {
					case 'small': $src .= $arr['photo'] . '100.jpg'; break;
					case 'medium': $src .= $arr['photo'] . '400.jpg'; break;
					case 'big': $src .= $arr['photo'] . '000.jpg'; break;
				}
			else
				$src .= $arr['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F;
		}
		if($type==3) { // employer
			if($arr['logo'])
				switch ($size) {
					case 'small': $src .= $arr['logo'] . '100.jpg'; break;
					case 'medium': $src .= $arr['logo'] . '400.jpg'; break;
					case 'big': $src .= $arr['logo'] . '000.jpg'; break;
				}
			else
				$src .= MainConfig::$DEF_LOGO;
		}
		return $src;
	}
}