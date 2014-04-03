/* ==========================================================================
 * DETAILED_SEARCH_RESULTS_CONTROLLER.JS
 * ==========================================================================
 * Provides the download as CSV action for the detailed search results page.
 */

 App.DetailedSearchResultsController = Ember.Controller.extend({
    actions: {
        downloadCSV: function() {
            var params = JSON.parse(decodeURIComponent(this.get('model.query')));
            params.csv = true;
            params.offset = 0;
            params.resultsPerPage = kResultsPerPage;
            window.location = "api/search.php?" + $.param(params);
        }
    }
 });