<?php

/**
 * Show the category detail.
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
class CollectionsCategory extends CuratorBaseAction
{
	/**
	 * @var array
	 */
	private $category;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->loadData();
		$this->parse();
		$this->display();
	}

	private function loadData()
	{
		$this->category = CollectionsHelper::getCategory($this->url->getParameter(1));

		if(empty($this->category))
		{
			$this->redirect($this->url->buildUrl('index', 'error') . '?code=404&message=InvalidCategory');
		}
	}

	/**
	 * Parse template info.
	 */
	private function parse()
	{
		$this->tpl->assign('title', $this->category['name']);
		$this->tpl->assign('pageTitle', $this->category['name']);
		$this->tpl->assign('category', $this->category);

		$collections = CollectionsHelper::getByCategoryId($this->category['id']);
		if(!empty($collections))
		{
			foreach($collections as &$item) $item = $item->toArray();
			$this->tpl->assign('collections', $collections);
		}

		$this->parseReports();
	}
}
