/* ==========================================================================
 * SEARCH_ROUTE.JS
 * ==========================================================================
 * Retrieves the results for the given query and passes them on as the model
 * for SearchController.
 */

App.SearchRoute = Ember.Route.extend({
	model: function(params) {
		var query = params.query,
			page = params.page,
			resultsPerPage = 10;

		return Ember.$.getJSON('api/search.php?query=wildcard&resultsPerPage=' + resultsPerPage + '&text=' + query).then(function(response) {
			response.query = query;
			response.page = page;
			var allResults = response.data;
			response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
			console.log(response);
			return response;
		});
	}
});