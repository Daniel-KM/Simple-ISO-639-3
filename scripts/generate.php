
<?php
require_once '/var/www/html/outils/debug.php';

/**
 * Prepare lists of languages from standard sources
 *
 * @url .
 */

$destination = dirname(__DIR__) . '/data/iso639.php';

echo 'Preparation of the languages…' . "\n";

$codes = list_codes();
if (empty($codes)) {
    echo 'Unable to create the list. Check your internet connection.' . "\n";
    exit;
}

$result = file_put_contents($destination, '');
if ($result === false) {
    echo 'Unable to create the file. Check your file system rights.' . "\n";
    exit;
}

$content = <<<'PHP'
<?php
/**
 * Automatically generated lists of languages from standard sources.
 *
 * File created with command `scripts/generate.php`.
 *
 * @url https://www.loc.gov/standards/iso639-2/php/English_list.php
 * @url https://iso639-3.sil.org/code_tables/download_tables
 */
class ISO639
{
    const CODES = __CODES__;

    const ENGLISH_NAMES = __ENGLISH_NAMES__;

    /**
     * Get a normalized three letters language from a two-letters one, or
     * language and country, or from an IETF RFC 4646 language tag.
     *
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    static function code($language)
    {
        // The check is done on "-" too to allow RFC4646 formatted locale.
        $lang = strtolower(strtok(strtok($language, '_'), '-'));
        return isset(self::CODES[$lang])
            ? self::CODES[$lang]
            : '';
    }

    /**
     * Get all standard languages by two or three letters abbreviations.
     *
     * @return array
     */
    static function codes()
    {
        return self::CODES;
    }

    /**
     * Get the language name in English from a language code.
     *
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    static function englishName($language)
    {
        $lang = self::code($language);
        return $lang
            ? self::ENGLISH_NAMES[$lang]
            : '';
    }

    /**
     * Get all standard languages English names by three letters abbreviations.
     *
     * @return array
     */
    static function englishNames()
    {
        return self::ENGLISH_NAMES;
    }
}

PHP;

// Convert into a short array.
$codes = short_array_string($codes);

$englishNames = list_english_names();
$englishNames = short_array_string($englishNames);

$replace = [
    '__CODES__' => $codes,
    '__ENGLISH_NAMES__' => $englishNames,
];

$content = str_replace(array_keys($replace), array_values($replace), $content);

file_put_contents($destination, $content);

echo 'Preparation of the languages file done.';
echo "\n";
exit;

function list_codes()
{
    $result = [];
    $data = fetch_iso639();

    foreach ($data as $language) {
        $result[$language[0]] = $language[0];
        $result[$language[1]] = $language[0];
        $result[$language[2]] = $language[0];
        $result[$language[3]] = $language[0];
    }

    unset($result['']);
    natsort($result);

    return $result;
}

function list_english_names()
{
    $result = [];
    $data = fetch_iso639();

    foreach ($data as $language) {
        $result[$language[0]] = $language[6];
    }

    return $result;
}

function fetch_iso639()
{
    static $data;

    if (is_null($data)) {
        $source = 'https://iso639-3.sil.org/sites/iso639-3/files/downloads/iso-639-3.tab';
        $content  = file_get_contents($source) ?: [];

        // Clean the table and convert it into an array..
        $content = str_replace(["\r\n", "\n\r", "\r"], ["\n", "\n", "\n"], $content);
        $data = array_map(function ($v) {
            return str_getcsv($v, "\t");
        }, explode("\n", $content));

        // Headers are: Id, Part2B, Part2T, Part1, Scope, Language_Type, Ref_Name, Comment
        // Remove the headers.
        unset($data[0]);
    }

    return $data;
}

/**
 * Use a short array for output.
 *
 * @param array $array
 * @return string
 */
function short_array_string($array)
{
    $arrayString = var_export($array, true);
    $arrayString = str_replace(['array (', ')'], ['[', '    ]'], $arrayString);
    return preg_replace("~^(  '.*)$~m", '      $1', $arrayString);
}
