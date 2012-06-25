<?php

/**
 * AjaxExampleGet
 *
 * @package		items
 * @subpackage	update_likes
 *
 * @author 		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		1.0
 */
class AjaxItemsUpdateLikes extends AjaxBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// get the term
		$id = SpoonFilter::getPostValue('id', null, '');
		$direction = SpoonFilter::getPostValue('direction', array('up', 'down'), '');

		if($id == '' || $direction == '')
		{
			SpoonHTTP::setHeadersByCode(400);

			// return
			$response['code'] = 400;
			$response['message'] = 'invalid parameters';
		}

		else
		{
			$item = Item::get($id);

			if($item === false)
			{
				// return
				$response['code'] = 400;
				$response['message'] = 'invalid parameters';
			}
			else
			{
				if($direction == 'up') $item->like_count = $item->like_count + 1;
				if($direction == 'down') $item->like_count = $item->like_count - 1;

				$item->save();
			}

			// return
			$response['code'] = 200;
			$response['message'] = 'ok';
		}

		// output
		echo json_encode($response);
		exit;
	}
}
