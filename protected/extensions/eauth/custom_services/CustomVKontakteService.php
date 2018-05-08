<?php
/**
 * An example of extending the provider class.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

require_once dirname(dirname(__FILE__)) . '/services/VKontakteOAuthService.php';

class CustomVKontakteService extends VKontakteOAuthService {

	// protected $scope = 'friends';

	protected function fetchAttributes() {
		$info = (array)$this->makeSignedRequest('https://api.vk.com/method/users.get', array(
			'query' => array(
				'user_ids' => $this->uid,
				//'fields' => '', // uid, first_name and last_name is always available
				'fields' => 'nickname, sex, bdate, city, country, timezone, photo, photo_medium, photo_big, photo_rec, email',
				'v' => '5.74'
			),
		));

		$info = $info['response'][0];

		$this->attributes['id'] = $info->id;
		$this->attributes['name'] = $info->first_name . ' ' . $info->last_name;
		$this->attributes['url'] = 'http://vk.com/id' . $info->id;

		if (!empty($info->nickname)) {
			$this->attributes['username'] = $info->nickname;
		}
		else {
			$this->attributes['username'] = 'id' . $info->id;
		}

		$this->attributes['gender'] = $info->sex == 1 ? 'F' : 'M';

		$this->attributes['city'] = $info->city;
		$this->attributes['country'] = $info->country;

		$this->attributes['timezone'] = timezone_name_from_abbr('', $info->timezone * 3600, date('I'));
		;

		$this->attributes['photo'] = $info->photo;
		$this->attributes['email'] = $info->email;
		$this->attributes['photo_medium'] = $info->photo_medium;
		$this->attributes['photo_big'] = $info->photo_big;
		$this->attributes['photo_rec'] = $info->photo_rec;
	}
}
