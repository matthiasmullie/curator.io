<?php

/**
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsDelete extends CuratorBaseAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->validateUser(true);
		$this->validateCollection();
		$this->collection->delete();
		$this->redirect($this->url->buildUrl('index', null, null, array('report' => 'deleted', 'var' => $this->collection->name)));
	}
}
