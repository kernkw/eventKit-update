/* ==========================================================================
 * EVENT.JS
 * ==========================================================================
 *
 * SUMMARY
 * This file contains the Ember JS data for the Event Details page.
 *
 */

/* ROUTES
 *=========================================================================*/

App.EventController = Ember.ObjectController.extend({
	modelDidChange: function() {
		// FIND ANY RELATED POSTS BY SMTP-ID
		var smtpid = this.get('model.smtpid'),
			event = this.get('model.event'),
			reason = this.get('model.reason'),
			email = this.get('model.email');

		if (smtpid) {
			var related = {
				query: 'detailed',
				match: 'all',
				smtpid: smtpid,
				email: email
			},
				self = this;
			Ember.$.getJSON('api/search.php?' + $.param(related)).then(function(events) {
				if (events.data && events.data.length > 1) {
					$("#related-group").html('');
					events.data.forEach(function(value, i, array) {
						var eventName = formatEventWithColor(value.event);

						if (value.uid === self.get('model.uid')) eventName += '&nbsp;<small>Currently Selected</small>';

						var a = jQuery('<a/>', {
							href: '#/event/' + value.uid,
							class: 'list-group-item'
						}).appendTo("#related-group");

						jQuery('<h3/>', {
							style: 'font-size: 18px; margin: 0px;'
						}).html(eventName).appendTo(a);

						jQuery('<p/>', {
							style: "font-size: 10px; margin: 0px; color: #AAA;"
						}).html(formatDateToLocal(value.timestamp)).appendTo(a);
					});
				}
			});
		}

		// IF THIS IS A DROP, ADD A MORE DESCRIPTIVE REASON FOR THE DROP
		if (event === 'dropped') {
			var descriptions = {
				bounce: "This email was dropped because \"__EMAIL__\" is on your bounce list.  To continue sending to this address, go to <a href=\"http://sendgrid.com/bounces\">http://sendgrid.com/bounces</a> and delete it from the list.",
				unsubscribe: "This email was dropped because \"__EMAIL__\" is on your unsubscribe list.  To continue sending to this address, go to <a href=\"http://sendgrid.com/unsubscribes\">http://sendgrid.com/unsubscribes</a> and delete it from the list.",
				invalid: "This email was dropped because \"__EMAIL__\" is an invalid email address.  See your invalid email list at <a href=\"http://sendgrid.com/invalidEmail\">http://sendgrid.com/invalidEmail</a> for more info.",
				spam: "This email was dropped because \"__EMAIL__\" reported one of your previous messages as spam, and got added to your spam report list.  To continue sending to this address, go to <a href=\"http://sendgrid.com/spamReports\">http://sendgrid.com/spamReports</a> and delete it from the list."
			},
				reason;

			for (var type in descriptions) {
				var regex = new RegExp(type, "gi");
				if (reason.match(regex)) {
					reason = descriptions[type].replace('__EMAIL__', email);
				}
			}
			if (reason) {
				jQuery('<p/>').html(reason).appendTo("#event-info-body");
			}
		}
	}.observes('model')

});

App.EventRoute = Ember.Route.extend({
	model: function(params) {
		var uid = params.uid,
			searchParams = {
				query: 'detailed',
				match: 'all',
				uid: uid
			};

		return Ember.$.getJSON('api/search.php?' + $.param(searchParams)).then(function(results) {
			if (results.data.length) {
				return results.data[0];
			} else {
				return [];
			}
		});
	}
});