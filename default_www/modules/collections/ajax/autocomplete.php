<?php

/**
 * Autocomplete for the collection names
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class AjaxCollectionsAutocomplete extends AjaxBaseAction
{
	public function execute()
	{
		// get the term
		$term = SpoonFilter::getPostValue('term', null, '');

		if($term == '')
		{
			SpoonHTTP::setHeadersByCode(400);

			// return
			$response['code'] = 400;
			$response['message'] = SiteLocale::err('FieldIsRequired');
		}

		else
		{
			// return
			$response['code'] = 200;
			$response['message'] = 'ok';
			$response['data'] = CollectionsHelper::getNamesForAutocomplete($term);
		}

		// output
		echo json_encode($response);
		exit;
	}
}
