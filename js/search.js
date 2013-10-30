/* ==========================================================================
 * SEARCH.JS
 * ==========================================================================
 *
 * SUMMARY
 * This file contains the Ember JS data for the search results page.
 *
 */

App.SearchRoute = Ember.Route.extend({
	setupController: function(controller, model) {
		var query = model.query,
			page = model.page,
			resultsPerPage = 10;
		
		Ember.$.getJSON('api/search.php?query=wildcard&resultsPerPage=' + resultsPerPage + '&text=' + query).then(function(response) {
			response.query = query;
			response.page = page;
			var allResults = response.data;
			response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
			controller.set('model', response);
		});
	}
});