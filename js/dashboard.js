/* ==========================================================================
 * DASHBOARD.JS
 * ==========================================================================
 *
 * SUMMARY
 * This file contains the EmberJS data for the main Dashboard page (the first
 * page you see when you load the url).
 *
 */

/* ROUTES
 *=========================================================================*/

App.RecentRoute = Ember.Route.extend({
	model: function() {
		var call = Ember.$.getJSON('api/search.php?query=recent&limit=10');
		return call;
	}
});

App.TotalsRoute = Ember.Route.extend({
	model: function() {
		var call = Ember.$.getJSON('api/search.php?query=total&hours=24');
		return call;
	}
});


/* CONTROLLERS
 *=========================================================================*/

App.DashboardController = Ember.Controller.extend({
	query: '',

	actions: {
		search: function() {
			var self = this,
				page = 1;
			Ember.$.getJSON('api/search.php?query=wildcard&text=' + this.get('query')).then(function(response) {
				var resultsPerPage = 20,
					mod = response.length % resultsPerPage,
					totalPages = (response.length - mod) / resultsPerPage;
				if (mod) totalPages++;
				pageArray = [];
				for (var i = 1; i <= totalPages; i++) {
					pageArray.push(i);
				}
				response.total = pageArray;
				response.query = self.get('query');
				response.page = page;
				var allResults = response.data;
				response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
				self.transitionToRoute('search', response);
			});
		}
	}
});