<?php

/**
 * Attachment Model File
 *
 * Copyright (c) 2007-2012 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP 5
 * CakePHP 2
 *
 * @copyright     2007-2012 David Persson <davidpersson@gmx.de>
 * @link          http://github.com/davidpersson/media
 * @package       Media.Model
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::uses('MediaAppModel', 'Media.Model');

/**
 * Attachment Model Class
 *
 * A ready-to-use model combining multiple behaviors.
 *
 * @package       Media.Model
 *
 * @property CouplerBehavior $Coupler
 * @property MetaBehavior $Meta
 * @property GeneratorBehavior $Generator
 * @property PolymorphicBehavior $Polymorphic
 * @property TransferBehavior $Transfer
 */
class Attachment extends MediaAppModel {

    /**
     * actsAs property
     *
     * @var array
     */
    public $actsAs = array(
        'Media.Transfer' => array(
            'trustClient' => false,
            'transferDirectory' => MEDIA_TRANSFER,
            'createDirectory' => true,
            'alternativeFile' => 100
        ),
        'Media.Generator' => array(
            'baseDirectory' => MEDIA_TRANSFER,
            'filterDirectory' => MEDIA_FILTER,
            'createDirectory' => true,
        ),
        'Media.Polymorphic',
        'Media.Coupler' => array(
            'baseDirectory' => MEDIA_TRANSFER
        ),
        'Media.Meta' => array(
            'level' => 2
        )
    );

    /**
     * Validation rules for file and alternative fields
     *
     * For more information on the rules used here
     * see the source of TransferBehavior and MediaBehavior or
     * the test case for MediaValidation.
     *
     * If you experience problems with your model not validating,
     * try commenting the mimeType rule or providing less strict
     * settings for single rules.
     *
     * `checkExtension()` and `checkMimeType()` take both a blacklist and
     * a whitelist. If you are on windows make sure that you additionally
     * specify the `'tmp'` extension in case you are using a whitelist.
     *
     * @var array
     */
    public $validate = array(
        'file' => array(
            'resource' => array('rule' => 'checkResource'),
            'access' => array('rule' => 'checkAccess'),
            'location' => array('rule' => array('checkLocation', array(
                        MEDIA_TRANSFER, MEDIA_UPLOAD_TMP_DIR
                    ))),
            'permission' => array('rule' => array('checkPermission', '*')),
            'size' => array('rule' => array('checkSize', '5M')),
            'extension' => array('rule' => array('checkExtension', false, array(
                        'jpg', 'jpeg', 'png', 'tif', 'tiff', 'gif', 'pdf', 'tmp'
                    ))),
            'mimeType' => array('rule' => array('checkMimeType', false, array(
                        'image/jpeg', 'image/png', 'image/tiff', 'image/gif', 'application/pdf'
                    )))),
        'alternative' => array(
            'rule' => 'checkRepresent',
            'on' => 'create',
            'required' => false,
            'allowEmpty' => true,
    ));
    
    public $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * Supply a fancy Path field
     *
     * @var array
     */
    public $virtualFields = array(
        'path' => "CONCAT_WS('/', dirname, basename)"
    );

    /**
     * Generate a version of a file
     *
     * Uncomment to force Generator Behavior to use this method when
     * generating versions of files.
     *
     * If you want to fall back from your code back to the default method use:
     * `return $this->Behaviors->Generator->makeVersion($this, $file, $process);`
     *
     * $process an array with the following contents:
     *  directory - The destination directory (If this method was called
     *              by `make()` the directory is already created)
     *  version - The version requested to be processed (e.g. `l`)
     *  instructions - An array containing which names of methods to be called
     *
     * @param file $file Absolute path to source file
     * @param array $process version, directory, instructions
     * @return boolean `true` if version for the file was successfully stored
     */
    // public function makeVersion($file, $process) {
    // }

    /**
     * Returns the relative path to the destination file
     *
     * Uncomment to force Transfer Behavior to use this method when
     * determining the destination path instead of the builtin one.
     *
     * @param array $via Information about the temporary file
     * @param array $from Information about the source file
     * @return string The path to the destination file or false
     */
    // public function transferTo($via, $from) {
    // }
}
