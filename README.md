Simple ISO 639
=============

[Simple ISO 639] is a small library to convert languages codes between ISO 639-1
(two letters language codes) and ISO 639-2 (three letters). It is built from the
official standard lists ([SIL]) and is compatible with IETF language tags
([RFC 4646]).

Note: The current standard (2007) uses the native language as a base for the
codes. For example, three letters code for `French` is `fra`, not `fre`, or, for
`Chinese`, `zho`, not `chi`. English language is deprecated.


Installation
------------

This module is a composer library available on [packagist]:

```
composer require daniel-km/simple-iso639
```


Usage
-----

Once included in your code, you can use if like that:

```
$languages = [
    'fr',
    'fra',
    'fre',
    'frm',
    'fro',
    'fr_FR',
    'fr-FR',
];
$result = [];
foreach ($languages as $language) {
    $result[$language] = SimpleISO639::code($language);
}
print_r($result);

```


Developement
------------

The lists are automatically generated from this command:

```
php -f scripts/generate.php
```


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitHub.


License
-------

### Module

This module is published under the [CeCILL v2.1] licence, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

This software is governed by the CeCILL license under French law and abiding by
the rules of distribution of free software. You can use, modify and/ or
redistribute the software under the terms of the CeCILL license as circulated by
CEA, CNRS and INRIA at the following URL "http://www.cecill.info".

As a counterpart to the access to the source code and rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors have only limited liability.

In this respect, the user’s attention is drawn to the risks associated with
loading, using, modifying and/or developing or reproducing the software by the
user in light of its specific status of free software, that may mean that it is
complicated to manipulate, and that also therefore means that it is reserved for
developers and experienced professionals having in-depth computer knowledge.
Users are therefore encouraged to load and test the software’s suitability as
regards their requirements in conditions enabling the security of their systems
and/or data to be ensured and, more generally, to use and operate it in the same
conditions as regards security.

The fact that you are presently reading this means that you have had knowledge
of the CeCILL license and that you accept its terms.


Copyright
---------

* Copyright The Internet Society (2005) (RFC 4646)
* Copyright http://www.iso639-3.sil.org (language codes)
* Copyright Daniel Berthereau, 2019 (see [Daniel-KM] on GitHub)


[Simple ISO 639]: https://github.com/Daniel-KM/Simple-ISO-639
[SIL]: http://www.iso639-3.sil.org/
[RFC 4646]: https://tools.ietf.org/html/rfc4646
[issues]: https://github.com/Daniel-KM/Simple-ISO-639/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
