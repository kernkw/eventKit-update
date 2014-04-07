/* ==========================================================================
 * SETTINGS_ROUTE.JS
 * ==========================================================================
 * Provides the model for the settings page.
 */

App.SettingsRoute = Ember.Route.extend({
    model: function() {
        var model = {
            autoDeleteValues: [{
                title: "1 month",
                value: 1
            }, {
                title: "2 months",
                value: 2
            }, {
                title: "3 months",
                value: 3
            }, {
                title: "4 months",
                value: 4
            }, {
                title: "5 months",
                value: 5
            }, {
                title: "6 months",
                value: 6
            }, {
                title: "7 months",
                value: 7
            }, {
                title: "8 months",
                value: 8
            }, {
                title: "9 months",
                value: 9
            }, {
                title: "10 months",
                value: 10
            }, {
                title: "11 months",
                value: 11
            }, {
                title: "12 months",
                value: 12
            }],
            selectedAutoDeleteValue: 6
        },

            params = {
                query: 'settings'
            };

        return Ember.$.getJSON('api/search.php?' + $.param(params)).then(function(results) {
            if (results.data.length) {
                model.data = results.data;
                results.data.forEach(function(item) {
                    if (item.setting = "autodelete") {
                        model.selectedAutoDeleteValue = item.value * 1;
                    }
                });
            }
            return model;
        });
    }
});