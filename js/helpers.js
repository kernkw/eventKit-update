Ember.Handlebars.helper('format-event', function(event) {
	var events = {
		bounce: "Bounce",
		click: "Click",
		deferred: "Deferred",
		delivered: "Delivered",
		dropped: "Dropped",
		open: "Open",
		processed: "Processed",
		spamreport: "Spam Report",
		unsubscribe: "Unsubscribe"
	};
	return events[event];
});

Ember.Handlebars.helper('event-color', function(event) {
	var events = {
		bounce: '<span style="color:#880">Bounce</span>',
		click: '<span style="color:#008">Click</span>',
		deferred: '<span style="color:#088">Deferred</span>',
		delivered: '<span style="color:#080">Delivered</span>',
		dropped: '<span style="color:"800">Dropped</span>',
		open: '<span style="color:#008">Open</span>',
		processed: '<span style="color:#666">Processed</span>',
		spamreport: '<span style="color:#800">Spam Report</span>',
		unsubscribe: '<span style="color:#088">Unsubscribe</span>'
	};
	return new Handlebars.SafeString(events[event]);
});

Ember.Handlebars.helper('plural-event', function(count) {
	if (count === 1) return "Event";
	else return "Events";
});

Ember.Handlebars.helper('format-date', function(date) {
	var date = new Date(date * 1000);
	return date.toLocaleDateString() + " at " + date.toLocaleTimeString();
});