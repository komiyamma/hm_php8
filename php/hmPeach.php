<?php
/*-------------------- coding: utf-8 ---------------------------
 * hmPeach 1.8.1.1用 ライブラリ
 * Copyright (c) 2021-2021 Akitsugu Komiyama
 * under the Apache License Version 2.0
 *
 * This product includes PHP, freely available from
 * <http://www.php.net/>.
 *--------------------------------------------------------------
 */



class _TEdit {

	public function getFilePath(): string {
		return hidemaru_edit_getfilepath();
	}

    public function getTotalText(): string {
		return hidemaru_edit_gettotaltext();
    }

    public function setTotalText(string $text): bool {
		return hidemaru_edit_settotaltext($text);
    }

    public function getSelectedText(): string {
		return hidemaru_edit_getselectedtext();
    }

    public function setSelectedText(string $text): bool {
		return hidemaru_edit_setselectedtext($text);
    }

    public function getLineText(): string {
		return hidemaru_edit_getlinetext();
    }

    public function setLineText(string $text): bool {
		return hidemaru_edit_setlinetext($text);
    }

	public function getCursorPos(): array {
		$pos = hidemaru_edit_getcursorpos();
		return $pos;
	}

	public function getMousePos(): array {
		$pos = hidemaru_edit_getcursorposfrommousepos();
		return $pos;
	}
}

class _TMacro {

	function getVar(string $simbol): string|int {
		if ( is_string($simbol) ) {
			return hidemaru_macro_getvar($simbol);
		} else {
			new TypeError($simbol);
		}
	}

	function setVar(string $simbol, string|int|float $value): bool {
		if ( is_string($simbol) ) {
			return hidemaru_macro_setvar($simbol, strval($value));
		} else {
			new TypeError($simbol);
		}
	}

	function doEval(string $expression): array {
		$success = hidemaru_macro_eval($expression);
        if ($success) {
			return array($success, "", null);
        } else {
			return array(0, "", new RuntimeException("Hidemaru Macro doEval(...):\n" . $expression));
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

	function sendMessage(int $command): int {
		return hidemaru_outputpane_sendmessage($command);
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