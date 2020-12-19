<?php

class mspMonoPartsItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'mspMonoPartsItem';
    public $classKey = 'mspMonoPartsItem';
    public $languageTopics = array('mspmonoparts');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mspmonoparts_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
            $this->modx->error->addField('name', $this->modx->lexicon('mspmonoparts_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'mspMonoPartsItemCreateProcessor';