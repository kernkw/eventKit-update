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
			var resultsPerPage = 10,
				page = 1,
				query = {
					query: "wildcard",
					text: this.get('query'),
					resultsPerPage: resultsPerPage,
					offset: page
				},
				self = this;
			var url = "api/search.php?" + $.param(query);
			this.set("query", "");

			Ember.$.getJSON(url).then(function(response) {
				response.query = query.text;
				response.page = page;
				self.transitionToRoute('search', response);
			});
		}
	}
});