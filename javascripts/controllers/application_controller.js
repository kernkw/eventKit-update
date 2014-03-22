/* ==========================================================================
 * APPLICATION_CONTROLLER.JS
 * ==========================================================================
 * SUMMARY
 * Contains the actions used on the main application page (mainly the top
 * search field).
 */

App.ApplicationController = Ember.Controller.extend({
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
				self.transitionToRoute('search', response);
			});
		}
	}
});