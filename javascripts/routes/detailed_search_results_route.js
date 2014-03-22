/* ==========================================================================
 * DETAILED_SEARCH_RESULTS_ROUTE.JS
 * ==========================================================================
 * Provides the model for the detailed search page.
 */

App.DetailedSearchResultsRoute = Ember.Route.extend({
    model: function(params) {
        var query = JSON.parse(decodeURIComponent(params.query)),
            page = params.page,
            resultsPerPage = 10;
        query.resultsPerPage = resultsPerPage;
        var url = "api/search.php?" + $.param(query);

        return Ember.$.getJSON(url).then(function(response) {
            response.page = page;
            response.query = JSON.stringify(query);
            var allResults = response.data;
            response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
            return response;
        });
    }
});