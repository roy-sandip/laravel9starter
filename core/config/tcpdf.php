<?php

return [

    /*
        'K_PATH_MAIN'			=> '',
        'K_PATH_URL'			=> '',
        'K_PATH_FONTS'			=> 'fonts/',
        'K_PATH_IMAGES'			=> '',
        'PDF_HEADER_LOGO'		=> '',
        'PDF_HEADER_LOGO_WIDTH'	=> 0,
        'K_PATH_CACHE'			=> '/tmp/',
        */

    'K_BLANK_IMAGE'         => '_blank.png',
    'PDF_PAGE_FORMAT'       => 'A4',
    'PDF_PAGE_ORIENTATION'  => 'P',
    'PDF_CREATOR' => 'TCPDF',
    'PDF_AUTHOR' => 'TCPDF',
    'PDF_HEADER_TITLE' => 'TCPDF Default Title',
    'PDF_HEADER_STRING' => 'by Nicola Asuni - Tecnick.com\nwww.tcpdf.org',

    /**
     * Document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch].
     */
    'PDF_UNIT' => 'mm',

    /**
     * Header margin.
     */
    'PDF_MARGIN_HEADER' => 5,

    /**
     * Footer margin.
     */
    'PDF_MARGIN_FOOTER' => 10,

    /**
     * Top margin.
     */
    'PDF_MARGIN_TOP' => 27,

    /**
     * Bottom margin.
     */
    'PDF_MARGIN_BOTTOM' => 25,

    /**
     * Left margin.
     */
    'PDF_MARGIN_LEFT' => 15,

    /**
     * Right margin.
     */
    'PDF_MARGIN_RIGHT' => 15,

    /**
     * Default main font name.
     */
    'PDF_FONT_NAME_MAIN' => 'helvetica',

    /**
     * Default main font size.
     */
    'PDF_FONT_SIZE_MAIN' => 10,

    /**
     * Default data font name.
     */
    'PDF_FONT_NAME_DATA' => 'helvetica',

    /**
     * Default data font size.
     */
    'PDF_FONT_SIZE_DATA' => 8,

    /**
     * Default monospaced font name.
     */
    'PDF_FONT_MONOSPACED' => 'courier',

    /**
     * Ratio used to adjust the conversion of pixels to user units.
     */
    'PDF_IMAGE_SCALE_RATIO' => 1.25,

    /**
     * Magnification factor for titles.
     */
    'HEAD_MAGNIFICATION' => 1.1,

    /**
     * Height of cell respect font height.
     */
    'K_CELL_HEIGHT_RATIO' => 1.25,

    /**
     * Title magnification respect main font size.
     */
    'K_TITLE_MAGNIFICATION' => 1.3,

    /**
     * Reduction factor for small font.
     */
    'K_SMALL_RATIO' => 2 / 3,

    /**
     * Set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language.
     */
    'K_THAI_TOPCHARS' => true,

    /**
     * If true allows to call TCPDF methods using HTML syntax
     * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
     */
    'K_TCPDF_CALLS_IN_HTML' => true,

    /**
     * If true and PHP version is greater than 5, then the Error() method throw new exception instead of terminating the execution.
     */
    'K_TCPDF_THROW_EXCEPTION_ERROR' => true,

    /**
     * Default timezone for datetime functions
     */
    'K_TIMEZONE' => 'UTC',

];
