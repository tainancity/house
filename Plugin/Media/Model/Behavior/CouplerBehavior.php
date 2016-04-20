<?php
/**
 * Coupler Behavior File
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
 * @package       Media.Model.Behavior
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::uses('File', 'Utility');
App::uses('ModelBehavior', 'Model');

/**
 * Coupler Behavior Class
 *
 * Your model needs to be bound to a table. The table must have at least the
 * dirname, basename fields to make that work. Below you’ll find some example
 * SQL to alter an existent table.
 *
 * {{{
 *     ALTER TABLE `movies`
 *     ADD COLUMN `dirname` varchar(255) NOT NULL,
 *     ADD COLUMN `basename` varchar(255) NOT NULL,
 * }}}
 *
 * If you now save a record with a field named file which must contain an absolute
 * path to a file, is the path made relative (using the base path provided) and
 * then split into the dirname and basename parts which end up in the
 * corresponding fields. This way you won’t have any absolute paths in your
 * table which is more flexible (e.g. when relocating the folder with the media
 * files).
 *
 * Keeping files in sync with their records and vice versa can sometimes get
 * cumbersome. The SyncTask makes ensuring integrity easy. Just invoke it with the
 * following command from shell:
 * $cake media sync
 *
 * For more information on options and arguments for the task call:
 * $cake media help
 *
 * @see           SyncTask
 * @package       Media.Model.Behavior
 */
class CouplerBehavior extends ModelBehavior {

/**
 * Default settings
 *
 * baseDirectory
 *   An absolute path (with trailing slash) to a directory which will be stripped off the file path
 *
 * @var array
 */
	protected $_defaultSettings = array(
		'baseDirectory' => MEDIA_TRANSFER
	);

/**
 * Setup
 *
 * @see $_defaultSettings
 * @param Model $Model Model using this behavior
 * @param array $settings Configuration settings for $Model
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaultSettings;
		}

		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
	}

/**
 * Callback
 *
 * Requires `file` field to be present if a record is created.
 *
 * Handles deletion of a record and corresponding file if the `delete` field is
 * present and has not a value of either `null` or `'0'.`
 *
 * Prevents `dirname`, `basename`, `checksum` and `delete` fields to be written to
 * database.
 *
 * Parses contents of the `file` field if present and generates a normalized path
 * relative to the path set in the `baseDirectory` option.
 *
 * @param Model $Model Model using this behavior
 * @param array $options Options passed from Model::save().
 * @return mixed False if the operation should abort. Any other result will continue.
 */
	public function beforeSave(Model $Model, $options = array()) {
		if (!$Model->exists()) {
			if (!isset($Model->data[$Model->alias]['file'])) {
				//unset($Model->data[$Model->alias]);
				return true;
			}
		} else {
			if (isset($Model->data[$Model->alias]['delete'])
			&& $Model->data[$Model->alias]['delete'] !== '0') {
				$Model->delete();
				unset($Model->data[$Model->alias]);
				return true;
			}
		}

		$blacklist = array(
			'dirname', 'basename', 'checksum', 'delete'
		);
		$whitelist = array(
			'id', 'file', 'model', 'foreign_key',
			'created', 'modified', 'alternative'
		);

		foreach ($Model->data[$Model->alias] as $key => $value) {
			if (in_array($key, $whitelist)) {
				continue;
			}
			if (in_array($key, $blacklist)) {
				unset($Model->data[$Model->alias][$key]);
			}
		}

		extract($this->settings[$Model->alias]);
		/* @var $baseDirectory string */

		if (isset($Model->data[$Model->alias]['file'])) {
			$File = new File($Model->data[$Model->alias]['file']);

			/* `baseDirectory` may equal the file's directory or use backslashes */
			$dirname = substr(str_replace(
				str_replace('\\', '/', $baseDirectory),
				null,
				str_replace('\\', '/', Folder::slashTerm($File->Folder->pwd()))
			), 0, -1);

			$result = array(
				'dirname'  => $dirname,
				'basename' => $File->name,
			);

			$Model->data[$Model->alias] = array_merge($Model->data[$Model->alias], $result);
		}
		return true;
	}

/**
 * Callback, deletes file (if there's one coupled) corresponding to record. If
 * the file couldn't be deleted the callback will stop the delete operation and
 * not continue to delete the record.
 *
 * @param Model $Model Model using this behavior
 * @param boolean $cascade If true records that depend on this record will also be deleted
 * @return mixed False if the operation should abort. Any other result will continue.
 */
	public function beforeDelete(Model $Model, $cascade = true) {
		extract($this->settings[$Model->alias]);
		/* @var $baseDirectory string */

		$result = $Model->find('first', array(
			'conditions' => array($Model->primaryKey => $Model->id),
			'fields'     => array('dirname', 'basename'),
			'recursive'  => -1,
		));
		if (!$result[$Model->alias]['dirname'] || !$result[$Model->alias]['basename']) {
			return true;
		}

		$file  = $baseDirectory;
		$file .= $result[$Model->alias]['dirname'];
		$file .= DS . $result[$Model->alias]['basename'];

		$File = new File($file);
		return $File->delete();
	}

/**
 * Callback, adds the `file` field to each result.
 *
 * @param Model $Model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 */
	public function afterFind(Model $Model, $results, $primary = false) {
		if (empty($results)) {
			return $results;
		}
		extract($this->settings[$Model->alias]);
		/* @var $baseDirectory string */

		foreach ($results as &$result) {
			if (!isset($result[$Model->alias]['dirname'], $result[$Model->alias]['basename'])) {
				continue;
			}
			$file  = $baseDirectory;
			$file .= $result[$Model->alias]['dirname'];
			$file .= DS . $result[$Model->alias]['basename'];
			$file = str_replace(array('\\', '/'), DS, $file);

			$result[$Model->alias]['file'] = $file;
		}
		return $results;
	}

/**
 * Checks if an alternative text is given only if a file is submitted
 *
 * @param Model $Model Model using this behavior
 * @param array $check Value to check
 * @return boolean
 */
	public function checkRepresent(Model $Model, $check) {
		if (!isset($Model->data[$Model->alias]['file'])) {
			return true;
		}
		$value = current($check);
		return !empty($value);
	}

}
