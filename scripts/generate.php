<?php declare(strict_types=1);

/**
 * Prepare lists of languages from standard sources.
 *
 * Some native names and other codes can be added via the file "extra codes".
 * Only the main native is managed.
 *
 * @link https://iso639-3.sil.org
 * @link https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes
 */

echo 'Preparation of the languages…' . "\n";

$codes = list_codes();
if (empty($codes)) {
    echo 'Unable to create the list. Check your internet connection.' . "\n";
    exit;
}

$destination = dirname(__DIR__) . '/src/Iso639p3.php';
$result = file_put_contents($destination, '');
if ($result === false) {
    echo 'Unable to create the file. Check your file system rights.' . "\n";
    exit;
}

// Convert into a short array.
$codes = short_array_string($codes);

$names = list_names();
$names = short_array_string($names);

$englishNames = list_english_names();
$englishNames = short_array_string($englishNames);

$englishInvertedNames = list_english_inverted_names();
$englishInvertedNames = short_array_string($englishInvertedNames);

$frenchNames = list_french_names();
$frenchNames = short_array_string($frenchNames);

$replace = [
    '__CODES__' => $codes,
    '__NAMES__' => $names,
    '__ENGLISH_NAMES__' => $englishNames,
    '__ENGLISH_INVERTED_NAMES__' => $englishInvertedNames,
    '__FRENCH_NAMES__' => $frenchNames,
];

$content = file_get_contents(__DIR__ . '/templates/Iso639p3.php');
$content = str_replace(array_keys($replace), array_values($replace), $content);
file_put_contents($destination, $content);

echo 'Preparation of the languages file done.';
echo "\n";
exit;

function list_codes()
{
    $result = [];
    $data = fetch_iso_639_3();

    foreach ($data as $language) {
        // Code 3 letters.
        $result[$language[0]] = $language[0];
        // Code 3 letters bis.
        $result[$language[1]] = $language[0];
        // Code 3 letters ter.
        $result[$language[2]] = $language[0];
        // Code 2 letters.
        $result[$language[3]] = $language[0];
    }
    unset($result['']);

    $extra = require __DIR__ . '/extra_codes.php';
    $result += $extra['CODES'];
    ksort($result);

    return $result;
}

function list_names()
{
    $result = fetch_wikipedia_list_en();

    $extra = require __DIR__ . '/extra_codes.php';
    $result += $extra['NAMES'];
    ksort($result);

    return $result;
}

function list_english_names()
{
    $result = [];
    $data = fetch_iso_639_3();

    foreach ($data as $language) {
        $result[$language[0]] = $language[6];
    }

    $extra = require __DIR__ . '/extra_codes.php';
    $result += $extra['ENGLISH_NAMES'];
    ksort($result);

    return $result;
}

function list_english_inverted_names()
{
    $result = [];
    $data = fetch_iso_639_3_inverted();

    foreach ($data as $language) {
        $result[$language[0]] = $language[2];
    }

    $extra = require __DIR__ . '/extra_codes.php';
    $result += $extra['ENGLISH_INVERTED_NAMES'];
    ksort($result);

    return $result;
}

function list_french_names()
{
    $result = fetch_wikipedia_list_fr();

    $extra = require __DIR__ . '/extra_codes.php';
    $result += $extra['FRENCH_NAMES'];
    ksort($result);

    return $result;
}

function fetch_iso_639_3()
{
    // Headers are: Id, Part2B, Part2T, Part1, Scope, Language_Type, Ref_Name, Comment
    return fetch_iso_639('https://iso639-3.sil.org/sites/iso639-3/files/downloads/iso-639-3.tab');
}

function fetch_iso_639_3_inverted()
{
    // Headers are: Id, Print_Name, Inverted_Name
    return fetch_iso_639('https://iso639-3.sil.org/sites/iso639-3/files/downloads/iso-639-3_Name_Index.tab');
}

function fetch_iso_639($source)
{
    static $data;

    if (!isset($data[$source])) {
        $content = file_get_contents($source) ?: '';

        // Clean the table and convert it into an array..
        $content = str_replace(["\r\n", "\n\r", "\r"], ["\n", "\n", "\n"], $content);
        $data[$source] = array_map(function ($v) {
            return str_getcsv($v, "\t");
        }, explode("\n", $content));

        // Remove the headers.
        unset($data[$source][0]);
    }

    return $data[$source];
}

function fetch_wikipedia_list_en()
{
    static $data;

    $source = 'https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes';
    if (!isset($data[$source])) {
        $list = [];

        $html = file_get_contents($source) ?: '';
        libxml_use_internal_errors(true);
        $htmlDom = new \DOMDocument();
        $htmlDom->loadHTML($html);
        $xpath = new \DOMXPath($htmlDom);

        // Query for the three letters codes and the native name.
        $query = '//table[@id="iso-codes"]/tbody/tr';
        $queryCode = './td[1]/span[1]/a[1]/text()';
        $queryNative = './td[8]//text()';

        $rows = $xpath->query($query);
        if ($rows && $rows->length) {
            foreach ($rows as $row) {
                $codeCell = $xpath->query($queryCode, $row);
                if (!$codeCell->length) {
                    continue;
                }

                $code = substr(trim($codeCell->item(0)->nodeValue), 0, 3);
                $nativeCell = $xpath->query($queryNative, $row);
                $native = $nativeCell->length
                    ? trim(strtok(strtok(strtok(strtok($nativeCell->item(0)->nodeValue, ';'), '/'), ','), '('))
                    : '';
                $list[$code] = $native;
            }

            ksort($list);
        }

        $data[$source] = $list;
    }

    return $data[$source];
}

function fetch_wikipedia_list_fr()
{
    static $data;

    $source = 'https://fr.wikipedia.org/wiki/Liste_des_codes_ISO_639-2';
    if (!isset($data[$source])) {
        $list = [];

        $html = file_get_contents($source) ?: '';
        libxml_use_internal_errors(true);
        $htmlDom = new \DOMDocument();
        $htmlDom->loadHTML($html);
        $htmlDom->loadXML($htmlDom->saveHTML());
        $xpath = new \DOMXPath($htmlDom);

        // Query for the three letters codes and the native name.
        $query = '//table[contains(@class, "wikitable")]/tbody/tr';
        $queryCode = './td[1]/text()';
        $queryLocale = './td[4]//text()';

        $rows = $xpath->query($query);
        if ($rows && $rows->length) {
            foreach ($rows as $row) {
                $codeCell = $xpath->query($queryCode, $row);
                if (!$codeCell->length) {
                    continue;
                }

                $code = substr(trim($codeCell->item(0)->nodeValue), 0, 3);
                $nativeCell = $xpath->query($queryLocale, $row);
                $native = $nativeCell->length
                    ? trim(strtok(strtok(strtok(strtok($nativeCell->item(0)->nodeValue, ';'), '/'), ','), '('))
                    : '';
                $list[$code] = $native;
            }

            ksort($list);
        }

        $data[$source] = $list;
    }

    return $data[$source];
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
    $replace = [
        'array (' => '[',
        ' => ' => '=>',
    ];
    $arrayString = trim(str_replace(array_keys($replace), array_values($replace), $arrayString));
    $arrayString = mb_substr($arrayString, 0, -1) . ']';
    return preg_replace("~^\s*('.*)$~m", '$1', $arrayString);
}
