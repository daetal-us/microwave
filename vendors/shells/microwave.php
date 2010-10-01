<?php

class MicrowaveShell extends Shell {

	public $tasks = array('Project', 'DbConfig', 'Model', 'MicrowaveableModel', 'Controller', 'View', 'Fixture', 'Test');

	public function welcome() {
		$this->hr();
		$this->out('Microwave is the fastest way to bake!');
		$this->hr();
	}

	public function main() {
		$this->welcome();
		$this->out('Don\'t even act like you\'re not eating 8 boxes hot pockets a week.');
		$this->out('You know how this works.');
		$this->out();
		if ($this->in(__('Press 1 for express cook.', true), array('1'))) {
			$this->start();
		}
		return;
	}

	public function help() {
		$this->welcome();
		$this->out('Let\'s face it: you\'re a terrible baker. The microwave is faster and easier.');
		$this->out();
		$this->out(
			"By faster I mean way less options (to think about). \nAlso, rather than breaking on "
			. "relationship errors due to unconventional column names it just moves on.\n\n"
			. "Throw a database connection with pre-existing tables in there and press `1` for "
			. "express cook!"
		);
	}

	public function start() {

		$this->args[0] = 'all';

		if (!isset($this->params['connection']) && empty($this->connection)) {
			$this->connection = $this->DbConfig->getConfig();
		}

		foreach (array('MicrowaveableModel', 'Controller', 'View') as $task) {
			$this->{$task}->connection = $this->connection;
			$this->{$task}->interactive = false;
			$this->{$task}->execute();
		}

		$this->hr();
		$this->out();
		$this->out("	Ding!");
		$this->out();
		$this->_stop();

	}

}

?>