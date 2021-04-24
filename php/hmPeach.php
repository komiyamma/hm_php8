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

	/**
	 * 現在編集しているテキストのファイルのフルパスを取得する。
	 * 無題の時は空文字が返ってくる。
	 */
	public function getFilePath(): string {
		return hidemaru_edit_getfilepath();
	}

	/**
	 * 現在のテキスト内容全体を取得する。
	 */
    public function getTotalText(): string {
		return hidemaru_edit_gettotaltext();
    }

	/**
	 * 現在のテキスト内容全体を、指定の文字列へと置き換える。
	 */
    public function setTotalText(string $text): bool {
		return hidemaru_edit_settotaltext($text);
    }

	/**
	 * 単純選択している内容を取得する（複数選択や矩形選択は対象外）
	 */	
    public function getSelectedText(): string {
		return hidemaru_edit_getselectedtext();
    }

	/**
	 * 単純選択している内容のテキストの内容を、指定のテキストへと置き換える
	 */	
    public function setSelectedText(string $text): bool {
		return hidemaru_edit_setselectedtext($text);
    }

	/**
	 * カーソルがある行のテキスト内容を取得する
	 */	
    public function getLineText(): string {
		return hidemaru_edit_getlinetext();
    }

	/**
	 * カーソルがある行のテキスト内容を、指定の文字列へと置き換える 
	 */	
    public function setLineText(string $text): bool {
		return hidemaru_edit_setlinetext($text);
    }

	/**
	 * Unicode勘定のlinenoとcolumnを得る。
	 */
	public function getCursorPos(): array {
		$pos = hidemaru_edit_getcursorpos();
		return $pos;
	}

	/**
	 * マウスの位置に対応する「Unicode勘定のlinenoとcolumn」を得る。
	 */
	public function getMousePos(): array {
		$pos = hidemaru_edit_getcursorposfrommousepos();
		return $pos;
	}
}

class _TMacro {

	/**
	 * 秀丸マクロ変数(もしくはシンボル)の値を取得する。
	 */
	function getVar(string $simbol): string|int {
		if ( is_string($simbol) ) {
			return hidemaru_macro_getvar($simbol);
		} else {
			new TypeError($simbol);
		}
	}

	/**
	 * 秀丸マクロ変数に、指定の数値もしくは文字列を代入する。
	 */
	function setVar(string $simbol, string|int|float $value): bool {
		if ( is_string($simbol) ) {
			return hidemaru_macro_setvar($simbol, strval($value));
		} else {
			new TypeError($simbol);
		}
	}

	/**
	 * 秀丸マクロを文字列で実行する。
	 * 「シングルクォーテーション」の「ヒアドキュメント」で記述するのがオススメ。
	 */
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

	/**
	 * アウトプット枠のクリア
	 */
	function clear(): int {
		return hidemaru_outputpane_clear();
	}

	/**
	 * アウトプット枠の内容を一時的に対比し、アウトプット枠をクリア
	 */
	function push(): bool {
		return hidemaru_outputpane_push();
	}

	/**
	 * push()で一時的に退避しておいた内容を、アプトプット枠へと復元
	 */
	function pop(): bool {
		return hidemaru_outputpane_pop();
	}

	/**
	 * アウトプット枠出力となる際ベースとなるディレクトリを変更する。
	 * ジャンプタグ形式などの際に影響を与える
	 */
	function setBaseDir(string $dirpath): bool {
		return hidemaru_outputpane_setbasedir($dirpath);
	}

	/**
	 * アウトプット枠へと命令を送信する。
	 * アウトプット枠のコマンド値一覧
	 *
	 * 1001 枠を閉じる
	 * 1002 中断
	 * 1005 検索
	 * 1006 次の結果
	 * 1007 前の結果
	 * 1008 タグジャンプ
	 * 1009 クリア
	 * 1010 下候補
	 * 1011 上候補
	 * 1013 すべてコピー
	 * 1014 レジストリ変更を元に色を更新（V8.30以降）
	 * 1015 先頭にカーソル移動（V8.89以降）
	 * 1016 最後にカーソル移動（V8.89以降）
	 * 1100 位置：左
	 * 1101 位置：右
	 * 1102 位置：上
	 * 1103 位置：下
	 */
	function sendMessage(int $command): int {
		return hidemaru_outputpane_sendmessage($command);
	}

	/**
	 * アウトプット枠のウィンドウハンドル。
	 * 通常はスクリプト層から利用することはないが、win32ウィンドウ関連プログラムを組む際には必要となる。
	 */
	function getWindowHandle(): int {
		return hidemaru_outputpane_getwindowhandle();
	}
}

class _THidemaru {

	/**
	 * 編集領域関連
	 */
	public $Edit;

	/**
	 * マクロ関連
	 */
	public $Macro;

	/**
	 * アウトプット枠関連
	 */
	public $OutputPane;

	function __construct() {
		$this->Edit = new _TEdit();
		$this->Macro = new _TMacro();
		$this->OutputPane = new _TOutputPane();
	}

	/**
	 * 秀丸エディタのバージョンの取得。
	 * 秀丸エディタ 「8.73 正式版」⇒「873.99」、「8.74 β6」⇒「874.06」といった浮動小数値が返ってくる。
	 */
	function getVersion(): float {
		return hidemaru_version();
	}

	/**
	 * 秀丸のウィンドウハンドル。hidemaruhandle(0)と同じ値。
	 */
	function getWindowHandle(): int {
		return hidemaru_getwindowhandle();
	}

	/**
	 * hmPeach.dll が解放される直前のタイミングで実行されるメソッド。
	 */
    function onDisposeScope(): void {
	    if (function_exists("onDestroyScope")) {
			onDestroyScope();
		}
	}

}

$Hm = new _THidemaru();
?>