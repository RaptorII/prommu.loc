<?php
/**
 * Created by Vlasakh
 * Date: 09.09.16
 * Time: 17:41
 */


class ProfileFactory
{
//    protected $id; // id user



    /**
     * @param $inProps array (id, type)
     * @return UserProfile
     */
    public function makeProfile($inProps)
    {
        $props = (object)$inProps;

        if( $props->id )
        {
            // если id текущего пользователя - его и возвращаем
            if( Share::$UserProfile instanceof UserProfile )
                if( $props->id == Share::$UserProfile->id ) return Share::$UserProfile;


            // если тип не определен
//            if( !isset($props->type) )
            if( !$props->type )
            {
                list($props->idProfile, $props->type) = array_values(UserProfile::getUserType($props->id));
//                $res = UserProfile::getUserType($props->id);
//                $props->idProfile = $res['id'];
//                $props->type = $res['type'];
            } // endif

            if( $props->type == 2 ) $Profile = new UserProfileApplic($props);
            elseif( $props->type == 3 ) $Profile = new UserProfileEmpl($props);
            else $Profile = (object)array('error' => -101, 'message' => 'Нет данных для такого пользователя');

            return $Profile;
        }
        else
        {
            return new UserProfileGuest(array('id' => 0, 'type' => 0));
        } // endif
    }
}
