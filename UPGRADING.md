# Upgrading Notes for webfactory/object-routing

# Version 2.0.0

* The `\JMS\ObjectRouting\Metadata\Driver\AnnotationDriver` and `\JMS\ObjectRouting\Annotation\ObjectRoute` classes have been removed.
* The `\JMS\ObjectRouting\Attribute\ObjectRoute` class is now `final`.

# Version 1.7.0 

* Using the `\JMS\ObjectRouting\Annotation\ObjectRoute` class to configure object routes either through annotations or as an attribute has been deprecated. Use the `\JMS\ObjectRouting\Attribute\ObjectRoute` attribute instead. Also, the `\JMS\ObjectRouting\Metadata\Driver\AnnotationDriver` has been deprecated.
