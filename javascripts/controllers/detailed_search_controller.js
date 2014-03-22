/* ==========================================================================
 * DETAILED_SEARCH_CONTROLLER.JS
 * ==========================================================================
 * This contains the controller for the detailed search page, where you can
 * specify different filters for your search.  The controller mainly handles
 * adding and removing filters as well as submitting the search.
 */

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

            var self = this,
                page = "1";
            model.resultsPerPage = kResultsPerPage;
            model.offset = page;
            var url = "api/search.php?" + $.param(model);

            Ember.$.getJSON(url).then(function(response) {
                response.page = page;
                response.query = JSON.stringify(model);
                self.transitionToRoute('detailedSearchResults', response);
            });
        }
    },

    allFilters: function() {
        return availableParams;
    }.property()
});

var availableParams = [{
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
    name: "Type",
    id: "type"
}, {
    name: "URL",
    id: "url"
}, {
    name: "User Agent",
    id: "useragent"
}];