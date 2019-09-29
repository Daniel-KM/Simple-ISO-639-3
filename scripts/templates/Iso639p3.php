<?php
/**
 * Automatically generated lists of languages from standard sources.
 *
 * File created with command `scripts/generate.php`.
 *
 * @url https://iso639-3.sil.org/code_tables/download_tables
 * @url https://www.loc.gov/standards/iso639-2/php/English_list.php
 * @url https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes
 */
class Iso639p3
{
    // Constants are not indented for smaller file.

    const CODES = __CODES__;

    const NAMES = __NAMES__;

    const ENGLISH_NAMES = __ENGLISH_NAMES__;

    const ENGLISH_INVERTED_NAMES = __ENGLISH_INVERTED_NAMES__;

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
    static function code($language)
    {
        // The check is done on "-" too to allow RFC4646 formatted locale.
        $lang = function_exists('mb_strtolower')
            ? mb_strtolower(strtok(strtok($language, '_'), '-'))
            :  strtolower(strtok(strtok($language, '_'), '-'));
        if (isset(self::CODES[$lang])) {
            return self::CODES[$lang];
        }

        $code = array_search($language, self::NAMES)
            ?: (array_search($language, self::ENGLISH_NAMES)
                ?: array_search($language, self::ENGLISH_INVERTED_NAMES));
        if ($code) {
            return $code;
        }

        if (function_exists('mb_strtolower')) {
            $lower = mb_strtolower($language);
            return array_search($lower, array_map('mb_strtolower', self::NAMES))
                ?: (array_search($lower, array_map('mb_strtolower', self::ENGLISH_NAMES))
                    ?: (array_search($lower, array_map('mb_strtolower', self::ENGLISH_INVERTED_NAMES))
                        ?: ''));
        }

        $lower = strtolower($language);
        return array_search($lower, array_map('strtolower', self::NAMES))
            ?: (array_search($lower, array_map('strtolower', self::ENGLISH_NAMES))
                ?: (array_search($lower, array_map('strtolower', self::ENGLISH_INVERTED_NAMES))
                    ?: ''));
    }

    /**
     * Alias of code().
     *
     * @see self::code()
     * @param string $language
     * @return string
     */
    static function code3letters($language)
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
    static function code2letters($language)
    {
        $code = self::code($language);
        return $code
            // The first code is always the two-letters one, if any.
            ? array_search($code, self::CODES)
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
    static function codes($language)
    {
        $code = self::code($language);
        return $code
            ? array_keys(self::CODES, $code)
            : [];
    }

    /**
     * Get the native language name from a language string, if available.
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    static function name($language)
    {
        $lang = self::code($language);
        return $lang
            ? self::NAMES[$lang]
            : '';
    }

    /**
     * Get the language name in English from a language string.
     *
     * @uses self::code()
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
     * Get the language inverted name in English from a language string.
     *
     * The inverted language is used to simplify listing (ordered by root
     * language).
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    static function englishInvertedName($language)
    {
        $lang = self::code($language);
        return $lang
            ? self::ENGLISH_INVERTED_NAMES[$lang]
            : '';
    }
}
