# php-mvc

What is it?
-----------

Releasy is a simple version number bumping and releasing tool written in PHP. The goal is to provide simple tracker
of current release number and to provide a simple, one-command tool to bump release version numbers and push 
correctly tagged revisions into version control.

Currently only Git is supported. Releasy also currently assumes that all actual releases (tag+push) is done to master branch.

Installation
------------
Using [Composer][composer]: 
`composer require belowaverage/releasy`

Usage
------------
Releasy works as a Composer vendor bin. 

To init a project, run

`vendor/bin releasy init <your version>`.

If <your version> is omitted, default 0.0.0 will be used. Please use semver compliant version constraints.

To bump and release major/minor/patch version, make sure all your work is committed (or stashed) and then run

`vendor/bin releasy major|minor|patch`

This will increment the version and then it does the release (tag and push). 


Versioning
----------

Surprisingly, this project is going to be maintained under the [Semantic Versioning guidelines][semver].
Releases will be numbered with the following format:

> Given a version number MAJOR.MINOR.PATCH, increment the:
>
> 1. MAJOR version when you make incompatible API changes,
> 2. MINOR version when you add functionality in a backwards-compatible manner, and
> 3. PATCH version when you make backwards-compatible bug fixes.

This project is in initial development phase (0.y.z), expect a lot of changes.

Contributors
------------

* [Jani Yli-Paavola][jylipaa]

License
-------

Releasy is free software. See the LICENSE file for more information.

[jylipaa]: https://twitter.com/jylipaa
[semver]: http://semver.org/
[composer]: https://getcomposer.org/doc/00-intro.md

