<?php 
/**
 * IYiiUpload A Software Interface for YiiUpload Source Providers
 *
 * @uses CWidget
 * @version 1.0 
 * @author Dmitry Derevyanko <derevyanko977@gmail.com>
 * 
 */
interface IYiiUpload {
	public function yiiupload_post($chat_id, $identity, $message, $data);
	public function yiiupload_list_posts($chat_id, $identity, $last_id, $data);
}
?>