﻿<?php
$hello3 = "eeeee";
$hello4 = 2222;

class _TEdit {

	public function getFilePath(): string {
		return hidemaru_edit_getfilepath();
	}

    public function getTotalText(): string {
		return hidemaru_edit_gettotaltext();
    }

    public function getSelectedText(): string {
		return hidemaru_edit_getselectedtext();
    }

    public function getLineText(): string {
		return hidemaru_edit_getlinetext();
    }

	public function getCursorPos(): array {
		$pos = hidemaru_edit_getcursorpos();
		return array(
			"LineNo" => $pos[0],
			"Column" => $pos[1]
		);
	}

	public function getMousePos(): array {
		$pos = hidemaru_edit_getcursorposfrommousepos();
		return array(
			"LineNo" => $pos[0],
			"Column" => $pos[1],
			"X" => $pos[2],
			"Y" => $pos[3]
		);
	}
}

class _TMacro {
	function doEval(string $expression): array {
		$success = hidemaru_macro_eval($exporession);
        if ($success) {
			return array(
				"Result" => $success,
				"Message"=> "",
				"Error"  => null
			);
        } else {
			return array(
				"Result" => 0,
				"Message"=> "",
				"Error"  => new RuntimeException("Hidemaru Macro Runtime Exception:\n" . $expression)
			);
		}
	}
}

class _TOutputPane {
	function output(string $message): bool {
		$mod_message = str_replace("\n", "\r\n", $message);
		$mod_message = str_replace("\r\r", "\r", $mod_message);
		return hidemaru_outputpane_output($mod_message);
	}

	function clear(): int {
		return hidemaru_outputpane_clear();
	}

	function push(): bool {
		return hidemaru_outputpane_push();
	}

	function pop(): bool {
		return hidemaru_outputpane_pop();
	}

	function setBaseDir(string $dirpath): bool {
		return hidemaru_outputpane_setbasedir($dirpath);
	}

	function sendMessage(int $command_id): int {
		return hidemaru_outputpane_sendmessage($command_id);
	}

	function getWindowHandle(): int {
		return hidemaru_outputpane_getwindowhandle();
	}
}

class _THidemaru {

	public $Edit;
	public $Macro;
	public $OutputPane;

	function __construct() {
		$this->Edit = new _TEdit();
		$this->Macro = new _TMacro();
		$this->OutputPane = new _TOutputPane();
	}

	function getVersion(): float {
		return hidemaru_version();
	}

	function debugInfo(string $message): void {
		hidemaru_debuginfo($message);
	}

	function getWindowHandle(): int {
		return hidemaru_getwindowhandle();
	}

    function onDisposeScope(): void {
	    if (function_exists("onDestroyScope")) {
			onDestroyScope();
		}
	}

}

$Hm = new _THidemaru();
?>