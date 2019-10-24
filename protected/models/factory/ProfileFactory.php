<?php
/**
 * Created by Vlasakh
 * Date: 09.09.16
 * Time: 17:41
 */


class ProfileFactory
{
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
        if(
          Share::$UserProfile instanceof UserProfile
          &&
          $props->id==Share::$UserProfile->id
        )
        {
          return Share::$UserProfile;
        }

        // если тип не определен
        if( !$props->type )
        {
          list($props->idProfile, $props->type) = array_values(UserProfile::getUserType($props->id));
        }

        if( Share::isApplicant($props->type) )
        {
          $Profile = new UserProfileApplic($props);
        }
        elseif( Share::isEmployer($props->type) )
        {
          $Profile = new UserProfileEmpl($props);
        }
        else
        {
          $Profile = (object)['error'=>-101, 'message'=>'Нет данных для такого пользователя'];
        }

        return $Profile;
      }
      else
      {
        return new UserProfileGuest(['id'=>0, 'type'=>0]);
      }
    }
}