var mspMonoParts = function (config) {
    config = config || {};
    mspMonoParts.superclass.constructor.call(this, config);
};
Ext.extend(mspMonoParts, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('mspmonoparts', mspMonoParts);

mspMonoParts = new mspMonoParts();