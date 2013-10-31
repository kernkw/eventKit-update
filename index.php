<?php
/* ==========================================================================
 * INDEX PAGE
 * ==========================================================================
 *
 * SUMMARY
 * This page is the main index page. Upon load, it'll determine if a user is
 * viewing the page and display the GUI, or if it's receiving a POST from the
 * webhook, in which case it'll log the notification and send a response
 * back.
 *
 */

require_once("DatabaseController.php");

// DETERMINE IF THERE'S POST DATA
if (isset($HTTP_RAW_POST_DATA)) {
    $db = new SendGrid\EventKit\DatabaseController();
    $response = $db->processPost($HTTP_RAW_POST_DATA);
    header($response);
    return;
} else {
    // IF THERE ISN'T ANY POST DATA, SHOW THE GUI
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SendGrid Event Webhook Starter Kit</title>
    
    <!--META TAGS-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <!--STYLES-->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/master.css">
</head>
<body>

    <!--
    *
    * MAIN DASHBOARD PAGE
    *
    *****************************************************-->

    <script type="text/x-handlebars" charset="utf-8">
        <div class="nav">
            <div class="nav_container">
                <a href="#">
                    <div class="brand"></div>
                </a>
                <div class="search">
                    <div class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            {{input type="text" value=query action="search" class="form-control" placeholder="Search"}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="body_container">
            {{outlet}}
            </div>
        </div
    </script>
    
    <script type="text/x-handlebars" id="dashboard" charset="utf-8">
        <h1 class="outer-text">Dashboard</h1>
        <p class="outer-text">Welcome to the SendGrid Event Webhook Starter Kit. Use the search box below to start searching your logs.</p>
        <div class="panel panel-default" style="margin-top: 25px">
            <div class="panel-body">
                <h1 style="margin-top: 0px">Search</h1>
                <p>Your query below will check every field in the database (to, from, etc.):</p>
                {{input type="text" value=query action="search" class="form-control"}}
            </div>
        </div>
        
        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; table-layout:fixed;">
            <tr>
                {{outlet}}
            </tr>
        </table>
        <h1>&nbsp;</h1>
    </script>

    <script type="text/x-handlebars" id="recent" data-template-name="recent">
        <td style="width: 415px; vertical-align: top;">
            <div class="panel panel-info" style="margin-top: 25px;">
                <div class="panel-heading">
                    Most Recent Events
                </div>
                <div class="list-group" style="overflow-y: auto; height: 275px;">
                    {{#if data.length}}
                        {{#each data}}
                            {{#link-to 'event' this classNames="list-group-item"}}
                                <h3 style="font-size: 18px; margin: 0px;">
                                    {{event-color event}}
                                </h3>
                                <p style="font-size: 10px; margin: 0px; color: #AAA;">{{format-date timestamp}}</p>
                                <p class="truncate" style="font-size: 14px; margin-top: 5px; margin-bottom: 0px;">{{email}}</p>
                            {{/link-to}}
                        {{/each}}
                    {{else}}
                        <span href="#" class="list-group-item">
                            <h3 style="font-size: 18px; margin-top: 1em; margin-bottom: 1em;">
                                No recent events.
                            </h3>
                        </span>
                    {{/if}}
                </div>
            </div>
        </td>
        <td style="width: 40px"></td>
        <td style="width: 415px; vertical-align: top;">
            {{outlet}}
        </td>
    </script>

    <script type="text/x-handlebars" id="totals" data-template-name="totals">
        <div class="panel panel-info" style="margin-top: 25px">
            <div class="panel-heading">
                Total Events Today
            </div>
            <div class="panel-body" style="overflow-y: auto; height: 275px;">
                <span style="text-align: center">
                    <h1 style="font-size: 72px; margin-top: 50px; margin-bottom: 0px;">{{data}}</h1>
                    <h3>{{plural-event data}}</h3>
                </span>
            </div>
        </div>
    </script>


    <!--
    *
    * SEARCH PAGE
    *
    *****************************************************-->
    <script type="text/x-handlebars" id="search" data-template-name="search">
        <div class="panel panel-default" style="margin-top: 25px">
            <div class="panel-body">
                <h1 style="margin:0px;">Search Results</h1>
                <p style="margin:0px; color: #888; font-size: 14px;">For "{{query}}"</p>
            </div>
            <div class="list-group">
                {{#if data.length}}
                    {{#each data}}
                        {{#link-to 'event' this classNames="list-group-item"}}
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; table-layout:fixed;">
                                <tr>
                                    <td style="width: 50%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: top;">
                                        <h3 style="font-size: 18px; margin: 0px;">
                                            {{event-color event}}
                                        </h3>
                                        {{#if timestamp}}
                                            <p style="font-size: 10px; margin: 0px; color: #AAA;">{{format-date timestamp}}</p>
                                        {{/if}}
                                        {{#if subject}}
                                            <i><p class="truncate" style="font-size: 14px; margin-top: 5px; margin-bottom: 0px; font-weight: bold;">{{subject}}</p></i>
                                        {{/if}}
                                        {{#if email}}
                                            <p class="truncate" style="font-size: 14px; margin: 0px;">{{email}}</p>
                                        {{/if}}
                                        {{#if url}}
                                            <p class="truncate" style="font-size: 14px; margin-top: 5px; margin-bottom: 0px;">{{url}}</p>
                                        {{/if}}
                                    </td>
                                    <td style="width: 50%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: top;">
                                        {{#if category}}
                                            <p style="margin: 0px; font-weight: bold">Categories:</p>
                                            <ul>
                                                {{#each category}}
                                                    <li>{{this}}</li>
                                                {{/each}}
                                            </ul>
                                        {{/if}}
                                        {{#if additional_arguments}}
                                            <p style="margin: 0px; font-weight: bold;">Additional Arguments</p>
                                            {{list-additional-arguments additional_arguments}}
                                        {{/if}}
                                    </td>
                                </tr>
                            </table>
                        {{/link-to}}
                    {{/each}}
                {{else}}
                    <div class="list-group-item">
                        <h3 style="font-size: 18px; margin: 0px;">
                            No results.
                        </h3>
                    </div>
                {{/if}}
            </div>
        </div>
        {{#if data.length}}
            <div style="text-align: center; margin: auto;">
                <ul class="pagination">
                    {{result-pagination pages}}
                </ul>
            </div>
        {{/if}}
        <h1>&nbsp;</h1>
    </script>


    <!--
    *
    * EVENT INSPECTOR PAGE
    *
    *****************************************************-->
    <script type="text/x-handlebars" id="event">
        <span class="outer-text">
            <h1>Event Details</h1>
            {{#if event}}
                <h2 style="font-size: 22px; margin-bottom: 0px;">{{event-color event}}</h2>
            {{/if}}
            {{#if email}}
                <h2 style="font-size: 18px; margin: 0px;">{{email}}</h2>
            {{/if}}
        </span>
        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; table-layout:fixed;">
            <tr>
                <td style="width: 415px; vertical-align: top;">
                    <div class="panel panel-info" style="margin-top: 25px;">
                        <div class="panel-heading">
                            Event Information
                        </div>
                        <div class="panel-body" id="event-info-body">
                            {{#if timestamp}}
                                <p><b>Time:</b></p>
                                <ul><li>{{format-date timestamp}}</li></ul>
                            {{/if}}

                            {{#if subject}}
                                <p><b>Subject:</b></p>
                                <ul><li>{{subject}}</li></ul>
                            {{/if}}

                            {{#if smtpid}}
                                <p><b>SMTP-ID:</b></p>
                                <ul><li><span id='current_smtpid'>{{smtpid}}</span></li></ul>
                            {{/if}}

                            {{#if sg_event_id}}
                                <p><b>SendGrid Event ID:</b></p>
                                <ul><li>{{sg_event_id}}</li></ul>
                            {{/if}}

                            {{#if category}}
                                <p><b>Categories:</b></p>
                                <ul>
                                    {{#each category}}
                                        <li>{{this}}</li>
                                    {{/each}}
                                </ul>
                            {{/if}}

                            {{#if newsletter}}
                                <p><b>Newsletter Info:</b></p>
                                <ul>
                                    <li>Newsletter ID: {{newsletter.newsletter_id}}</li>
                                    <li>User List ID: {{newsletter.newsletter_user_list_id}}</li>
                                    <li>Send ID: {{newsletter.newsletter_send_id}}</li>
                                </ul>
                            {{/if}}

                            {{#if reason}}
                                <p><b>Reason:</b></p>
                                <ul><li>{{reason}}</li></ul>
                            {{/if}}

                            {{#if response}}
                                <p><b>Response:</b></p>
                                <ul><li>{{response}}</li></ul>
                            {{/if}}

                            {{#if url}}
                                <p><b>URL:</b></p>
                                <ul><li>{{url}}</li></ul>
                            {{/if}}

                            {{#if ip}}
                                <p><b>IP:</b></p>
                                <ul><li>{{ip}}</li></ul>
                            {{/if}}

                            {{#if useragent}}
                                <p><b>User Agent:</b></p>
                                <ul><li>{{useragent}}</li></ul>
                            {{/if}}

                            {{#if attempt}}
                                <p><b>Attempt:</b> {{attempt}}</p>
                            {{/if}}

                            {{#if status}}
                                <p><b>Status:</b></p>
                                <ul><li>{{status}}</li></ul>
                            {{/if}}

                            {{#if type}}
                                <p><b>Type:</b></p>
                                <ul><li class="truncate">{{type}}</li></ul>
                            {{/if}}

                            {{#if sg_message_id}}
                                <p><b>SendGrid Message ID:</b></p>
                                <ul><li class="truncate">{{smtpid}}</li></ul>
                            {{/if}}

                            {{#if additional_arguments}}
                                <p><b>Additional Arguments</b></p>
                                {{list-additional-arguments additional_arguments}}
                            {{/if}}
                        </div>
                    </div>
                </td>
                <td style="width: 40px"></td>
                <td style="width: 415px; vertical-align: top;">
                    <div class="panel panel-info" style="margin-top: 25px;">
                        <div class="panel-heading">
                            Related Events
                        </div>
                        <div class="panel-body">
                            Related events are determined by the SMTP-ID of the message. Keep in mind that not all events have an SMTP-ID (such as opens and clicks), so you might not see all the related events listed below.
                        </div>
                        <div class="list-group" style="overflow-y: auto;" id="related-group">
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </script>

    <!--EMBER JS-->
    <script src="js/libs/jquery-1.9.1.js"></script>
    <script src="js/libs/handlebars-1.0.0.js"></script>
    <script src="js/libs/ember-1.1.2.js"></script>
    <script src="js/app.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/search.js"></script>
    <script src="js/event.js"></script>
    <script src="js/helpers.js"></script>
</body>
</html>

<?php
}
?>