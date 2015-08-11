# Errors checked

This project can detect several errors:

## Reserved types

PHP 7 prohibits the usage of some types as class, interface and trait names.
It also prevents them from being used in class_alias().

The full list of reserved types is:
* int
* float
* bool
* string
* true
* false
* null
* resource
* object
* mixed
* numeric

## PHP 4 constructor

PHP 7 emits E_DEPRECATED whenever a PHP 4 constructor is defined.

A PHP 4 constructor is a method that have the same name than its class. The
only few cases were this method would be considered as a constructor is when no
PHP 5 constructor (__construct) is defined and when the class is in the global
namespace.

## New assignment by reference

New objects cannot be assigned by reference.

The result of the new statement can no longer be assigned to a variable by
reference. In PHP 5, this behavior triggered an E_DEPRECATED error but in
PHP 7, it will trigger a parse error.

## Integer handling changes

Invalid octal literal now trigger a parse error instead of silently truncated
(0128 was taken as 012).

## List handling changes

Empty list() are no longer supported.

## Function with mote than one parameter with the same name

PHP 7 no longer allows function to have more than one parameter with the same
name.

## Function removed

PHP 7 removed a bunch of functions that you can no longer use.

Most removed functions are a consequence of a extensions removal (mysql, ereg,
mssql, etc). Some are simply removed in favor of an alternative function.

This tool cannot detects class method calls so only simple functions are
checked.

## Class added

New classes were added in the global namespace so you cannot redefine them.

## Function added

Some functions were added in the global namespace so you cannot redefine them.

This tool cannot detects class method calls so only simple functions are
checked.

## Global variable removed

The global variable $HTTP_RAW_POST_DATA was removed.
