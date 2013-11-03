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
	},

	renderTemplate: function() {
		this.render();
		setTimeout(function() {
			var d = $(".init-date-picker");
			d.each(function(index) {
				$(d[index]).datepicker();
			});
		}, 500);
	}
});

App.DetailedSearchResultsRoute = Ember.Route.extend({
	setupController: function(controller, model) {
		var query = JSON.parse(decodeURIComponent(model.query)),
			page = model.page,
			resultsPerPage = 10;
		query.resultsPerPage = resultsPerPage;
		var url = "api/search.php?" + $.param(query);

		Ember.$.getJSON(url).then(function(response) {
			response.page = page;
			response.query = JSON.stringify(query);
			var allResults = response.data;
			response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
			controller.set('model', response);
		});
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

			if ($("#match_option").val() == "all" && this.findBy('id', type.id)) {
				switch (type.id) {
					case "category":
					case "additional_arguments":
						break;

					default:
						alert('Since you\'ve selected "match all" above, you can only have one "' + type.name + '" filter in your search.');
						return;
						break;
				}
			}

			var newFilter = {
				name: type.name,
				id: type.id,
				time: new Date().getTime(),
				name_key: type.id + "_key",
				name_val: type.id + "_value",
				key: "",
				val: ""
			};

			newFilter[type.id] = "id";

			this.addObject(newFilter);

			setTimeout(function() {
				var d = $(".init-date-picker");
				d.each(function(index) {
					$(d[index]).datepicker();
				});
			}, 500);
		},

		submitSearch: function() {
			var params = {};
			$("#detailedSearchParams :input").each(function() {
				if (this.type === 'button') return;
				if (!params[this.name]) params[this.name] = [];
				params[this.name].push($(this).val());
			});

			var model = {
				query: "detailed",
				match: "all"
			};
			for (var key in params) {
				if (key === "additional_arguments_value") {
					continue;
				} else if (key === "additional_arguments_key") {
					model.additional_arguments = [];
					params[key].forEach(function(value, index, array) {
						model.additional_arguments.push('"' + value + '":"' + params.additional_arguments_value[index] + '"');
					});
				} else if (key.match(/newsletter/g)) {
					model.newsletter = [];
					params[key].forEach(function(value, index, array) {
						model.newsletter.push('"' + key + '":"' + value + '"');
					});
				} else if (key === "dateStart" || key === "dateEnd") {
					var date = new Date(params[key]),
						milliseconds = date.getTime(),
						mod = milliseconds % 1000,
						unix = (milliseconds - mod) / 1000;
					model[key] = unix;
				} else {
					if (params[key].length > 1) {
						model[key] = params[key];
					} else {
						model[key] = params[key][0];
					}
				}
			}

			var url = "api/search.php?" + $.param(model);
			var self = this,
				page = 1;
			Ember.$.getJSON(url).then(function(response) {
				var resultsPerPage = 10,
					mod = response.length % resultsPerPage,
					totalPages = (response.length - mod) / resultsPerPage;
				if (mod) totalPages++;
				pageArray = [];
				for (var i = 1; i <= totalPages; i++) {
					pageArray.push(i);
				}
				response.total = pageArray;
				response.page = page;
				response.query = JSON.stringify(model);
				var allResults = response.data;
				response.data = allResults.slice(((page - 1) * resultsPerPage), (page * resultsPerPage));
				self.transitionToRoute('detailedSearchResults', response);
			});
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