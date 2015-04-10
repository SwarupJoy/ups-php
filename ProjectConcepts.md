# Introduction #

The basic idea of this tool would be to take it from a couple of classes, and extend it into a slightly more complex framework, unifying all of the common tasks, and extending that base to include the varying specific bits for each UPS API.


# Details #

**Structure**

_upsPHP-1.0/classes_
> - Contains something like: class.upsPHP.php (A base class for common things)
> - Location of all classes for various UPS API Tools
_upsPHP-1.0/xml_
> - XML Templates used to make PHP/XML Sandwiches that we send to UPS for processing
_upsPHP-1.0/Docs_
> - Need specific docs that are complete if you want people to use it (and help make it cooler)
> - Docs describing how to get started, UPS sucks at documenting what you need to run their services.
_upsPHP-1.0/tests_
> - Some relevant tests, they can replace the user/pass/access and run this code to watch it in action.