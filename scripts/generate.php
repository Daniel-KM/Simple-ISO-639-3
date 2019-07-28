
<?php
require_once '/var/www/html/outils/debug.php';

/**
 * Prepare lists of languages from standard sources
 *
 * @url .
 */

echo 'Preparation of the languages…' . "\n";

$codes = list_codes();
if (empty($codes)) {
    echo 'Unable to create the list. Check your internet connection.' . "\n";
    exit;
}

$destination = dirname(__DIR__) . '/src/ISO639.php';
$result = file_put_contents($destination, '');
if ($result === false) {
    echo 'Unable to create the file. Check your file system rights.' . "\n";
    exit;
}

// Convert into a short array.
$codes = short_array_string($codes);

$englishNames = list_english_names();
$englishNames = short_array_string($englishNames);

$replace = [
    '__CODES__' => $codes,
    '__ENGLISH_NAMES__' => $englishNames,
];

$content = file_get_contents(__DIR__ . '/templates/ISO639.php');
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
