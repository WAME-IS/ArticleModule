<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface IArticleFormContainerFactory
{
	/** @return ArticleFormContainer */
	public function create();
}


class ArticleFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();

		$form->addAutocomplete('value', _('Article'))
                ->setAttribute('placeholder', _('Begin typing the article title'))
                ->setSource('/api/v1/article-search')
                ->setColumns(['langs.title'])
                ->setSelect('a.id, langs.title')
                ->setRequired(_('You must select article'));
		
		$form->addText('alternative_title', _('Alternative title'));
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();

		$form['value']->setDefaultValue($object->menuEntity->getValue());
		$form['alternative_title']->setDefaultValue($object->menuEntity->getAlternativeTitle());
	}

}
