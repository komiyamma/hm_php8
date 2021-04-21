<?php
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

class _THidemaru {

	public $Edit;
	public $Macro;

	function __construct() {
		$this->Edit = new _TEdit();
		$this->Macro = new _TMacro();
	}

	function getVersion(): float {
		return hidemaru_version();
	}

	function debugInfo(mixed $message): void {
		hidemaru_debuginfo(strval($message));
	}

	function getWindowHandle(): int {
		return hidemaru_getwindowhandle();
	}

    function onDestroyScope(): void {
	    if (function_exists("DestroyScope")) {
			DestroyScope();
		}
	}

}

$Hm = new _THidemaru();
?>