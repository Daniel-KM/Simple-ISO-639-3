<?php declare(strict_types=1);

namespace Iso639p3;

// For direct use.
require_once __DIR__ . '/Language.php';

use Iso639p3\Language;

/**
 * Automatically generated lists of languages from standard sources.
 *
 * See daniel-km/simple-iso-3166-1
 *
 * @link https://iso639-3.sil.org/code_tables/download_tables
 * @link https://www.loc.gov/standards/iso639-2/php/English_list.php
 * @link https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes
 * @link https://www.loc.gov/standards/iso639-2/php/French_list.php
 */
class Iso639p3
{
    /**
     * Get a normalized three letters language code from a two or three letters
     * one, or language and country, or from an IETF RFC 4646 language tag, or
     * from the English normalized name, raw or inverted.
     *
     * For performance in case of a full language, it is recommended to respect
     * standard case (lowercase or uppercase first letter) according to the
     * language.
     *
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function code($language)
    {
        $language = (string) $language;

        // The check is done on "-" too to allow RFC4646 formatted locale.
        $hasMbFunctions = function_exists('mb_strtolower');
        $lang = $hasMbFunctions
            ? mb_strtolower((string) strtok((string) strtok($language, '_'), '-'))
            : strtolower((string) strtok((string) strtok($language, '_'), '-'));
        if (isset(Language::CODES[$lang])) {
            return Language::CODES[$lang];
        }

        $code = array_search($language, Language::NAMES)
            ?: (array_search($language, Language::ENGLISH_NAMES)
                ?: (array_search($language, Language::ENGLISH_INVERTED_NAMES)
                    ?: (array_search($language, Language::FRENCH_NAMES)
                        ?: array_search($language, Language::FRENCH_INVERTED_NAMES))));
        if ($code) {
            return $code;
        }

        if ($hasMbFunctions) {
            $lower = mb_strtolower($language);
            return array_search($lower, array_map('mb_strtolower', Language::NAMES))
                ?: (array_search($lower, array_map('mb_strtolower', Language::ENGLISH_NAMES))
                    ?: (array_search($lower, array_map('mb_strtolower', Language::ENGLISH_INVERTED_NAMES))
                        ?: (array_search($lower, array_map('mb_strtolower', Language::FRENCH_NAMES))
                            ?: (array_search($lower, array_map('mb_strtolower', Language::FRENCH_INVERTED_NAMES))
                                ?: ''))));
        }

        $lower = strtolower($language);
        return array_search($lower, array_map('strtolower', Language::NAMES))
            ?: (array_search($lower, array_map('strtolower', Language::ENGLISH_NAMES))
                ?: (array_search($lower, array_map('strtolower', Language::ENGLISH_INVERTED_NAMES))
                    ?: (array_search($lower, array_map('strtolower', Language::FRENCH_NAMES))
                        ?: (array_search($lower, array_map('strtolower', Language::FRENCH_INVERTED_NAMES))
                            ?: ''))));
    }

    /**
     * Alias of code().
     *
     * @see self::code()
     * @param string $language
     * @return string
     */
    public static function code3letters($language)
    {
        return self::code($language);
    }

    /**
     * Get a normalized two letters language code from a two or three-letters
     * one, or language and country, or from an IETF RFC 4646 language tag, or
     * from the English normalized name, raw or inverted.
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function code2letters($language)
    {
        $code = self::code($language);
        return $code
            // The first code is always the two-letters one, if any.
            ? array_search($code, Language::CODES)
            : '';
    }

    /**
     * Get all variant codes of a language (generally only one, except some
     * languages).
     *
     * Examples: fr_FR => [fr, fra, fre]; or Français => [fr, fra, fre].
     *
     * @uses self::code()
     * @param string $language
     * @return array
     */
    public static function codes($language)
    {
        $code = self::code($language);
        return $code
            ? array_keys(Language::CODES, $code)
            : [];
    }

    /**
     * Get the native language name from a language string, if available.
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function name($language)
    {
        $lang = self::code($language);
        return $lang
            ? Language::NAMES[$lang]
            : '';
    }

    /**
     * Get the language name in English from a language string.
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function englishName($language)
    {
        $lang = self::code($language);
        return $lang
            ? Language::ENGLISH_NAMES[$lang]
            : '';
    }

    /**
     * Get the language inverted name in English from a language string.
     *
     * The inverted language is used to simplify listing (ordered by root
     * language).
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function englishInvertedName($language)
    {
        $lang = self::code($language);
        return $lang
            ? Language::ENGLISH_INVERTED_NAMES[$lang]
            : '';
    }

    /**
     * Get the language name in French from a language string.
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function frenchName($language)
    {
        $lang = self::code($language);
        return $lang
            ? Language::FRENCH_NAMES[$lang]
            : '';
    }

    /**
     * Get the language inverted name in French from a language string.
     *
     * The inverted language is used to simplify listing (ordered by root
     * language).
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    public static function frenchInvertedName($language)
    {
        $lang = self::code($language);
        return $lang
            ? Language::FRENCH_INVERTED_NAMES[$lang]
            : '';
    }
}
