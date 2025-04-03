<?php

function create_event()
{
    $credentials = __DIR__ . 'json/credentials.json';
    require __DIR__ . '/vendor/autoload.php';

    $client = new Google_Client();
    $client->setApplicationName('test');
    $client->setScopes(array(Google_Service_Calendar::CALENDAR));
    $client->setAuthConfig($credentials);
    $client->setAccessType('offline');
    $client->getAccessToken();
    $client->getRefreshToken();

    $service = new Google_Service_Calendar($client);
    $event   = new Google_Service_Calendar_Event(array(
        'summary' => 'testing',
        'location' => '800 Howard St., San Francisco, CA 94103',
        'description' => 'A chance to hear more about Google\'s developer products.',
        'start' => array(
            'dateTime' => '2024-10-28T09:00:00-07:00',
        ),
        'end' => array(
            'dateTime' => '2024-10-29T17:00:00-07:00',
        ),
        'recurrence' => array(
            'RRULE:FREQ=DAILY;COUNT=2'
        ),
        'attendees' => array(),
        'reminders' => array(
            'useDefault' => FALSE,
            'overrides' => array(
                array('method' => 'email', 'minutes' => 24 * 60),
                array('method' => 'popup', 'minutes' => 10),
            ),
        ),
    ));

    $calendarId = 'f0b69b5a7100f17479b08deec4a01a3cfe7c36ec83dd4cfbcc033e5bc3f5e2a0@group.calendar.google.com';
    $event      = $service->events->insert($calendarId, $event);
    print_r($event->htmlLink);
}
create_event();