/* ==========================================================================
 * SEARCH_ROUTE.JS
 * ==========================================================================
 * Retrieves the results for the given query and passes them on as the model
 * for SearchController.
 */

App.SearchRoute = Ember.Route.extend({
	model: function(params) {
		var resultsPerPage = kResultsPerPage,
			page = params.page,
			query = {
				query: "wildcard",
				text: params.query,
				resultsPerPage: resultsPerPage,
				offset: page
			};
		var url = "api/search.php?" + $.param(query);

		return Ember.$.getJSON(url).then(function(response) {
			response.query = query.text;
			response.page = page;
			return response;
		});
	}
});