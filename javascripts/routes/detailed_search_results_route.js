/* ==========================================================================
 * DETAILED_SEARCH_RESULTS_ROUTE.JS
 * ==========================================================================
 * Provides the model for the detailed search page.
 */

App.DetailedSearchResultsRoute = Ember.Route.extend({
    model: function(params) {
        var query = JSON.parse(decodeURIComponent(params.query)),
            page = params.page;
        query.resultsPerPage = kResultsPerPage;
        query.offset = page;
        var url = "api/search.php?" + $.param(query);

        return Ember.$.getJSON(url).then(function(response) {
            response.page = page;
            response.query = JSON.stringify(query);
            return response;
        });
    }
});