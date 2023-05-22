Simple ISO 639-3
================

[Simple ISO 639-3] is a small library to convert languages codes between ISO 639-1
(two letters language codes) and ISO 639-2 (three letters). It is built from the
official standard lists ISO 639-3 of ([SIL]) and is compatible with IETF
language tags ([RFC 4646]). Languages names are available in native language,
English and French.

Note: The current standard (2007) uses the native language as a base for the
codes. For example, three letters code for `French` is `fra`, not `fre`, or, for
`Chinese`, `zho`, not `chi`. English language is deprecated.

This library is used in the module [Internationalisation] for [Omeka S] and some
other places.

The list of codes may be updated by [SIL], so some codes may be removed and some
other ones may be added regularly.


Installation
------------

This module is a composer library available on [packagist]:

```sh
composer require daniel-km/simple-iso-639-3
```


Usage
-----

Once included in your code via composer or with `require_once 'path/to/vendor/daniel-km/simple-iso-639-3/src/Iso639p3.php;'`,
you can use it like that:

```php
$languages = [
    'fr',
    'fra',
    'fre',
    'fr_FR',
    'fr-FR',
    'French',
    'frAnÇaiS',
    'frm',
    'fro',
    'fxxx',
];
$result = [];
foreach ($languages as $language) {
    $result[$language] = [
        'language'              => $language,
        'code'                  => \Iso639p3\Iso639p3::code($language),
        'short'                 => \Iso639p3\Iso639p3::code2letters($language),
        'all'                   => \Iso639p3\Iso639p3::codes($language),
        'name'                  => \Iso639p3\Iso639p3::name($language),
        'English name'          => \Iso639p3\Iso639p3::englishName($language),
        'English inverted name' => \Iso639p3\Iso639p3::englishInvertedName($language),
        'French name'           => \Iso639p3\Iso639p3::frenchName($language),
        'French inverted name'  => \Iso639p3\Iso639p3::frenchInvertedName($language),
    ];
}
print_r($result);
```

Result:

| language | code | short | all          | name     | English name                  | English inverted name          | French name                   | French inverted name          |
|----------|------|-------|--------------|----------|-------------------------------|--------------------------------|-------------------------------|-------------------------------|
| fr       | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| fra      | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| fre      | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| fr_FR    | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| fr-FR    | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| French   | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| frAnÇaiS | fra  | fr    | fr, fra, fre | français | French                        | French                         | français                      | français                      |
| frm      | frm  | frm   | frm          | françois | Middle French (ca. 1400-1600) | French, Middle (ca. 1400-1600) | moyen français (1400-1600)    | français moyen (1400-1600)    |
| fro      | fro  | fro   | fro          | Franceis | Old French (842-ca. 1400)     | French, Old (842-ca. 1400)     | ancien français (842-ca.1400) | français ancien (842-ca.1400) |
| fxxx     |      |       |              |          |                               |                                |                               |                               |


Development
-----------

The lists are automatically generated from this command:

```sh
php -f scripts/generate.php
```


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online [issues] page on GitLab.


License
-------

### Module

This module is published under the [CeCILL v2.1] license, compatible with
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
* Copyright Daniel Berthereau, 2019-2023 (see [Daniel-KM] on GitLab)


[Simple ISO 639-3]: https://gitlab.com/Daniel-KM/Simple-ISO-639-3
[SIL]: https://iso639-3.sil.org
[RFC 4646]: https://tools.ietf.org/html/rfc4646
[Internationalisation]: https://gitlab.com/Daniel-KM/Omeka-S-module-Internationalisation
[Omeka S]: https://omeka.org/s
[issues]: https://gitlab.com/Daniel-KM/Simple-ISO-639-3/-/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[GitLab]: https://gitlab.com/Daniel-KM
[Daniel-KM]: https://gitlab.com/Daniel-KM "Daniel Berthereau"
