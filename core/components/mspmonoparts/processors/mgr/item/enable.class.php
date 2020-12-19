<?php

class mspMonoPartsItemEnableProcessor extends modObjectProcessor
{
    public $objectType = 'mspMonoPartsItem';
    public $classKey = 'mspMonoPartsItem';
    public $languageTopics = array('mspmonoparts');
    //public $permission = 'save';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mspmonoparts_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var mspMonoPartsItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('mspmonoparts_item_err_nf'));
            }

            $object->set('active', true);
            $object->save();
        }

        return $this->success();
    }

}

return 'mspMonoPartsItemEnableProcessor';
