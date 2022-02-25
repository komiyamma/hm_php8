<?php
/*-------------------- coding: utf-8 ---------------------------
 * hmPeach 1.9.3.1用 ライブラリ
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

class _TMacroStatement {
    public function __call($statement_name, $args) {
       return $Hm->Macro->__Statement($statement_name, ...$args);
    }
}
class _TMacroFunction {
    public function __call($function_name, $args) {
       return $Hm->Macro->__Function($function_name, ...$args);
    }
}

class _TMacro {

    public $doStatement;
    public $doFunction;

    public function __construct() {
        $this->doStatement = new _TMacroStatement();
        $this->doFunction = new _TMacroFunction();
	}
    /**
     * 秀丸マクロ実行中かどうかの判定。原則的に、hmPeachではtrueが返る。
     */
    public function isExecuting(): bool {
        return hidemaru_macro_isexecuting();
    }

    /**
     * 秀丸マクロ変数(もしくはシンボル)の値を取得する。
     */
    public function getVar(string $simbol): string|int {
        if ( is_string($simbol) ) {
            return hidemaru_macro_getvar($simbol);
        } else {
            new TypeError($simbol);
        }
    }

    /**
     * 秀丸マクロ変数に、指定の数値もしくは文字列を代入する。
     */
    public function setVar(string $simbol, string|int|float|bool $value): bool {
        if ( is_string($simbol) ) {
            if (is_bool($value) || is_float($value)) {
                $value = intval($value); // 一度整数にまるめてから...
                return hidemaru_macro_setvar($simbol, strval($value));
            } else {
                return hidemaru_macro_setvar($simbol, strval($value));
            }
        } else {
            new TypeError($simbol);
        }
    }

    /**
     * 秀丸マクロを文字列で実行する。
     * 「シングルクォーテーション」の「ヒアドキュメント」で記述するのがオススメ。
     */
    public function doEval(string $expression): array {
        $success = hidemaru_macro_eval($expression);
        if ($success) {
            return array($success, null, "");
        } else {
            return array(0, new RuntimeException("Hidemaru Macro doEval(...):\n" . $expression), "");
        }
    }

    function __Function(string $function_name, ...$args): array {

		list($args_key, $args_value) = $this->_setMacroVarAndMakeMacroKeyArray($args);

        $arg_varname_join = join(',', $args_key);
        $expression = $function_name . '(' . $arg_varname_join . ')';
        list($success, $result) = hidemaru_macro_eval_function($expression);

		$args_result = $this->_clearMacroVarAndUpdateArgs($args_key, $args_value);
		if ($success) {
	        return array($result, $args_result, null, "");
		} else {
	        return array(null, $args_result, new RuntimeException("Hidemaru Macro doFunction(...):\n" . $function_name), "");
		}
    }

    function __Statement(string $statement_name, ...$args): array {

		list($args_key, $args_value) = $this->_setMacroVarAndMakeMacroKeyArray($args);

        $arg_varname_join = join(',', $args_key);
        $expression = $statement_name . ' ' . $arg_varname_join . ';';
        $result_array = $Hm->Macro->doEval($expression);

		$args_result = $this->_clearMacroVarAndUpdateArgs($args_key, $args_value);

        return array($result_array[0], $args_result, $result_array[1], $result_array[2]);
    }

    private function _setMacroVarAndMakeMacroKeyArray(array $args) {
        $base_random = strval(rand (1, 10000));
        $curr_random = strval(rand (1, 10000));

		$args_key = array();
        $args_value = array();

        for ( $ix = 0; $ix < count($args); $ix++ ) {
            $item = $args[$ix];
            if ( is_int($item) || is_float($item) || is_bool($item) ) {
                $value = intval($item);
                $varname = '#AsMacroArs_' . strval($base_random) . '_' . strval($curr_random + $ix);
                array_push($args_key, $varname);
                array_push($args_value, $value);
                $Hm->Macro->setVar($varname, $value);
            } else if ( is_array($item) ) {
                array_push($args_value, $item);
                $intcheck_array = array_filter($item, function($elem) { return is_int($elem) || is_float($elem) || is_bool($elem); } );
                if (count($item) == count($intcheck_array) ) {
                    $varname = '#AsIntArrayOfMacroArs_' . strval($base_random) . '_' . strval($curr_random + $ix);
                    array_push($args_key, $varname);
                    for ( $aix = 0; $aix < count($item); $aix++ ) {
                        $elem = $item[$aix];
                        $value = intval($elem);
                        $index_varname = $varname . '[' . strval($aix) . ']';
                        $Hm->Macro->setVar($index_varname, $value);
                    }
                } else {
                    $varname = '$AsStrArrayOfMacroArs_' . strval($base_random) . '_' . strval($curr_random + $ix);
                    array_push($args_key, $varname);
                    for ( $aix = 0; $aix < count($item); $aix++ ) {
                        $elem = $item[$aix];
                        $value = strval($elem);
                        $index_varname = $varname . '[' . strval($aix) . ']';
                        $Hm->Macro->setVar($index_varname, $value);
                    }
                }
            } else {
                $value = strval($item);
                $varname = '$AsMacroArs_' . strval($base_random) . '_' . strval($curr_random + $ix);
                array_push($args_key, $varname);
                array_push($args_value, $value);
                $Hm->Macro->setVar($varname, $value);
            }
        }

		return array($args_key, $args_value);
    }

    private function _clearMacroVarAndUpdateArgs(array $args_key, array $args_value) {
        $args_result = array();

        for($ix = 0; $ix < count($args_key); $ix++) {
            $varname = $args_key[$ix];
            if ( strpos($varname, '#AsMacroArs_') === 0) {
                array_push( $args_result, $Hm->Macro->getVar($varname) );
                $Hm->Macro->setVar($varname, 0);
            }
            else if ( strpos($varname, '$AsMacroArs_') === 0) {
                array_push( $args_result, $Hm->Macro->getVar($varname) );
                $Hm->Macro->setVar($varname, "");
            }
            else if ( strpos($varname, '#AsIntArrayOfMacroArs_') === 0) {
                $arr = $args_value[$ix];
                array_push( $args_result, $arr );
                for($aix = 0; $aix < count($arr); $aix++) {
                    $index_varname = $varname . '[' . strval($aix) . ']';
                    $Hm->Macro->setVar($index_varname, 0);
                }
            }
            else if ( strpos($varname, '$AsStrArrayOfMacroArs_') === 0) {
                $arr = $args_value[$ix];
                array_push( $args_result, $arr );
                for($aix = 0; $aix < count($arr); $aix++) {
                    $index_varname = $varname . '[' . strval($aix) . ']';
                    $Hm->Macro->setVar($index_varname, "");
                }
            }
        }

        return $args_result;
    }

}

class _TOutputPane {
    /**
     * アウトプット枠への出力
     */
    public function output(string $message): bool {
        $mod_message = str_replace("\n", "\r\n", $message);
        $mod_message = str_replace("\r\r", "\r", $mod_message);
        return hidemaru_outputpane_output($mod_message);
    }

    /**
     * アウトプット枠のクリア
     */
    public function clear(): int {
        return hidemaru_outputpane_clear();
    }

    /**
     * アウトプット枠の内容を一時的に対比し、アウトプット枠をクリア
     */
    public function push(): bool {
        return hidemaru_outputpane_push();
    }

    /**
     * push()で一時的に退避しておいた内容を、アプトプット枠へと復元
     */
    public function pop(): bool {
        return hidemaru_outputpane_pop();
    }

    /**
     * アウトプット枠出力となる際ベースとなるディレクトリを変更する。
     * ジャンプタグ形式などの際に影響を与える
     */
    public function setBaseDir(string $dirpath): bool {
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
    public function sendMessage(int $command): int {
        return hidemaru_outputpane_sendmessage($command);
    }

    /**
     * アウトプット枠のウィンドウハンドル。
     * 通常はスクリプト層から利用することはないが、win32ウィンドウ関連プログラムを組む際には必要となる。
     */
    public function getWindowHandle(): int {
        return hidemaru_outputpane_getwindowhandle();
    }
}

class _TExplorerPane {
    /**
     * ファイルマネージャ枠のモード設定
     */
    public function setMode(int $mode): bool {
        return hidemaru_explorerpane_setmode($mode);
    }

    /**
     * ファイルマネージャ枠のモード取得
     */
    public function getMode(): int {
        return hidemaru_explorerpane_getmode();
    }

    /**
     * ファイルマネージャ枠にプロジェクトを読み込み
     */
    public function loadProject(string $filepath): bool {
        return hidemaru_explorerpane_loadproject($filepath);
    }

    /**
     * ファイルマネージャ枠のプロジェクトを保存
     */
    public function saveProject(string $filepath): bool {
        return hidemaru_explorerpane_saveproject($filepath);
    }

    /**
     * ファイルマネージャ枠のプロジェクトのファイルパスを取得する
     */
    public function getProject(): string {
        return hidemaru_explorerpane_getproject();
    }

    /**
     * ファイルマネージャ枠のカレントディレクトリ
     */
    public function getCurrentDir(): string {
        return hidemaru_explorerpane_getcurrentdir();
    }


    /**
     * ファイルマネージャ枠の表示がプロジェクトのとき、更新された状態であるかどうかを返します。
     */
    public function getUpdated(): bool {
        return hidemaru_explorerpane_getupdated();
    }

    /**
     * ファイルマネージャ枠へと命令を送信する。
     * ファイルマネージャ枠枠のコマンド値一覧
     *
     * 200 フォルダモード
     * 201 ファイル一覧モード
     * 202 ヒストリモード
     * 203 ウィンドウ一覧モード
     * 204 ブックマークモード
     * 205 プロジェクトモード
     * 206 フォルダ+ファイル一覧モード
     * 251 フォルダ/ファイル一覧：１つ上のフォルダへ
     * 252 同期
     * 254 枠を閉じる
     * 256 フォルダ/ファイル一覧：アドレスバー
     * 257 ツールバー：自動
     * 258 ツールバー：常にON
     * 259 ツールバー：常にOFF
     * 260 アドレスバーにフォーカス
     * 262 フォルダ/ファイル一覧：コピー
     * 263 フォルダ/ファイル一覧：切り取り
     * 264 フォルダ/ファイル一覧：貼り付け
     * 265 フォルダ/ファイル一覧：削除
     * 266 フォルダ/ファイル一覧：プロパティ
     * 267 位置：左
     * 268 位置：右
     * 269 位置：上
     * 270 位置：下
     * 300 フォルダ/ファイル一覧：ここを親にする
     * 301 フォルダ/ファイル一覧：デスクトップを親にする
     * 302 フォルダのファイル表示：なし
     * 303 フォルダのファイル表示：全て
     * 304 フォルダのファイル表示：既定のワイルドカード
     * 305 フォルダのファイル表示：カスタム
     * 400 フォルダ/ファイル一覧：サブフォルダも表示
     * 402 ファイル一覧のファイル表示：全て
     * 403 ファイル一覧のファイル表示：既定のワイルドカード
     * 404 ファイル一覧のファイル表示：カスタム
     * 450 フォルダ/ファイル一覧：フォルダ表示ON/OFF
     * 503 ヒストリ：削除
     * 510 ヒストリ：ヒストリ項目１～
     * 701 ブックマーク：ブックマークの整理
     * 800 プロジェクト：開く
     * 801 プロジェクト：名前を付けて保存
     * 802 プロジェクト：上書き保存
     * 803 プロジェクト：閉じる
     * 900 プロジェクト：追加
     * 901 プロジェクト：削除
     * 902 プロジェクト：上へ
     * 903 プロジェクト：下へ
     */
    public function sendMessage(int $command): int {
        return hidemaru_explorerpane_sendmessage($command);
    }

    /**
     * アウトプット枠のウィンドウハンドル。
     * 通常はスクリプト層から利用することはないが、win32ウィンドウ関連プログラムを組む際には必要となる。
     */
    public function getWindowHandle(): int {
        return hidemaru_explorerpane_getwindowhandle();
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

    /**
     * ファイルマネージャ枠関連
     */
    public $ExplorerPane;

    public function __construct() {
        $this->Edit = new _TEdit();
        $this->Macro = new _TMacro();
        $this->OutputPane = new _TOutputPane();
        $this->ExplorerPane = new _TExplorerPane();
    }

    /**
     * 秀丸エディタのバージョンの取得。
     * 秀丸エディタ 「8.73 正式版」⇒「873.99」、「8.74 β6」⇒「874.06」といった浮動小数値が返ってくる。
     */
    public function getVersion(): float {
        return hidemaru_version();
    }

    /**
     * 秀丸のウィンドウハンドル。hidemaruhandle(0)と同じ値。
     */
    public function getWindowHandle(): int {
        return hidemaru_getwindowhandle();
    }

    /**
     * hmPeach.dll が解放される直前のタイミングで実行されるメソッド。
     */
    public function onDisposeScope(): void {
        if (function_exists("onDestroyScope")) {
            onDestroyScope();
        }
    }

}

$Hm = new _THidemaru();
?>