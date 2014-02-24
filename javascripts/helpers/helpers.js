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

var formatEventWithColor = function(event) {
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
	return events[event];
};

Ember.Handlebars.helper('event-color', function(event) {
	return new Handlebars.SafeString(formatEventWithColor(event));
});

Ember.Handlebars.helper('plural-event', function(count) {
	if (count === 1) return "Event";
	else return "Events";
});

var formatDateToLocal = function(date) {
	var date = new Date(date * 1000);
	return date.toLocaleDateString() + " at " + date.toLocaleTimeString();
};

Ember.Handlebars.helper('format-date', formatDateToLocal);

Ember.Handlebars.helper('list-additional-arguments', function(args) {
	var div = jQuery('<div/>');

	var ul = jQuery('<ul/>').appendTo(div);

	for (var key in args) {
		jQuery('<li/>')
			.html(key + ": " + args[key])
			.appendTo(ul);
	}

	return new Handlebars.SafeString(div.html());
});

Ember.Handlebars.helper('result-pagination', function(pages) {
	var hash = window.location.hash,
		splits = hash.split('/'),
		current = splits[splits.length - 1] * 1,
		last = pages[pages.length - 1];
	div = jQuery('<div/>');
	splits.pop();

	// FIRST PAGE BUTTON
	var firstPage = $('<li/>').appendTo(div),
		firstPagePath = splits.slice(0);
	firstPagePath.push(1);
	var firstPageLink = firstPagePath.join("/");
	if (current === 1) firstPage.addClass('disabled');
	jQuery('<a/>', {
		href: firstPageLink
	}).html('&laquo;').appendTo(firstPage);

	// INDIVIDUAL PAGES
	var numberOfLinksToShow = 8,
		buffer = ((numberOfLinksToShow + (numberOfLinksToShow % 2)) / 2),
		startIndex = (current - buffer);
	if (startIndex < 1) startIndex = 1;
	var endIndex = startIndex + numberOfLinksToShow;
	if (endIndex > last) endIndex = last;
	pages.forEach(function(page, i, array) {
		if (page >= startIndex && page <= endIndex) {
			var thisPath = splits.slice(0);
			thisPath.push(page);
			var link = thisPath.join("/"),
				li = jQuery('<li/>').appendTo(div);
			if (current === page) li.addClass('active');
			jQuery('<a/>', {
				href: link
			}).html(page).appendTo(li);
		}
	});

	// LAST PAGE BUTTON
	var lastPage = $('<li/>').appendTo(div),
		lastPagePath = splits.slice(0);
	lastPagePath.push(last);
	var lastPageLink = lastPagePath.join("/");
	if (current === last) lastPage.addClass('disabled');
	jQuery('<a/>', {
		href: lastPageLink
	}).html('&raquo;').appendTo(lastPage);

	return new Handlebars.SafeString(div.html());
});

//Handlebars.registerBoundHelper('add-search-filter', function(filter, options) {
Ember.Handlebars.helper('label-for', function(filter) {
	var div = jQuery('<div/>');

	jQuery('<label/ > ', {
		for: filter.id,
		class: "col-sm-2 control-label"
	}).html(filter.name).css('width', '170px').css('marginRight', '10px').css('textAlign', 'right').appendTo(div);

	return new Handlebars.SafeString(div.html());
});

Ember.Handlebars.helper('download-csv', function(url) {
	var div = jQuery('<div/>');

	jQuery('<a/>', {
		href: url
	}).html('Download as CSV').appendTo(div);

	return new Handlebars.SafeString(div.html());
});