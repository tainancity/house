<?php
/**
 * Plugin Configuration File
 *
 * In order to make the plugin work you must include this file
 * within either your app’s `core.php` or `bootstrap.php`.
 *
 * To overwrite defaults you'll define constants before including this file,
 * and overwrite other settings selectively with `Configure::write()`
 * calls after including it.
 *
 * {{{
 *     define('MEDIA', ROOT . DS . 'media' . DS);
 *     require APP . 'Plugin' . DS . 'Media' . DS . 'Config' . DS . 'core.php';
 *
 *     Configure::write('Media.filter.document.xs', array(
 *         'convert' => 'image/png',  'compress' => 9.6, 'zoomCrop' => array(16,16)
 *     ));
 * }}}
 *
 * Copyright (c) 2007-2011 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP 5
 * CakePHP 2
 *
 * @copyright     2007-2012 David Persson <davidpersson@gmx.de>
 * @link          http://github.com/davidpersson/media
 * @package       Media.Config
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Directory paths
 *
 * You can also customize the directory locations or the URLs their contents
 * are served under. Each one of the basic three directory types can be
 * configured by defining constants.
 *
 * Example:
 * 	`'/var/www/example.org/htdocs/app/webroot/media/'`
 *
 * Directory Structure
 * -------------------
 * The plugin's components are capable of adapting to custom directory
 * structures by modifying the MEDIA_* path and URL constants. It's
 * recommended (but not required) to use the following structure in combination
 * with the plugin. Components of the plugin are by default expecting it to
 * be organized this way.
 *
 * - webroot/media: The base directory.
 * -- static: Files required by the application.
 * --- doc: Documents i.e. PDF.
 * --- gen: Everything else.
 * --- img: Images.
 * -- filter: Directory holding generated file versions from _static_ or _transfer_.
 * --- xs
 * --- ...other version names...
 * -- transfer; User uploaded files.
 * --- ...
 *
 * The _static_ directory and all it's content must be *readable* by the
 * effective user.  The _filter_ and the _transfer_ directory must be
 * *read/writable* by the effective user.
 *
 * You can initialize the directory structure from the command line with:
 * $ cake media init
 *
 * Transfer Directory
 * ------------------
 * To protect transferred files from becoming a security issue (most exploits
 * don't affect i.e. resized images) or for being able to password protect those
 * files you can use the following methods.
 *
 * 1. Put a .htaccess file in the directory (in case you're using Apache).
 *    {{{
 *      # webroot/media/transfer/.htaccess
 *      Order deny,allow
 *      Deny from all
 *    }}}
 *
 * 2. Create the .htaccess file from the shell with:
 *    $ cake media protect
 *
 * 3. Relocate the transfer directory by defining the following constants.
 *    The first definition points to the new location of the directory, the second
 *    disables generation of URLs for files below the transfer directory.
 *    {{{
 *        define('MEDIA_TRANSFER', APP . 'transfer' . DS);
 *        define('MEDIA_TRANSFER_URL', false);
 *    }}}
 */
if (!defined('MEDIA')) {
	define('MEDIA', WWW_ROOT . 'media' . DS);
}
if (!defined('MEDIA_STATIC')) {
	define('MEDIA_STATIC', MEDIA . 'static' . DS);
}
if (!defined('MEDIA_FILTER')) {
	define('MEDIA_FILTER', MEDIA . 'filter' . DS);
}
if (!defined('MEDIA_TRANSFER')) {
	define('MEDIA_TRANSFER', MEDIA . 'transfer' . DS);
}
if (!defined('MEDIA_UPLOAD_TMP_DIR')) {
	define('MEDIA_UPLOAD_TMP_DIR', ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir());
}

/**
 * URL paths
 *
 * Each constant is defined with a value which is
 * an (slash terminated) URL path fragment relative to your webroot.
 *
 * In case the corresponding directory isn't served use `false` as a value.
 */
if (!defined('MEDIA_URL')) {
	define('MEDIA_URL', 'media/');
}
if (!defined('MEDIA_STATIC_URL')) {
	define('MEDIA_STATIC_URL', MEDIA_URL . 'static/');
}
if (!defined('MEDIA_FILTER_URL')) {
	define('MEDIA_FILTER_URL', MEDIA_URL . 'filter/');
}
if (!defined('MEDIA_TRANSFER_URL')) {
	define('MEDIA_TRANSFER_URL', MEDIA_URL . 'transfer/');
}

/*
 * Test for features on this system.
 */
$hasFileinfo = extension_loaded('fileinfo');
$hasImagick = extension_loaded('imagick');

/*
 * Bootstrap the `mm` library. We are putting the library into the include path which
 * is expected (by the library) in order to be able to load classes.
 */
$mm = dirname(dirname(__FILE__)) . DS . 'Lib' . DS . 'mm';

if (strpos(ini_get('include_path'), $mm) === false) {
	ini_set('include_path', $mm . DS . 'src' . PATH_SEPARATOR . ini_get('include_path'));
}

/**
 * Configure the MIME type detection. The detection class is two headed which means it
 * uses both a glob (for matching against file extensions) and a magic adapter (for
 * detecting the type from the content of files). Available `Glob` adapters are `Apache`,
 * `Freedesktop`, `Memory` and `Php`. These adapters are also available as a `Magic`
 * variant with the addition of a `Fileinfo` magic adapter. Not all adapters require
 * a file to be passed along with the configuration.
 *
 * @see TransferBehavior
 * @see MetaBehavior
 * @see MediaHelper
 */
require_once 'Mime/Type.php';

if ($hasFileinfo) {
	Mime_Type::config('Magic', array(
		'adapter' => 'Fileinfo'
	));
} else {
	Mime_Type::config('Magic', array(
		'adapter' => 'Freedesktop',
		'file'    => $mm . DS . 'data' . DS . 'magic.db'
	));
}
if ($cached = Cache::read('mime_type_glob')) {
	Mime_Type::config('Glob', array(
		'adapter' => 'Memory'
	));
	foreach ($cached as $item) {
		Mime_Type::$glob->register($item);
	}
} else {
	Mime_Type::config('Glob', array(
		'adapter' => 'Freedesktop',
		'file'    => $mm . DS . 'data' . DS . 'glob.db'
	));
	Cache::write('mime_type_glob', Mime_Type::$glob->to('array'));
}

/**
 * Configure the adapters to be used by media process class. Adjust this
 * mapping of media names to adapters according to your environment. For example:
 * most PHP installations have GD enabled thus should choose the `Gd` adapter for
 * image transformations. However the `Imagick` adapter may be more desirable
 * in other cases and also supports transformations for documents.
 *
 * @see GeneratorBehavior
 */
require_once 'Media/Process.php';

Media_Process::config(array(
	//'audio'    => 'SoxShell',
	'document' => $hasImagick ? 'Imagick' : null,
	'image'    => $hasImagick ? 'Imagick' : 'Gd',
	//'video'    => 'FfmpegShell'
));

/**
 * Configure the adpters to be used by media info class. Adjust this
 * mapping of media names to adapters according to your environment. In contrast
 * to `Media_Proces` which operates only with one adapter per media type
 * `Media_Info` can use multiple adapter per media type.
 *
 * @see MetaBehavior
 */
require_once 'Media/Info.php';

Media_Info::config(array(
	'audio'    => array('NewWave'),
	'document' => $hasImagick ? array('Imagick') : array(),
	'image'    => $hasImagick ? array('ImageBasic', 'Imagick') : array('ImageBasic'),
	//'video'    => array()
));

/**
 * Filters and versions
 *
 * For each media type a set of filters keyed by version name is configured.
 * A filter is a set of instructions which are processed by the Media_Process class.
 *
 * For more information on available methods see the classes
 * located in `libs/mm/src/Media/Process`.
 *
 * @see GeneratorBehavior
 */
// $sRGB = $mm . DS . 'data' . DS . 'sRGB_IEC61966-2-1_black_scaled.icc';

$s = array('convert' => 'image/jpeg', 'fitCrop' => array(100, 100));
$m = array('convert' => 'image/jpeg', 'fit'     => array(300, 300));
$l = array('convert' => 'image/jpeg', 'fit'     => array(600, 600));
$original = array('clone' => 'copy');

Configure::write('Media.filter', array(
	'audio'    => compact('s', 'm'),
	'document' => compact('s', 'm', 'original'),
	'generic'  => array('original' => array('clone' => 'copy')),
	'image'    => compact('m', 'l', 'original'),
	'video'    => compact('s', 'm')
));
