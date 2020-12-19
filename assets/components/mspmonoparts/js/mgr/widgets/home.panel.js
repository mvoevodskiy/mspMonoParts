mspMonoParts.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'mspmonoparts-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('mspmonoparts') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('mspmonoparts_items'),
                layout: 'anchor',
                items: [{
                    html: _('mspmonoparts_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'mspmonoparts-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    mspMonoParts.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(mspMonoParts.panel.Home, MODx.Panel);
Ext.reg('mspmonoparts-panel-home', mspMonoParts.panel.Home);
