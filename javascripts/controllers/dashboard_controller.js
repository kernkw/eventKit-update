/* ==========================================================================
 * DASHBOARD_CONTROLLER.JS
 * ==========================================================================
 * This file contains the controller for the main Dashboard page (the first
 * page you see when you load the url). This handles the search action for
 * the center search bar (not the search bar at the top navigation bar).
 */

App.DashboardController = Ember.Controller.extend({
	query: '',

	actions: {
		search: function() {
			var query = this.get('query'),
				page = 1,
				resultsPerPage = 10,
				self = this;

			Ember.$.getJSON('api/search.php?query=wildcard&resultsPerPage=' + resultsPerPage + '&text=' + query).then(function(response) {
				response.query = query;
				response.page = page;
				var allResults = response.data;
				response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
				console.log("Application search results:", response);
				self.transitionToRoute('search', response);
			});
		}
	}
});