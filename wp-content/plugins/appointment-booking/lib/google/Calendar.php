<?php
namespace Bookly\Lib\Google;

use Bookly\Lib\Config;
use Bookly\Lib\Entities\Appointment;
use Bookly\Lib\Entities\Service;
use Bookly\Lib\Entities\Staff;
use Bookly\Lib\Proxy;
use Bookly\Lib\Slots\Booking;
use Bookly\Lib\Slots\DatePoint;

/**
 * Class Calendar
 * @package Bookly\Lib
 */
class Calendar
{
    const EVENTS_PER_REQUEST = 250;

    /** @var Client */
    protected $client;

    /** @var string */
    protected $timezone;

    /**
     * Constructor.
     *
     * @param Client $client
     */
    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Synchronize Google Calendar with given appointment.
     *
     * @param Appointment $appointment
     * @return bool
     */
    public function syncAppointment( Appointment $appointment )
    {
        if ( ! $this->_hasCalendar() ) {
            return true;
        }

        try {
            if ( $appointment->hasGoogleCalendarEvent() ) {
                // Update event.
                $event = $this->_populateEvent(
                    $this->client->service()->events->get( $this->_getCalendarId(), $appointment->getGoogleEventId() ),
                    $appointment
                );
                $event = $this->client->service()->events->update( $this->_getCalendarId(), $event->getId(), $event );
            } else {
                // Create event.
                $event = $this->_populateEvent( new \Google_Service_Calendar_Event(), $appointment );
                $event = $this->client->service()->events->insert( $this->_getCalendarId(), $event );
            }
            $appointment
                ->setGoogleEventId( $event->getId() )
                ->setGoogleEventETag( $event->getEtag() )
                ->save()
            ;

            return true;

        } catch ( \Exception $e ) {
            $this->client->addError( $e->getMessage() );
        }

        return false;
    }

    /**
     * Delete Google Calendar event by ID.
     *
     * @param string $event_id
     * @return bool
     */
    public function deleteEvent( $event_id )
    {
        if ( ! $this->_hasCalendar() ) {
            return true;
        }

        try {
            $this->client->service()->events->delete( $this->_getCalendarId(), $event_id );

            return true;

        } catch ( \Exception $e ) {
            $this->client->addError( $e->getMessage() );
        }

        return false;
    }

    /**
     * Get bookings created from Google Calendar events.
     *
     * @param DatePoint $start_date
     * @return Booking[]|false
     */
    public function getBookings( DatePoint $start_date )
    {
        if ( ! $this->_hasCalendar() ) {
            return array();
        }

        try {
            $result       = array();
            $limit_events = get_option( 'bookly_gc_limit_events' );
            $time_min     = $start_date->format( \DateTime::RFC3339 );

            $params = array(
                'singleEvents' => true,
                'orderBy'      => 'startTime',
                'timeMin'      => $time_min,
                'maxResults'   => $limit_events ?: self::EVENTS_PER_REQUEST,
            );

            do {
                // Fetch events.
                $events = $this->client->service()->events->listEvents( $this->_getCalendarId(), $params );

                /** @var \Google_Service_Calendar_Event $event */
                foreach ( $events->getItems() as $event ) {
                    if ( ! $this->_isTransparentEvent( $event ) ) {
                        $ext_properties = $event->getExtendedProperties();
                        if ( $ext_properties !== null ) {
                            $private = $ext_properties->private;
                            if (
                                is_array( $private ) && (
                                    array_key_exists( 'bookly', $private ) ||
                                    array_key_exists( 'appointment_id', $private )  // Backward compatibility
                                )
                            ) {
                                // Skip events created by Bookly.
                                continue;
                            }
                        }

                        // Get start/end dates of event and transform them into WP timezone (Google doesn't transform whole day events into our timezone).
                        $event_start = $event->getStart();
                        $event_end   = $event->getEnd();

                        if ( $event_start->dateTime == null ) {
                            // All day event.
                            $event_start_date = new \DateTime( $event_start->date, new \DateTimeZone( $this->_getTimeZone() ) );
                            $event_end_date = new \DateTime( $event_end->date, new \DateTimeZone( $this->_getTimeZone() ) );
                        } else {
                            // Regular event.
                            $event_start_date = new \DateTime( $event_start->dateTime );
                            $event_end_date = new \DateTime( $event_end->dateTime );
                        }

                        // Convert to WP time zone.
                        $event_start_date = date_timestamp_set( date_create( Config::getWPTimeZone() ), $event_start_date->getTimestamp() );
                        $event_end_date   = date_timestamp_set( date_create( Config::getWPTimeZone() ), $event_end_date->getTimestamp() );

                        // Populate result.
                        $result[] = new Booking(
                            0,
                            0,
                            1,
                            0,
                            $event_start_date->format( 'Y-m-d H:i:s' ),
                            $event_end_date->format( 'Y-m-d H:i:s' ),
                            0,
                            0,
                            0,
                            true
                        );
                    }
                }

                $params['pageToken'] = $events->getNextPageToken();

            } while ( ! $limit_events && $params['pageToken'] !== null );

            return $result;

        } catch ( \Exception $e ) {
            $this->client->addError( $e->getMessage() );
        }

        return false;
    }

    /**
     * Populate Google Calendar event with data from given appointment.
     *
     * @param \Google_Service_Calendar_Event $event
     * @param Appointment $appointment
     * @return \Google_Service_Calendar_Event
     */
    protected function _populateEvent( \Google_Service_Calendar_Event $event, Appointment $appointment )
    {
        // Set start and end dates.
        $start_datetime = new \Google_Service_Calendar_EventDateTime();
        $start_datetime->setDateTime(
            DatePoint::fromStr( $appointment->getStartDate() )->format( \DateTime::RFC3339 )
        );
        $end_datetime = new \Google_Service_Calendar_EventDateTime();
        $end_datetime->setDateTime(
            DatePoint::fromStr( $appointment->getEndDate() )->modify( (int) $appointment->getExtrasDuration() )->format( \DateTime::RFC3339 )
        );
        $event->setStart( $start_datetime );
        $event->setEnd( $end_datetime );

        // Set other fields.
        if ( $appointment->getCreatedFrom() == 'bookly' ) {
            // Populate event created from Bookly.
            if ( $appointment->getServiceId() ) {
                $service = Service::find( $appointment->getServiceId() );
            } else {
                // Custom service.
                $service = new Service();
                $service
                    ->setTitle( $appointment->getCustomServiceName() )
                    ->setPrice( $appointment->getCustomServicePrice() );
            }
            $description  = __( 'Service', 'bookly' ) . ': ' . $service->getTitle() . PHP_EOL;
            $client_names = array();
            foreach ( $appointment->getCustomerAppointments() as $ca ) {
                $description .= sprintf(
                    "%s: %s\n%s: %s\n%s: %s\n",
                    __( 'Name',  'bookly' ), $ca->customer->getFullName(),
                    __( 'Email', 'bookly' ), $ca->customer->getEmail(),
                    __( 'Phone', 'bookly' ), $ca->customer->getPhone()
                );
                if ( Config::customFieldsActive() ) {
                    $description .= Proxy\CustomFields::getFormatted( $ca, 'text' ) . PHP_EOL;
                }
                if ( Config::serviceExtrasActive() ) {
                    $appointment_extras = json_decode( $ca->getExtras(), true );
                    $extras = implode( ', ', array_map( function ( $extra ) use ( $appointment_extras ) {
                        /** @var \BooklyServiceExtras\Lib\Entities\ServiceExtra $extra */
                        $count = $appointment_extras[ $extra->getId() ];

                        return ( $count > 1 ? $count . ' × ' : '' ) . $extra->getTitle();
                    }, (array) Proxy\ServiceExtras::findByIds( array_keys( $appointment_extras ) ) ) );
                    if ( $extras != '' ) {
                        $description .= __( 'Extras', 'bookly' ) . ': ' . $extras . PHP_EOL;
                    }
                }
                $client_names[] = $ca->customer->getFullName();
            }

            $staff = Staff::find( $appointment->getStaffId() );
            $title = strtr( get_option( 'bookly_gc_event_title', '{service_name}' ), array(
                '{service_name}' => $service->getTitle(),
                '{client_names}' => implode( ', ', $client_names ),
                '{staff_name}'   => $staff->getFullName(),
            ) );

            $event->setSummary( $title );
            $event->setDescription( $description );

            $extended_property = new \Google_Service_Calendar_EventExtendedProperties();
            $extended_property->setPrivate( array(
                'bookly'                => 1,
                'bookly_appointment_id' => $appointment->getId(),
            ) );
            $event->setExtendedProperties( $extended_property );
        } else {
            // Populate event created from Google Calendar.
            $event->setSummary( $appointment->getCustomServiceName() );
        }

        return $event;
    }

    /**
     * Get Google Calendar ID.
     *
     * @return string
     */
    public function _getCalendarId()
    {
        return $this->client->data()->calendar->id;
    }

    /**
     * Get Google Calendar time zone.
     *
     * @return string
     */
    protected function _getTimeZone()
    {
        if ( $this->timezone === null ) {
            $this->timezone = $this->client->service()->calendarList->get( $this->_getCalendarId() )->getTimeZone();
        }

        return $this->timezone;
    }

    /**
     * Tells whether there is a selected calendar.
     *
     * @return bool
     */
    protected function _hasCalendar()
    {
        return $this->_getCalendarId() != '';
    }

    /**
     * Tells whether given event is transparent (does not block time on Google Calendar).
     *
     * @param \Google_Service_Calendar_Event $event
     * @return bool
     */
    protected function _isTransparentEvent( \Google_Service_Calendar_Event $event )
    {
        return $event->getTransparency() == 'transparent';
    }
}