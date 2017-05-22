<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'simpleCalDAV/SimpleCalDAVClient.php';

/*
 * Created by PhpStorm.
 * User: geldh
 * Date: 16/05/2017
 * Time: 11:47
 */

class Calendar
{
//function to create agendaItem
    function createEvent($Description,$Summary,$START,$END,$Loc,$group)
    {
        $firstNewEvent = 'BEGIN:VCALENDAR
PRODID:-//SomeExampleStuff//EN
VERSION:2.0
BEGIN:VTIMEZONE
TZID:Europe/Berlin
X-LIC-LOCATION:Europe/Berlin
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
DTSTART:20170506T020000
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=3
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10
END:STANDARD
END:VTIMEZONE
BEGIN:VEVENT
CREATED:20140403T091024Z
LAST-MODIFIED:20140403T091044Z
DTSTAMP:20140416T091044Z
UID:' . $Summary . '
SUMMARY:' . $Summary . '
DTSTART;TZID=Europe/Berlin:' . $START . '
DTEND;TZID=Europe/Berlin:' . $END . '
LOCATION:' . $Loc . '
DESCRIPTION:' . $Description . '
END:VEVENT
END:VCALENDAR';
        try {
            /*
             * To establish a connection and to choose a calendar on the server, use
             * connect()
             * findCalendars()
             * setCalendar()
             */
            $client = new SimpleCalDAVClient();
            $client->connect('http://10.3.51.24/owncloud/remote.php/dav/calendars/admin/personal/', 'admin', 'Student1');

            $arrayOfCalendars = $client->findCalendars(); // Returns an array of all accessible calendars on the server.
            //var_dump($arrayOfCalendars);
            $client->setCalendar($arrayOfCalendars["personal"]); // Here: Use the calendar ID of your choice. If you don't know which calendar ID to use, try config/listCalendars.php
          //  http://10.3.51.24/owncloud/remote.php/dav/

            /*
             * You can create calendar objects (e.g. events, todos,...) on the server with create().
             * Just pass a string with the iCalendar-data which should be saved on the server.
             * The function returns a CalDAVObject (see CalDAVObject.php) with the stored information about the new object on the server
             */

            $firstNewEventOnServer = $client->create($firstNewEvent); // Creates $firstNewEvent on the server and a CalDAVObject representing the event.
            $client->setCalendar($arrayOfCalendars["personal"]);
            //$secondNewEventOnServer = $client->create($secondNewEvent); // Creates $firstNewEvent on the server and a CalDAVObject representing the event.
        } catch (Exception $e) {
            echo $e->__toString();
        }
if($group == "SPK"){
    try {
        $client = new SimpleCalDAVClient();
        $client->connect('http://10.3.51.24/owncloud/remote.php/dav/calendars/admin/personal/', 'adminSPK', 'Student1');

        $arrayOfCalendars = $client->findCalendars(); // Returns an array of all accessible calendars on the server.
        $client->setCalendar($arrayOfCalendars["personal"]); // Here: Use the calendar ID of your choice. If you don't know which calendar ID to use, try config/listCalendars.php

        $firstNewEventOnServer = $client->create($firstNewEvent); // Creates $firstNewEvent on the server and a CalDAVObject representing the event.
        $client->setCalendar($arrayOfCalendars["personal"]);
    } catch (Exception $e) {
        echo $e->__toString();
    }

}

if($group == "COL"){
    try {
        $client = new SimpleCalDAVClient();
        $client->connect('http://10.3.51.24/owncloud/remote.php/dav/calendars/admin/personal/', 'adminCOL', 'Student1');

        $arrayOfCalendars = $client->findCalendars(); // Returns an array of all accessible calendars on the server.
        $client->setCalendar($arrayOfCalendars["personal"]); // Here: Use the calendar ID of your choice. If you don't know which calendar ID to use, try config/listCalendars.php

        $firstNewEventOnServer = $client->create($firstNewEvent); // Creates $firstNewEvent on the server and a CalDAVObject representing the event.
        $client->setCalendar($arrayOfCalendars["personal"]);
    } catch (Exception $e) {
        echo $e->__toString();
    }
}

    }
}

