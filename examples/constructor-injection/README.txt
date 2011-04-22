How To Run This Example
=======================

The target use case is located in the My\ namespace.  It consist
of a simplified modeling example: repository classes that
manage entities, these repository classes consume mappers which
in turn consume a database adapter, which is seeded by some
scalar values that are user provided.  These examples use
constructor injection as their method of dependency injection.

First, you'll need to run the development time tool zf-di.php
with the appropriate configuration.

    $ php zf-di.php ./target-config.php
    
This produces the proper object configuration file, currently
it is called managed-di-container-config.php, this file is then
used as the primary structure map for the di container.

The usage of this configured di container is now demonstrated
in the test.php script.

    $ php test.php

There will be a couple more examples to come: setter injection
and interface injection.

