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
		var smtpid = this.get('model.smtpid'),
			group = $("#related-group"),
			noResults = '<span href="#" class="list-group-item"><h3 style="font-size: 18px; margin-top: 1em; margin-bottom: 1em;">No related events found.</h3></span>';
		group.html('');
		
		if (smtpid) {
			var related = {
					query: 'detailed',
					match: 'all',
					smtpid: smtpid,
					email: this.get('model.email')
				},
				self = this;
			Ember.$.getJSON('api/search.php?' + $.param(related)).then(function(events) {
				
				if (events.data && events.data.length > 1) {
					events.data.forEach(function(value, i, array) {
						var eventName = formatEventWithColor(value.event);

						if (value.uid === self.get('model.uid')) eventName += '&nbsp;<small>Currently Selected</small>';

						var a = jQuery('<a/>', {
							href: '#/event/' + value.uid,
							class: 'list-group-item'
						}).appendTo(group);

						jQuery('<h3/>', {
							style: 'font-size: 18px; margin: 0px;'
						}).html(eventName).appendTo(a);

						jQuery('<p/>', {
							style: "font-size: 10px; margin: 0px; color: #AAA;"
						}).html(formatDateToLocal(value.timestamp)).appendTo(a);
					});
				} else {
					group.html(noResults);
				}
			});
		} else {
			group.html(noResults);
		}
	}.observes('model')
			
});

App.EventRoute = Ember.Route.extend({
 	setupController: function(controller, model) {
 		var uid = model.uid,
			searchParams = {
				query: 'detailed',
				match: 'all',
				uid: uid
			};
		
		Ember.$.getJSON('api/search.php?' + $.param(searchParams)).then(function(response) {
			controller.set('model', response.data[0]);
		});
 	}
 });