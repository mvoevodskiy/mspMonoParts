mspMonoParts.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'mspmonoparts-panel-home',
            renderTo: 'mspmonoparts-panel-home-div'
        }]
    });
    mspMonoParts.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(mspMonoParts.page.Home, MODx.Component);
Ext.reg('mspmonoparts-page-home', mspMonoParts.page.Home);