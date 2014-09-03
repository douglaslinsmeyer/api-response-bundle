<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer\Event\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

/**
 * Handles translating a mixed type to a different type
 *
 * @author Daniel Lakes <dlakes@nerdery.com>
 */
class MixedTypePreSerializeSubscriber implements EventSubscriberInterface
{
    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' => 'json'),
     *         array(...),
     *     )
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => 'serializer.pre_serialize',
                'method' => 'onMixedTypePreSerialize',
                'class' => 'DLinsmeyer\Bundle\ApiBundle\Response\Model\Response',
                'type' => 'Mixed',
            )
        );
    }

    public function onMixedTypePreSerialize(PreSerializeEvent $event)
    {

    }
}
