/* ==========================================================================
 * SEARCH.JS
 * ==========================================================================
 *
 * SUMMARY
 * This file contains the Ember JS data for the search results page and the
 * detailed search page.
 *
 */

/* ROUTES
 *=========================================================================*/

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

App.DetailedSearchRoute = Ember.Route.extend({
	model: function() {
		return searchParams;
	}
});

/* CONTROLLERS
 *=========================================================================*/
App.DetailedSearchController = Ember.ArrayController.extend({
	actions: {
		removeFilter: function(sender) {
			this.removeObject(sender);
		},

		addFilter: function(type) {
			if (type.id === "dateStart" && this.findBy('id', 'dateStart')) {
				alert("You already have a Start Date filter!");
				return;
			}

			if (type.id === "dateEnd" && this.findBy('id', 'dateEnd')) {
				alert("You already have a Start End filter!");
				return;
			}

			this.addObject({
				name: type.name,
				id: type.id,
				time: new Date().getTime()
			});

			setTimeout(function() {
				var d = $(".init-date-picker");
				d.each(function(index) {
					$(d[index]).datepicker();
					$(d[index]).removeClass("init-date-picker");
				});
			}, 500);
		},

		submitSearch: function() {
			var params = {};
			$("#detailedSearchParams :input").each(function() {
				if (!params[this.name]) params[this.name] = [];
				params[this.name].push($(this).val());
			});
			console.log(params);
		}
	},

	allFilters: function() {
		return availableParams;
	}.property()
});

/* MODELS
 *=========================================================================*/
var searchParams = [],
	availableParams = [{
		name: "Additional Argument",
		id: "additional_arguments"
	}, {
		name: "Attempt",
		id: "attempt"
	}, {
		name: "Category",
		id: "category"
	}, {
		name: "Date End",
		id: "dateEnd"
	}, {
		name: "Date Start",
		id: "dateStart"
	}, {
		name: "Email",
		id: "email"
	}, {
		name: "Event",
		id: "event"
	}, {
		name: "IP",
		id: "ip"
	}, {
		name: "Newsletter ID",
		id: "newsletter_id"
	}, {
		name: "Newsletter Send ID",
		id: "newsletter_send_id"
	}, {
		name: "Newsletter User List ID",
		id: "newsletter_list_id"
	}, {
		name: "SMTP-ID",
		id: "smtpid"
	}, {
		name: "Reason",
		id: "reason"
	}, {
		name: "Response",
		id: "response"
	}, {
		name: "Status",
		id: "status"
	}, {
		name: "Subject",
		id: "subject"
	}, {
		name: "Type",
		id: "type"
	}, {
		name: "URL",
		id: "url"
	}, {
		name: "User Agent",
		id: "useragent"
	}];