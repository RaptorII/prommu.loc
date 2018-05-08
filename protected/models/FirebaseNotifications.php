<?php
/**
 * @author Dege
 */
class FirebaseNotifications extends Object
{

    public function getPush($props=[])
    {
        $apiKey = 'AAAAOoZQN40:APA91bEgi7ebdOYMEwl60gzbgqFCOxv3gvmiq9hdpl4lE1SLOeCHHHlRah0U5qEHroYznP3MHnm3Ilj-n7ilsf8Rd9J-oEDZYE_3vsFIvqq9XgZrLfL64MWFaaFUVPZ5aIrtfNo3Mt07';
        $apiUrl = 'https://fcm.googleapis.com/fcm/send';
        $timeout = 5;
        $sslVerifyHost = false;
        $sslVerifyPeer = false;
        $message = $props['message'];
        $datas = $props['datas'];
        $id = $props['id'];

        $sql = "SELECT r.push
            FROM push_mess r
            WHERE r.id = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $value) {
            foreach ($value as $key1 => $value1) {
            $resus[] = $value1;
            }
        }

         $body = [
            'to' => $resus,
            'notification' => $message,
            'data' => $datas,
        ];

        $headers = [
            "Authorization:key={$apiKey}",
            'Content-Type: application/json',
            'Expect: ',
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_SSL_VERIFYHOST => $this->sslVerifyHost,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerifyPeer,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FRESH_CONNECT  => false,
            CURLOPT_FORBID_REUSE   => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_POSTFIELDS     => json_encode($body),
        ]);
        $result = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($result , true);

        return $result;
    }

}