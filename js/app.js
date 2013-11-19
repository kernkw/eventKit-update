/* ==========================================================================
 * APP.JS
 * ==========================================================================
 *
 * SUMMARY
 * This file contains the main Ember JS application data, including the
 * Router map which defines the URLs of the app. For more information on
 * Ember JS, visit http://emberjs.com/.
 *
 */

App = Ember.Application.create();

App.Router.map(function() {
	// DASHBOARD
	this.resource('dashboard', {
		path: "/"
	});

	// SEARCH
	this.resource('search', {
		path: "/search/:query/page/:page"
	});

	// DETAILED SEARCH ENTRY
	this.resource('detailedSearch', {
		path: "/detailed_search"
	});

	this.resource('detailedSearchResults', {
		path: "/detailed_search/:query/results/:page"
	});

	// EVENT INSPECTOR
	this.resource('event', {
		path: "/event/:uid"
	});
});

App.ApplicationController = Ember.Controller.extend({
	query: '',

	actions: {
		search: function() {
			var self = this,
				query = this.get('query'),
				page = 1;
			Ember.$.getJSON('api/search.php?query=wildcard&text=' + query).then(function(response) {
				var resultsPerPage = 10,
					mod = response.length % resultsPerPage,
					totalPages = (response.length - mod) / resultsPerPage;
				if (mod) totalPages++;
				pageArray = [];
				for (var i = 1; i <= totalPages; i++) {
					pageArray.push(i);
				}
				response.total = pageArray;
				response.query = query;
				response.page = page;
				var allResults = response.data;
				response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
				self.set('query', '');
				self.transitionToRoute('search', response);
			});
		}
	}
});