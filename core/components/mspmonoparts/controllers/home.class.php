<?php

/**
 * The home manager controller for mspMonoParts.
 *
 */
class mspMonoPartsHomeManagerController extends modExtraManagerController
{
    /** @var mspMonoParts $mspMonoParts */
    public $mspMonoParts;


    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('mspmonoparts_core_path', null,
                $this->modx->getOption('core_path') . 'components/mspmonoparts/') . 'model/mspmonoparts/';
        $this->mspMonoParts = $this->modx->getService('mspmonoparts', 'mspMonoParts', $path);
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('mspmonoparts:default');
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('mspmonoparts');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->mspMonoParts->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->mspMonoParts->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/mspmonoparts.js');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->mspMonoParts->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        mspMonoParts.config = ' . json_encode($this->mspMonoParts->config) . ';
        mspMonoParts.config.connector_url = "' . $this->mspMonoParts->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "mspmonoparts-page-home"});
        });
        </script>
        ');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->mspMonoParts->config['templatesPath'] . 'home.tpl';
    }
}