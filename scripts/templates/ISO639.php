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
     * Get a normalized three letters language from a two or three letters one,
     * or language and country, or from an IETF RFC 4646 language tag, or from
     * the English normalized name.
     *
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    static function code($language)
    {
        // The check is done on "-" too to allow RFC4646 formatted locale.
        $lang = strtolower(strtok(strtok($language, '_'), '-'));
        if (isset(self::CODES[$lang])) {
            return self::CODES[$lang];
        }

        return array_search($language, self::ENGLISH_NAMES) ?: '';
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
     * Get a normalized two letters language from a two or three-letters one, or
     * language and country, or from an IETF RFC 4646 language tag, or from the
     * English normalized name.
     *
     * @uses self::code()
     * @param string $language
     * @return string If language doesn't exist, an empty string is returned.
     */
    static function code2letters($language)
    {
        $code = self::code($language);
        return $code
            ? array_search($code, self::CODES)
            : '';
    }

    /**
     * Get the language name in English from a language code.
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
}
