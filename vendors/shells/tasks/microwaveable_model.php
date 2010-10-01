<?php

class MicrowaveableModelTask extends ModelTask {

	/**
	 * Assembles and writes a Model file.
	 *
	 * @param mixed $name Model name or object
	 * @param mixed $data if array and $name is not an object assume bake data, otherwise boolean.
	 * @access private
	 */
	function bake($name, $data = array()) {
		if (is_object($name)) {
			if ($data == false) {
				$data = $associations = array();
				$data['associations'] = $this->doAssociations($name, $associations);
				$data['validate'] = $this->doValidation($name);
			}
			$data['primaryKey'] = $name->primaryKey;
			$data['useTable'] = $name->table;
			$data['useDbConfig'] = $name->useDbConfig;
			$data['name'] = $name = $name->name;
		} else {
			$data['name'] = $name;
		}
		$defaults = array('associations' => array(), 'validate' => array(), 'primaryKey' => 'id',
			'useTable' => null, 'useDbConfig' => 'default', 'displayField' => null);
		$data = array_merge($defaults, $data);

		if (!empty($data['associations'])) {
			foreach ($data['associations'] as $type => $associations) {
				if (!empty($associations)) {
					foreach ($associations as $key => $assocation) {
						if (in_array(
							Inflector::tableize($assocation['alias']), $this->_tables
						) === false) {
							unset($data['associations'][$type][$key]);
						}
					}
				}
				sort($data['associations'][$type]);
			}
		}

		$this->Template->set($data);
		$this->Template->set('plugin', Inflector::camelize($this->plugin));
		$out = $this->Template->generate('classes', 'model');

		$path = $this->getPath();
		$filename = $path . Inflector::underscore($name) . '.php';
		$this->out("\nBaking model class for $name...");
		$this->createFile($filename, $out);
		ClassRegistry::flush();
		return $out;
	}

}

?>