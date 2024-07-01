# Object Routing Library

This library allows to create routes based on _objects_. This can help reduce repetitive code patterns when the same parameters are read from an object every time a route is used, and also opens up new possibilities through the support of polymorphism.

This repository is a fork of [jms/object-routing](https://github.com/schmittjoh/object-routing), and the original documentation is at http://jmsyst.com/libs/object-routing.

The library is not tied to any concrete router implementation. It ships with an adapter for Symfony's router.

## Installation

Install this library through composer as `webfactory/object-routing`. 

For Symfony projects, additionally install [webfactory/object-routing-bundle](https://github.com/webfactory/BGObjectRoutingBundle).

## Usage

The preferred and recommended way to declare _object routes_ is through PHP attributes, although PHP, XML and YAML drivers are also available. See the original library's documentation on how to use those.

The 1.x version of this library supports configuration through annotations. Annotation support has been removed in the 2.0 release.

```php
<?php

use JMS\ObjectRouting\Attribute\ObjectRoute;

// multiple `#[ObjectRoute]` attributes are possible for different `type`s 
#[ObjectRoute(type: 'view', name: 'the-actual-route-name', params: ['slug' => 'slug'])
class BlogPost
{
    public function getSlug(): string
    {
        // ...
    }
}
```

This declares an _object route_  named `view`. A corresponding Symfony controller might look like this:

```php
<?php

use Symfony\Component\Routing\Attribute\Route;
// ...

class BlogPostController
{
    #[Route('/blog-posts/{slug}', name: 'the-actual-route-name')]
    public function viewAction(BlogPost $post): Response
    {
        // ...
    }
}
```

You use the _object router_ to generate the URL or path of a given `type` for a given object. The `type` will be used to pick the right _object route_ for the object's class, and the object route's `name` 
in turn determines the name of the route that will finally be used.

`params` declared in an object route will be evaluated as [Symfony PropertyAccess](https://symfony.com/doc/current/components/property_access.html) expressions on the given object, and the resulting values will be passed on to the underlying router.

You can also use a configuration setting named `paramExpressions` for expression language support; see the section below.

`extraParams` can be given to the object router, and those will be passed-on to the underlying router as-is.

```php
$objectRouter->generate('view', $blogPost);
// equivalent to
$router->generate('the-actual-route-name', ['slug' => $blogPost->getSlug()]);
```

This example shows that when you need to read data from the object and pass it to the router, using the object router results in more concise expressions that can hide this detail, avoiding repetitions.

### Twig support

For Twig, this library also provides two new functions:

```twig
{{ object_path('view', blogPost) }}
{# equivalent to #}
{{ path('the-actual-route-name', {'slug': blogPost.slug}) }}

{{ object_url('view', blogPost) }}
{# equivalent to #}
{{ url('the-actual-route-name', {'slug': blogPost.slug}) }}
```

## Polymorphism support

Object routes of a given `type` can be defined differently in different classes. 

For example, you could have a "conference website" application that displays details about schedule items, but the routes used to display "workshop" or "talk" detail pages differ.

```php
use JMS\ObjectRouting\Attribute\ObjectRoute;

#[ObjectRoute(type: 'detail', name: 'app_talk_detail', params: ['id' => 'id'])
class Talk {
    // ...
}

#[ObjectRoute(type: 'detail', name: 'app_workshop_detail', params: ['slug' => 'slug'])
class Workshop {
    // ...
}
```

In this example, you could use the same Twig expression `object_path('detail', schedule_item)` to generate the right route for the `schedule_item` depending on whether it is a `Talk` or a `Workshop`, and the appropriate parameters (either the `id` or the `slug`) would be passed automatically as well.

## Expression Language support

In an `#[ObjectRoute]` declaration, you can also use the `paramExpressions` key to use [Symfony Expression Language](https://symfony.com/doc/current/reference/formats/expression_language.html) expressions. 

The expression gets access to two variables: `this` is the object that the route is generated on, and `params` gives access to all `extraParams` passed to the object router and the values that have been read from the object through property path expressions.

The keys of `extraParams` indicate the parameter name. Prefixing the key with `?` means that the value should not be set if the expression evaluates to `null`.

The motivating use case it that you might have a `BlogPost` object that can be archived. When you link to such a blog post, you need to include the post's year in an extra URL parameter: `?year=....`.

This is not possible with Property Access paths alone, but can be done with Expression language support:

```
#[ObjectRoute(..., paramExpressions: ['?year' => 'this.isArchived ? this.year : null'])]
```

In this case, when the `BlogPost::isArchived()` method returns `true`, the value returned from `BlogPost::getYear()` will be included in the `year` parameter for the route. When it returns `false`, the `year` parameter is omitted.

## License

The code is released under the [Apache2 license](http://www.apache.org/licenses/LICENSE-2.0.html).

Documentation is subject to the [Attribution-NonCommercial-NoDerivs 3.0 Unported](http://creativecommons.org/licenses/by-nc-nd/3.0/) license.
