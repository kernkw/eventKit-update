/* ==========================================================================
 * SEARCH_CONTROLLER.JS
 * ==========================================================================
 * Provides the download action for the search results page.
 */

App.SearchController = Ember.Controller.extend({
    actions: {
        downloadCSV: function() {
            var params = {
                text: decodeURIComponent(this.get('model.query')),
                query: 'wildcard',
                csv: true
            };
            window.location = "api/search.php?" + $.param(params);
        }
    }
})