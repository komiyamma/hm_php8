<?php
/*-------------------- coding: utf-8 ---------------------------
 * hmPeach 2.0.0.7用 ライブラリ
 * Copyright (c) 2021-2023 Akitsugu Komiyama
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

class _TFlagsEncode {
    public $Sjis = 0x01;
    public $Utf16 = 0x02;
    public $Euc = 0x03;
    public $Jis = 0x04;
    public $Utf7 = 0x05;
    public $Utf8 = 0x06;
    public $Utf16_be = 0x07;
    public $Euro = 0x08;
    public $Gb2312 = 0x09;
    public $Big5 = 0x0a;
    public $Euckr = 0x0b;
    public $Johab = 0x0c;
    public $Easteuro = 0x0d;
    public $Baltic = 0x0e;
    public $Greek = 0x0f;
    public $Russian = 0x10;
    public $Symbol = 0x11;
    public $Turkish = 0x12;
    public $Hebrew = 0x13;
    public $Arabic = 0x14;
    public $Thai = 0x15;
    public $Vietnamese = 0x16;
    public $Mac = 0x17;
    public $Oem = 0x18;
    public $Default = 0x19;
    public $Utf32 = 0x1b;
    public $Utf32_be = 0x1c;
    public $Binary = 0x1a;
    public $LF = 0x40;
    public $CR = 0x80;

    public $Bom = 0x0600;
    public $NoBom = 0x0400;
    public $Selection = 0x2000;

    public $NoAddHist = 0x0100;
    public $WS = 0x0800;
    public $WB = 0x1000;
}

class _TFlagsSearchOption {
    public $Word = 0x00000001;
    public $Casesense = 0x00000002;
    public $NoCasesense = 0x00000000;
    public $Regular = 0x00000010;
    public $NoRegular = 0x00000000;
    public $Fuzzy = 0x00000020;
    public $Hilight = 0x00003800;
    public $NoHilight = 0x00002000;
    public $LinkNext = 0x00000080;
    public $Loop = 0x01000000;

    public $MaskComment = 0x00020000;
    public $MaskIfdef = 0x00040000;
    public $MaskNormal = 0x00010000;
    public $MaskScript = 0x00080000;
    public $MaskString = 0x00100000;
    public $MaskTag = 0x00200000;
    public $MaskOnly = 0x00400000;
    public $FEnableMaskFlags = 0x00800000;

    public $FEnableReplace = 0x00000004;
    public $Ask = 0x00000008;
    public $NoClose = 0x02000000;

    public $SubDir = 0x00000100;
    public $Icon = 0x00000200;
    public $Filelist = 0x00000040;
    public $FullPath = 0x00000400;
    public $OutputSingle = 0x10000000;
    public $OutputSameTab = 0x20000000;

    public $BackUp = 0x04000000;
    public $Preview = 0x08000000;

    public $FEnableSearchOption2 = 0x80000000;
}

class _TFlagsSearchOption2 {
    public $UnMatch = 0x00000001;
    public $InColorMarker = 0x00000002;
    public $FGrepFormColumn = 0x00000008;
    public $FGrepFormHitOnly = 0x00000010;
    public $FGrepFormSortDate = 0x00000020;
}

class _TFlags {
    public $Encode;
    public $SearchOption;
    public $SearchOption2;

    public function __construct() {
        $this->Encode = new _TFlagsEncode();
        $this->SearchOption = new _TFlagsSearchOption();
        $this->SearchOption2 = new _TFlagsSearchOption2();
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
    public $Flags;

    public function __construct() {
        $this->doStatement = new _TMacroStatement();
        $this->doFunction = new _TMacroFunction();
        $this->Flags = new _TFlags();
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


    function doProxyMethod(string $name, string $t, ...$args) {
        if ($t == "fn" ||  $t == "fs") {
            $count = count($args);
            if ($count == 0) {
                return $Hm->Macro->getVar($name);
            }
            else if ($count > 0) {
                list($_result, $_args, $_error, $_message) = $this->__Function($name, ...$args);
                return $_result;
            }
        }
        else if ($t == "fs0") {
            list($_result, $_args, $_error, $_message) = $this->__Function($name, ...$args);
            return $_result;
        }
        else if ($t == "fsn") {
            $count = count($args);
            if ($count == 0) {
                return $Hm->Macro->getVar($name);
            }
            else if ($count > 0) {
                list($_result, $_args, $_error, $_message) = $this->__Function($name, ...$args);
                return $_result;
            }
        }
        else if ($t == "fn1s") {
            $list_args = $args;
            $count = count($list_args);
            if ($count >= 1) {
                $list_args[0] = "$args[0]";
            }
            list($_result, $_args, $_error, $_message) = $this->__Function($name, ...$list_args);
            return $_result;
        }
        else if ($t == "fn1s2s") {
            $list_args = $args;
            $count = count($list_args);
            if ($count >= 1) {
                $list_args[0] = "$args[0]";
            }
            if ($count >= 2) {
                $list_args[1] = "$args[1]";
            }
            list($_result, $_args, $_error, $_message) = $this->__Function($name, ...$list_args);
            return $_result;
        }
        else if ($t == "st") {
            list($_result, $_args, $_error, $_message) = $this->__Statement($name, ...$args);
            return $_result;
        }
        else if ($t == "st1s") {
            $list_args = $args;
            $count = count($list_args);
            if ($count >= 1) {
                $list_args[0] = "$args[0]";
            }
            list($_result, $_args, $_error, $_message) = $this->__Statement($name, ...$list_args);
            return $_result;
        }
        else if ($t == "st1s2s") {
            $list_args = $args;
            $count = count($list_args);
            if ($count >= 1) {
                $list_args[0] = "$args[0]";
            }
            if ($count >= 2) {
                $list_args[1] = "$args[1]";
            }
            list($_result, $_args, $_error, $_message) = $this->__Statement($name, ...$list_args);
            return $_result;
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
        $macro_result = $Hm->Macro->getVar("result");
        $args_result = $this->_clearMacroVarAndUpdateArgs($args_key, $args_value);
        if ($result_array[0] > 0) {
            return array($macro_result, $args_result, null, $result_array[2]);
        }
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


if (!function_exists("gettotaltext")) { function gettotaltext(...$args){ return $Hm->Macro->doProxyMethod("gettotaltext", "fs0", ...$args); } }
if (!function_exists("getlinetext")) { function getlinetext(...$args){ return $Hm->Macro->doProxyMethod("getlinetext", "fs0", ...$args); } }
if (!function_exists("getselectedtext")) { function getselectedtext(...$args){ return $Hm->Macro->doProxyMethod("getselectedtext", "fs0", ...$args); } }

if (!function_exists("version")) { function version(...$args){ return $Hm->Macro->doProxyMethod("version", "fn", ...$args); } }
if (!function_exists("platform")) { function platform(...$args){ return $Hm->Macro->doProxyMethod("platform", "fn", ...$args); } }
if (!function_exists("darkmode")) { function darkmode(...$args){ return $Hm->Macro->doProxyMethod("darkmode", "fn", ...$args); } }
if (!function_exists("x")) { function x(...$args){ return $Hm->Macro->doProxyMethod("x", "fn", ...$args); } }
if (!function_exists("y")) { function y(...$args){ return $Hm->Macro->doProxyMethod("y", "fn", ...$args); } }
if (!function_exists("column")) { function column(...$args){ return $Hm->Macro->doProxyMethod("column", "fn", ...$args); } }
if (!function_exists("column_wcs")) { function column_wcs(...$args){ return $Hm->Macro->doProxyMethod("column_wcs", "fn", ...$args); } }
if (!function_exists("column_ucs4")) { function column_ucs4(...$args){ return $Hm->Macro->doProxyMethod("column_ucs4", "fn", ...$args); } }
if (!function_exists("column_cmu")) { function column_cmu(...$args){ return $Hm->Macro->doProxyMethod("column_cmu", "fn", ...$args); } }
if (!function_exists("column_gcu")) { function column_gcu(...$args){ return $Hm->Macro->doProxyMethod("column_gcu", "fn", ...$args); } }
if (!function_exists("lineno")) { function lineno(...$args){ return $Hm->Macro->doProxyMethod("lineno", "fn", ...$args); } }
if (!function_exists("tabcolumn")) { function tabcolumn(...$args){ return $Hm->Macro->doProxyMethod("tabcolumn", "fn", ...$args); } }
if (!function_exists("xview")) { function xview(...$args){ return $Hm->Macro->doProxyMethod("xview", "fn", ...$args); } }
if (!function_exists("code")) { function code(...$args){ return $Hm->Macro->doProxyMethod("code", "fn", ...$args); } }
if (!function_exists("unicode")) { function unicode(...$args){ return $Hm->Macro->doProxyMethod("unicode", "fn", ...$args); } }
if (!function_exists("colorcode")) { function colorcode(...$args){ return $Hm->Macro->doProxyMethod("colorcode", "fn", ...$args); } }
if (!function_exists("marked")) { function marked(...$args){ return $Hm->Macro->doProxyMethod("marked", "fn", ...$args); } }
if (!function_exists("lineupdated")) { function lineupdated(...$args){ return $Hm->Macro->doProxyMethod("lineupdated", "fn", ...$args); } }
if (!function_exists("xpixel")) { function xpixel(...$args){ return $Hm->Macro->doProxyMethod("xpixel", "fn", ...$args); } }
if (!function_exists("ypixel")) { function ypixel(...$args){ return $Hm->Macro->doProxyMethod("ypixel", "fn", ...$args); } }
if (!function_exists("xpixel2")) { function xpixel2(...$args){ return $Hm->Macro->doProxyMethod("xpixel2", "fn", ...$args); } }
if (!function_exists("ypixel2")) { function ypixel2(...$args){ return $Hm->Macro->doProxyMethod("ypixel2", "fn", ...$args); } }
if (!function_exists("prevposx")) { function prevposx(...$args){ return $Hm->Macro->doProxyMethod("prevposx", "fn", ...$args); } }
if (!function_exists("prevposy")) { function prevposy(...$args){ return $Hm->Macro->doProxyMethod("prevposy", "fn", ...$args); } }
if (!function_exists("lastupdatedx")) { function lastupdatedx(...$args){ return $Hm->Macro->doProxyMethod("lastupdatedx", "fn", ...$args); } }
if (!function_exists("lastupdatedy")) { function lastupdatedy(...$args){ return $Hm->Macro->doProxyMethod("lastupdatedy", "fn", ...$args); } }
if (!function_exists("mousecolumn")) { function mousecolumn(...$args){ return $Hm->Macro->doProxyMethod("mousecolumn", "fn", ...$args); } }
if (!function_exists("mouselineno")) { function mouselineno(...$args){ return $Hm->Macro->doProxyMethod("mouselineno", "fn", ...$args); } }
if (!function_exists("linecount")) { function linecount(...$args){ return $Hm->Macro->doProxyMethod("linecount", "fn", ...$args); } }
if (!function_exists("linecount2")) { function linecount2(...$args){ return $Hm->Macro->doProxyMethod("linecount2", "fn", ...$args); } }
if (!function_exists("linelen")) { function linelen(...$args){ return $Hm->Macro->doProxyMethod("linelen", "fn", ...$args); } }
if (!function_exists("linelen2")) { function linelen2(...$args){ return $Hm->Macro->doProxyMethod("linelen2", "fn", ...$args); } }
if (!function_exists("linelen_wcs")) { function linelen_wcs(...$args){ return $Hm->Macro->doProxyMethod("linelen_wcs", "fn", ...$args); } }
if (!function_exists("linelen_ucs4")) { function linelen_ucs4(...$args){ return $Hm->Macro->doProxyMethod("linelen_ucs4", "fn", ...$args); } }
if (!function_exists("linelen_cmu")) { function linelen_cmu(...$args){ return $Hm->Macro->doProxyMethod("linelen_cmu", "fn", ...$args); } }
if (!function_exists("linelen_gcu")) { function linelen_gcu(...$args){ return $Hm->Macro->doProxyMethod("linelen_gcu", "fn", ...$args); } }
if (!function_exists("tabcolumnmax")) { function tabcolumnmax(...$args){ return $Hm->Macro->doProxyMethod("tabcolumnmax", "fn", ...$args); } }
if (!function_exists("selecting")) { function selecting(...$args){ return $Hm->Macro->doProxyMethod("selecting", "fn", ...$args); } }
if (!function_exists("rectselecting")) { function rectselecting(...$args){ return $Hm->Macro->doProxyMethod("rectselecting", "fn", ...$args); } }
if (!function_exists("lineselecting")) { function lineselecting(...$args){ return $Hm->Macro->doProxyMethod("lineselecting", "fn", ...$args); } }
if (!function_exists("selectionlock")) { function selectionlock(...$args){ return $Hm->Macro->doProxyMethod("selectionlock", "fn", ...$args); } }
if (!function_exists("mouseselecting")) { function mouseselecting(...$args){ return $Hm->Macro->doProxyMethod("mouseselecting", "fn", ...$args); } }
if (!function_exists("multiselecting")) { function multiselecting(...$args){ return $Hm->Macro->doProxyMethod("multiselecting", "fn", ...$args); } }
if (!function_exists("multiselectcount")) { function multiselectcount(...$args){ return $Hm->Macro->doProxyMethod("multiselectcount", "fn", ...$args); } }
if (!function_exists("inselecting")) { function inselecting(...$args){ return $Hm->Macro->doProxyMethod("inselecting", "fn", ...$args); } }
if (!function_exists("seltopx")) { function seltopx(...$args){ return $Hm->Macro->doProxyMethod("seltopx", "fn", ...$args); } }
if (!function_exists("seltopy")) { function seltopy(...$args){ return $Hm->Macro->doProxyMethod("seltopy", "fn", ...$args); } }
if (!function_exists("selendx")) { function selendx(...$args){ return $Hm->Macro->doProxyMethod("selendx", "fn", ...$args); } }
if (!function_exists("selendy")) { function selendy(...$args){ return $Hm->Macro->doProxyMethod("selendy", "fn", ...$args); } }
if (!function_exists("seltopcolumn")) { function seltopcolumn(...$args){ return $Hm->Macro->doProxyMethod("seltopcolumn", "fn", ...$args); } }
if (!function_exists("seltoplineno")) { function seltoplineno(...$args){ return $Hm->Macro->doProxyMethod("seltoplineno", "fn", ...$args); } }
if (!function_exists("selendcolumn")) { function selendcolumn(...$args){ return $Hm->Macro->doProxyMethod("selendcolumn", "fn", ...$args); } }
if (!function_exists("selendlineno")) { function selendlineno(...$args){ return $Hm->Macro->doProxyMethod("selendlineno", "fn", ...$args); } }
if (!function_exists("seltop_wcs")) { function seltop_wcs(...$args){ return $Hm->Macro->doProxyMethod("seltop_wcs", "fn", ...$args); } }
if (!function_exists("seltop_ucs4")) { function seltop_ucs4(...$args){ return $Hm->Macro->doProxyMethod("seltop_ucs4", "fn", ...$args); } }
if (!function_exists("seltop_cmu")) { function seltop_cmu(...$args){ return $Hm->Macro->doProxyMethod("seltop_cmu", "fn", ...$args); } }
if (!function_exists("seltop_gcu")) { function seltop_gcu(...$args){ return $Hm->Macro->doProxyMethod("seltop_gcu", "fn", ...$args); } }
if (!function_exists("selend_wcs")) { function selend_wcs(...$args){ return $Hm->Macro->doProxyMethod("selend_wcs", "fn", ...$args); } }
if (!function_exists("selend_ucs4")) { function selend_ucs4(...$args){ return $Hm->Macro->doProxyMethod("selend_ucs4", "fn", ...$args); } }
if (!function_exists("selend_cmu")) { function selend_cmu(...$args){ return $Hm->Macro->doProxyMethod("selend_cmu", "fn", ...$args); } }
if (!function_exists("selend_gcu")) { function selend_gcu(...$args){ return $Hm->Macro->doProxyMethod("selend_gcu", "fn", ...$args); } }
if (!function_exists("selopenx")) { function selopenx(...$args){ return $Hm->Macro->doProxyMethod("selopenx", "fn", ...$args); } }
if (!function_exists("selopeny")) { function selopeny(...$args){ return $Hm->Macro->doProxyMethod("selopeny", "fn", ...$args); } }
if (!function_exists("windowwidth")) { function windowwidth(...$args){ return $Hm->Macro->doProxyMethod("windowwidth", "fn", ...$args); } }
if (!function_exists("windowheight")) { function windowheight(...$args){ return $Hm->Macro->doProxyMethod("windowheight", "fn", ...$args); } }
if (!function_exists("windowcx")) { function windowcx(...$args){ return $Hm->Macro->doProxyMethod("windowcx", "fn", ...$args); } }
if (!function_exists("windowcy")) { function windowcy(...$args){ return $Hm->Macro->doProxyMethod("windowcy", "fn", ...$args); } }
if (!function_exists("windowposx")) { function windowposx(...$args){ return $Hm->Macro->doProxyMethod("windowposx", "fn", ...$args); } }
if (!function_exists("windowposy")) { function windowposy(...$args){ return $Hm->Macro->doProxyMethod("windowposy", "fn", ...$args); } }
if (!function_exists("splitstate")) { function splitstate(...$args){ return $Hm->Macro->doProxyMethod("splitstate", "fn", ...$args); } }
if (!function_exists("splitmode")) { function splitmode(...$args){ return $Hm->Macro->doProxyMethod("splitmode", "fn", ...$args); } }
if (!function_exists("splitpos")) { function splitpos(...$args){ return $Hm->Macro->doProxyMethod("splitpos", "fn", ...$args); } }
if (!function_exists("windowstate")) { function windowstate(...$args){ return $Hm->Macro->doProxyMethod("windowstate", "fn", ...$args); } }
if (!function_exists("windowstate2")) { function windowstate2(...$args){ return $Hm->Macro->doProxyMethod("windowstate2", "fn", ...$args); } }
if (!function_exists("cxscreen")) { function cxscreen(...$args){ return $Hm->Macro->doProxyMethod("cxscreen", "fn", ...$args); } }
if (!function_exists("cyscreen")) { function cyscreen(...$args){ return $Hm->Macro->doProxyMethod("cyscreen", "fn", ...$args); } }
if (!function_exists("xworkarea")) { function xworkarea(...$args){ return $Hm->Macro->doProxyMethod("xworkarea", "fn", ...$args); } }
if (!function_exists("yworkarea")) { function yworkarea(...$args){ return $Hm->Macro->doProxyMethod("yworkarea", "fn", ...$args); } }
if (!function_exists("cxworkarea")) { function cxworkarea(...$args){ return $Hm->Macro->doProxyMethod("cxworkarea", "fn", ...$args); } }
if (!function_exists("cyworkarea")) { function cyworkarea(...$args){ return $Hm->Macro->doProxyMethod("cyworkarea", "fn", ...$args); } }
if (!function_exists("monitor")) { function monitor(...$args){ return $Hm->Macro->doProxyMethod("monitor", "fn", ...$args); } }
if (!function_exists("monitorcount")) { function monitorcount(...$args){ return $Hm->Macro->doProxyMethod("monitorcount", "fn", ...$args); } }
if (!function_exists("tabmode")) { function tabmode(...$args){ return $Hm->Macro->doProxyMethod("tabmode", "fn", ...$args); } }
if (!function_exists("tabgroup")) { function tabgroup(...$args){ return $Hm->Macro->doProxyMethod("tabgroup", "fn", ...$args); } }
if (!function_exists("tabgrouporder")) { function tabgrouporder(...$args){ return $Hm->Macro->doProxyMethod("tabgrouporder", "fn", ...$args); } }
if (!function_exists("taborder")) { function taborder(...$args){ return $Hm->Macro->doProxyMethod("taborder", "fn", ...$args); } }
if (!function_exists("tabtotal")) { function tabtotal(...$args){ return $Hm->Macro->doProxyMethod("tabtotal", "fn", ...$args); } }
if (!function_exists("tabgrouptotal")) { function tabgrouptotal(...$args){ return $Hm->Macro->doProxyMethod("tabgrouptotal", "fn", ...$args); } }
if (!function_exists("screentopy")) { function screentopy(...$args){ return $Hm->Macro->doProxyMethod("screentopy", "fn", ...$args); } }
if (!function_exists("screenleftx")) { function screenleftx(...$args){ return $Hm->Macro->doProxyMethod("screenleftx", "fn", ...$args); } }
if (!function_exists("compfilehandle")) { function compfilehandle(...$args){ return $Hm->Macro->doProxyMethod("compfilehandle", "fn", ...$args); } }
if (!function_exists("scrolllinkhandle")) { function scrolllinkhandle(...$args){ return $Hm->Macro->doProxyMethod("scrolllinkhandle", "fn", ...$args); } }
if (!function_exists("filehistcount")) { function filehistcount(...$args){ return $Hm->Macro->doProxyMethod("filehistcount", "fn", ...$args); } }

# 分岐あり
if (!function_exists("overwrite")) {
    function overwrite(...$args){
        if (count($args)==0) {
            return $Hm->Macro->doProxyMethod("overwrite", "fn", ...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("overwrite", "st1s", ...$args);
        }
    }
}

if (!function_exists("updated")) { function updated(...$args){ return $Hm->Macro->doProxyMethod("updated", "fn", ...$args); } }
if (!function_exists("updatecount")) { function updatecount(...$args){ return $Hm->Macro->doProxyMethod("updatecount", "fn", ...$args); } }
if (!function_exists("anyclipboard")) { function anyclipboard(...$args){ return $Hm->Macro->doProxyMethod("anyclipboard", "fn", ...$args); } }
if (!function_exists("inputstates")) { function inputstates(...$args){ return $Hm->Macro->doProxyMethod("inputstates", "fn", ...$args); } }
if (!function_exists("imestate")) { function imestate(...$args){ return $Hm->Macro->doProxyMethod("imestate", "fn", ...$args); } }
if (!function_exists("browsemode")) { function browsemode(...$args){ return $Hm->Macro->doProxyMethod("browsemode", "fn", ...$args); } }
if (!function_exists("keypressed")) { function keypressed(...$args){ return $Hm->Macro->doProxyMethod("keypressed", "fn", ...$args); } }
if (!function_exists("replay")) { function replay(...$args){ return $Hm->Macro->doProxyMethod("replay", "fn", ...$args); } }
if (!function_exists("searchmode")) { function searchmode(...$args){ return $Hm->Macro->doProxyMethod("searchmode", "fn", ...$args); } }
if (!function_exists("searchoption")) { function searchoption(...$args){ return $Hm->Macro->doProxyMethod("searchoption", "fn", ...$args); } }
if (!function_exists("searchoption2")) { function searchoption2(...$args){ return $Hm->Macro->doProxyMethod("searchoption2", "fn", ...$args); } }
if (!function_exists("foundtopx")) { function foundtopx(...$args){ return $Hm->Macro->doProxyMethod("foundtopx", "fn", ...$args); } }
if (!function_exists("foundtopy")) { function foundtopy(...$args){ return $Hm->Macro->doProxyMethod("foundtopy", "fn", ...$args); } }
if (!function_exists("foundendx")) { function foundendx(...$args){ return $Hm->Macro->doProxyMethod("foundendx", "fn", ...$args); } }
if (!function_exists("foundendy")) { function foundendy(...$args){ return $Hm->Macro->doProxyMethod("foundendy", "fn", ...$args); } }
if (!function_exists("foundhilighting")) { function foundhilighting(...$args){ return $Hm->Macro->doProxyMethod("foundhilighting", "fn", ...$args); } }
if (!function_exists("foundoption")) { function foundoption(...$args){ return $Hm->Macro->doProxyMethod("foundoption", "fn", ...$args); } }
if (!function_exists("readonly")) { function readonly(...$args){ return $Hm->Macro->doProxyMethod("readonly", "fn", ...$args); } }
if (!function_exists("encode")) { function encode(...$args){ return $Hm->Macro->doProxyMethod("encode", "fn", ...$args); } }
if (!function_exists("charset")) { function charset(...$args){ return $Hm->Macro->doProxyMethod("charset", "fn", ...$args); } }
if (!function_exists("bom")) { function bom(...$args){ return $Hm->Macro->doProxyMethod("bom", "fn", ...$args); } }
if (!function_exists("codepage")) { function codepage(...$args){ return $Hm->Macro->doProxyMethod("codepage", "fn", ...$args); } }
if (!function_exists("getfocus")) { function getfocus(...$args){ return $Hm->Macro->doProxyMethod("getfocus", "fn", ...$args); } }
if (!function_exists("autocompstate")) { function autocompstate(...$args){ return $Hm->Macro->doProxyMethod("autocompstate", "fn", ...$args); } }
if (!function_exists("argcount")) { function argcount(...$args){ return $Hm->Macro->doProxyMethod("argcount", "fn", ...$args); } }
if (!function_exists("compatiblemode")) { function compatiblemode(...$args){ return $Hm->Macro->doProxyMethod("compatiblemode", "fn", ...$args); } }
if (!function_exists("carettabmode")) { function carettabmode(...$args){ return $Hm->Macro->doProxyMethod("carettabmode", "fn", ...$args); } }
if (!function_exists("return_in_cell_mode")) { function return_in_cell_mode(...$args){ return $Hm->Macro->doProxyMethod("return_in_cell_mode", "fn", ...$args); } }
if (!function_exists("stophistory")) { function stophistory(...$args){ return $Hm->Macro->doProxyMethod("stophistory", "fn", ...$args); } }
if (!function_exists("fontmode")) { function fontmode(...$args){ return $Hm->Macro->doProxyMethod("fontmode", "fn", ...$args); } }
if (!function_exists("formline")) { function formline(...$args){ return $Hm->Macro->doProxyMethod("formline", "fn", ...$args); } }
if (!function_exists("configstate")) { function configstate(...$args){ return $Hm->Macro->doProxyMethod("configstate", "fn", ...$args); } }
if (!function_exists("fontsize")) { function fontsize(...$args){ return $Hm->Macro->doProxyMethod("fontsize", "fn", ...$args); } }
if (!function_exists("dayofweeknum")) { function dayofweeknum(...$args){ return $Hm->Macro->doProxyMethod("dayofweeknum", "fn", ...$args); } }
if (!function_exists("tickcount")) { function tickcount(...$args){ return $Hm->Macro->doProxyMethod("tickcount", "fn", ...$args); } }
if (!function_exists("foldable")) { function foldable(...$args){ return $Hm->Macro->doProxyMethod("foldable", "fn", ...$args); } }
if (!function_exists("folded")) { function folded(...$args){ return $Hm->Macro->doProxyMethod("folded", "fn", ...$args); } }
if (!function_exists("rangeedittop")) { function rangeedittop(...$args){ return $Hm->Macro->doProxyMethod("rangeedittop", "fn", ...$args); } }
if (!function_exists("rangeeditend")) { function rangeeditend(...$args){ return $Hm->Macro->doProxyMethod("rangeeditend", "fn", ...$args); } }
if (!function_exists("rangeeditmode")) { function rangeeditmode(...$args){ return $Hm->Macro->doProxyMethod("rangeeditmode", "fn", ...$args); } }
if (!function_exists("outlinehandle")) { function outlinehandle(...$args){ return $Hm->Macro->doProxyMethod("outlinehandle", "fn", ...$args); } }
if (!function_exists("outlinesize")) { function outlinesize(...$args){ return $Hm->Macro->doProxyMethod("outlinesize", "fn", ...$args); } }
if (!function_exists("outlineitemcount")) { function outlineitemcount(...$args){ return $Hm->Macro->doProxyMethod("outlineitemcount", "fn", ...$args); } }
if (!function_exists("val")) { function val(...$args){ return $Hm->Macro->doProxyMethod("val", "fn", ...$args); } }
if (!function_exists("ascii")) { function ascii(...$args){ return $Hm->Macro->doProxyMethod("ascii", "fn", ...$args); } }
# if (!function_exists("strlen")) { function strlen(...$args){ return $Hm->Macro->doProxyMethod("strlen", "fn", ...$args); } }
# if (!function_exists("strstr")) { function strstr(...$args){ return $Hm->Macro->doProxyMethod("strstr", "fn", ...$args); } }
if (!function_exists("strrstr")) { function strrstr(...$args){ return $Hm->Macro->doProxyMethod("strrstr", "fn", ...$args); } }
if (!function_exists("wcslen")) { function wcslen(...$args){ return $Hm->Macro->doProxyMethod("wcslen", "fn", ...$args); } }
if (!function_exists("wcsstrstr")) { function wcsstrstr(...$args){ return $Hm->Macro->doProxyMethod("wcsstrstr", "fn", ...$args); } }
if (!function_exists("wcsstrrstr")) { function wcsstrrstr(...$args){ return $Hm->Macro->doProxyMethod("wcsstrrstr", "fn", ...$args); } }
if (!function_exists("ucs4len")) { function ucs4len(...$args){ return $Hm->Macro->doProxyMethod("ucs4len", "fn", ...$args); } }
if (!function_exists("ucs4strstr")) { function ucs4strstr(...$args){ return $Hm->Macro->doProxyMethod("ucs4strstr", "fn", ...$args); } }
if (!function_exists("ucs4strrstr")) { function ucs4strrstr(...$args){ return $Hm->Macro->doProxyMethod("ucs4strrstr", "fn", ...$args); } }
if (!function_exists("cmulen")) { function cmulen(...$args){ return $Hm->Macro->doProxyMethod("cmulen", "fn", ...$args); } }
if (!function_exists("cmustrstr")) { function cmustrstr(...$args){ return $Hm->Macro->doProxyMethod("cmustrstr", "fn", ...$args); } }
if (!function_exists("cmustrrstr")) { function cmustrrstr(...$args){ return $Hm->Macro->doProxyMethod("cmustrrstr", "fn", ...$args); } }
if (!function_exists("gculen")) { function gculen(...$args){ return $Hm->Macro->doProxyMethod("gculen", "fn", ...$args); } }
if (!function_exists("gcustrstr")) { function gcustrstr(...$args){ return $Hm->Macro->doProxyMethod("gcustrstr", "fn", ...$args); } }
if (!function_exists("gcustrrstr")) { function gcustrrstr(...$args){ return $Hm->Macro->doProxyMethod("gcustrrstr", "fn", ...$args); } }
if (!function_exists("wcs_to_char")) { function wcs_to_char(...$args){ return $Hm->Macro->doProxyMethod("wcs_to_char", "fn", ...$args); } }
if (!function_exists("char_to_wcs")) { function char_to_wcs(...$args){ return $Hm->Macro->doProxyMethod("char_to_wcs", "fn", ...$args); } }
if (!function_exists("ucs4_to_char")) { function ucs4_to_char(...$args){ return $Hm->Macro->doProxyMethod("ucs4_to_char", "fn", ...$args); } }
if (!function_exists("char_to_ucs4")) { function char_to_ucs4(...$args){ return $Hm->Macro->doProxyMethod("char_to_ucs4", "fn", ...$args); } }
if (!function_exists("cmu_to_char")) { function cmu_to_char(...$args){ return $Hm->Macro->doProxyMethod("cmu_to_char", "fn", ...$args); } }
if (!function_exists("char_to_cmu")) { function char_to_cmu(...$args){ return $Hm->Macro->doProxyMethod("char_to_cmu ", "fn", ...$args); } }
if (!function_exists("gcu_to_char")) { function gcu_to_char(...$args){ return $Hm->Macro->doProxyMethod("gcu_to_char", "fn", ...$args); } }
if (!function_exists("char_to_gcu")) { function char_to_gcu(...$args){ return $Hm->Macro->doProxyMethod("char_to_gcu", "fn", ...$args); } }
if (!function_exists("byteindex_to_charindex")) { function byteindex_to_charindex(...$args){ return $Hm->Macro->doProxyMethod("byteindex_to_charindex", "fn", ...$args); } }
if (!function_exists("charindex_to_byteindex")) { function charindex_to_byteindex(...$args){ return $Hm->Macro->doProxyMethod("charindex_to_byteindex", "fn", ...$args); } }
if (!function_exists("findwindow")) { function findwindow(...$args){ return $Hm->Macro->doProxyMethod("findwindow", "fn", ...$args); } }
if (!function_exists("findwindowclass")) { function findwindowclass(...$args){ return $Hm->Macro->doProxyMethod("findwindowclass", "fn", ...$args); } }
if (!function_exists("sendmessage")) { function sendmessage(...$args){ return $Hm->Macro->doProxyMethod("sendmessage", "fn", ...$args); } }
if (!function_exists("xtocolumn")) { function xtocolumn(...$args){ return $Hm->Macro->doProxyMethod("xtocolumn", "fn", ...$args); } }
if (!function_exists("ytolineno")) { function ytolineno(...$args){ return $Hm->Macro->doProxyMethod("ytolineno", "fn", ...$args); } }
if (!function_exists("columntox")) { function columntox(...$args){ return $Hm->Macro->doProxyMethod("columntox", "fn", ...$args); } }
if (!function_exists("linenotoy")) { function linenotoy(...$args){ return $Hm->Macro->doProxyMethod("linenotoy", "fn", ...$args); } }
if (!function_exists("charcount")) { function charcount(...$args){ return $Hm->Macro->doProxyMethod("charcount", "fn", ...$args); } }
if (!function_exists("existfile")) { function existfile(...$args){ return $Hm->Macro->doProxyMethod("existfile", "fn", ...$args); } }
if (!function_exists("getmaxinfo")) { function getmaxinfo(...$args){ return $Hm->Macro->doProxyMethod("getmaxinfo", "fn", ...$args); } }
if (!function_exists("keypressedex")) { function keypressedex(...$args){ return $Hm->Macro->doProxyMethod("keypressedex", "fn", ...$args); } }
if (!function_exists("setcompatiblemode")) { function setcompatiblemode(...$args){ return $Hm->Macro->doProxyMethod("setcompatiblemode", "fn", ...$args); } }
if (!function_exists("inputchar")) { function inputchar(...$args){ return $Hm->Macro->doProxyMethod("inputchar", "fn", ...$args); } }
if (!function_exists("iskeydown")) { function iskeydown(...$args){ return $Hm->Macro->doProxyMethod("iskeydown", "fn", ...$args); } }
if (!function_exists("getininum")) { function getininum(...$args){ return $Hm->Macro->doProxyMethod("getininum", "fn", ...$args); } }
if (!function_exists("getininumw")) { function getininumw(...$args){ return $Hm->Macro->doProxyMethod("getininumw", "fn", ...$args); } }
if (!function_exists("getregnum")) { function getregnum(...$args){ return $Hm->Macro->doProxyMethod("getregnum", "fn", ...$args); } }
if (!function_exists("getconfigcolor")) { function getconfigcolor(...$args){ return $Hm->Macro->doProxyMethod("getconfigcolor", "fn", ...$args); } }
if (!function_exists("hidemaruorder")) { function hidemaruorder(...$args){ return $Hm->Macro->doProxyMethod("hidemaruorder", "fn", ...$args); } }
if (!function_exists("hidemarucount")) { function hidemarucount(...$args){ return $Hm->Macro->doProxyMethod("hidemarucount", "fn", ...$args); } }
if (!function_exists("findhidemaru")) { function findhidemaru(...$args){ return $Hm->Macro->doProxyMethod("findhidemaru", "fn", ...$args); } }
if (!function_exists("hidemaruhandle")) { function hidemaruhandle(...$args){ return $Hm->Macro->doProxyMethod("hidemaruhandle", "fn", ...$args); } }
if (!function_exists("getcurrenttab")) { function getcurrenttab(...$args){ return $Hm->Macro->doProxyMethod("getcurrenttab", "fn", ...$args); } }
if (!function_exists("gettabhandle")) { function gettabhandle(...$args){ return $Hm->Macro->doProxyMethod("gettabhandle", "fn", ...$args); } }
if (!function_exists("getclipboardinfo")) { function getclipboardinfo(...$args){ return $Hm->Macro->doProxyMethod("getclipboardinfo", "fn", ...$args); } }
if (!function_exists("event")) { function event(...$args){ return $Hm->Macro->doProxyMethod("event", "fn", ...$args); } }
if (!function_exists("geteventnotify")) { function geteventnotify(...$args){ return $Hm->Macro->doProxyMethod("geteventnotify", "fn", ...$args); } }
if (!function_exists("loaddll")) { function loaddll(...$args){ return $Hm->Macro->doProxyMethod("loaddll", "fn", ...$args); } }
if (!function_exists("dllfunc")) { function dllfunc(...$args){ return $Hm->Macro->doProxyMethod("dllfunc", "fn", ...$args); } }
if (!function_exists("dllfuncw")) { function dllfuncw(...$args){ return $Hm->Macro->doProxyMethod("dllfuncw", "fn", ...$args); } }
if (!function_exists("dllfuncexist")) { function dllfuncexist(...$args){ return $Hm->Macro->doProxyMethod("dllfuncexist", "fn", ...$args); } }
if (!function_exists("createobject")) { function createobject(...$args){ return $Hm->Macro->doProxyMethod("createobject", "fn", ...$args); } }
if (!function_exists("browserpanehandle")) { function browserpanehandle(...$args){ return $Hm->Macro->doProxyMethod("browserpanehandle", "fn", ...$args); } }
if (!function_exists("browserpanesize")) { function browserpanesize(...$args){ return $Hm->Macro->doProxyMethod("browserpanesize", "fn", ...$args); } }
if (!function_exists("keyhook")) { function keyhook(...$args){ return $Hm->Macro->doProxyMethod("keyhook", "fn", ...$args); } }
if (!function_exists("registercallback")) { function registercallback(...$args){ return $Hm->Macro->doProxyMethod("registercallback", "fn", ...$args); } }

if (!function_exists("findmarker")) { function findmarker(...$args){ return $Hm->Macro->doProxyMethod("findmarker", "fs", ...$args); } }
if (!function_exists("diff")) { function diff(...$args){ return $Hm->Macro->doProxyMethod("diff", "fs", ...$args); } }
if (!function_exists("reservedmultisel")) { function reservedmultisel(...$args){ return $Hm->Macro->doProxyMethod("reservedmultisel", "fs", ...$args); } }
if (!function_exists("regulardll")) { function regulardll(...$args){ return $Hm->Macro->doProxyMethod("regulardll", "fs", ...$args); } }
if (!function_exists("filename")) { function filename(...$args){ return $Hm->Macro->doProxyMethod("filename", "fs", ...$args); } }
if (!function_exists("filename2")) { function filename2(...$args){ return $Hm->Macro->doProxyMethod("filename2", "fs", ...$args); } }
if (!function_exists("filename3")) { function filename3(...$args){ return $Hm->Macro->doProxyMethod("filename3", "fs", ...$args); } }
if (!function_exists("basename1")) { function basename1(...$args){ return $Hm->Macro->doProxyMethod("basename", "fs", ...$args); } }
if (!function_exists("basename2")) { function basename2(...$args){ return $Hm->Macro->doProxyMethod("basename2", "fs", ...$args); } }
if (!function_exists("basename3")) { function basename3(...$args){ return $Hm->Macro->doProxyMethod("basename3", "fs", ...$args); } }
if (!function_exists("directory")) { function directory(...$args){ return $Hm->Macro->doProxyMethod("directory", "fs", ...$args); } }
if (!function_exists("directory2")) { function directory2(...$args){ return $Hm->Macro->doProxyMethod("directory2", "fs", ...$args); } }
if (!function_exists("directory3")) { function directory3(...$args){ return $Hm->Macro->doProxyMethod("directory3", "fs", ...$args); } }
if (!function_exists("filetype2")) { function filetype2(...$args){ return $Hm->Macro->doProxyMethod("filetype", "fs", ...$args); } }
if (!function_exists("currentmacrofilename")) { function currentmacrofilename(...$args){ return $Hm->Macro->doProxyMethod("currentmacrofilename", "fs", ...$args); } }
if (!function_exists("currentmacrobasename")) { function currentmacrobasename(...$args){ return $Hm->Macro->doProxyMethod("currentmacrobasename", "fs", ...$args); } }
if (!function_exists("currentmacrodirectory")) { function currentmacrodirectory(...$args){ return $Hm->Macro->doProxyMethod("currentmacrodirectory", "fs", ...$args); } }
if (!function_exists("hidemarudir")) { function hidemarudir(...$args){ return $Hm->Macro->doProxyMethod("hidemarudir", "fs", ...$args); } }
if (!function_exists("macrodir")) { function macrodir(...$args){ return $Hm->Macro->doProxyMethod("macrodir", "fs", ...$args); } }
if (!function_exists("settingdir")) { function settingdir(...$args){ return $Hm->Macro->doProxyMethod("settingdir", "fs", ...$args); } }
if (!function_exists("backupdir")) { function backupdir(...$args){ return $Hm->Macro->doProxyMethod("backupdir", "fs", ...$args); } }
if (!function_exists("windir")) { function windir(...$args){ return $Hm->Macro->doProxyMethod("windir", "fs", ...$args); } }
if (!function_exists("winsysdir")) { function winsysdir(...$args){ return $Hm->Macro->doProxyMethod("winsysdir", "fs", ...$args); } }
if (!function_exists("searchbuffer")) { function searchbuffer(...$args){ return $Hm->Macro->doProxyMethod("searchbuffer", "fs", ...$args); } }
if (!function_exists("targetcolormarker")) { function targetcolormarker(...$args){ return $Hm->Macro->doProxyMethod("targetcolormarker", "fs", ...$args); } }
if (!function_exists("replacebuffer")) { function replacebuffer(...$args){ return $Hm->Macro->doProxyMethod("replacebuffer", "fs", ...$args); } }
if (!function_exists("grepfilebuffer")) { function grepfilebuffer(...$args){ return $Hm->Macro->doProxyMethod("grepfilebuffer", "fs", ...$args); } }
if (!function_exists("grepfolderbuffer")) { function grepfolderbuffer(...$args){ return $Hm->Macro->doProxyMethod("grepfolderbuffer", "fs", ...$args); } }
if (!function_exists("foundbuffer")) { function foundbuffer(...$args){ return $Hm->Macro->doProxyMethod("foundbuffer", "fs", ...$args); } }
if (!function_exists("currentconfigset")) { function currentconfigset(...$args){ return $Hm->Macro->doProxyMethod("currentconfigset", "fs", ...$args); } }
if (!function_exists("fontname")) { function fontname(...$args){ return $Hm->Macro->doProxyMethod("fontname", "fs", ...$args); } }
if (!function_exists("date1")) { function date2(...$args){ return $Hm->Macro->doProxyMethod("date", "fs", ...$args); } }
if (!function_exists("time1")) { function time2(...$args){ return $Hm->Macro->doProxyMethod("time", "fs", ...$args); } }
if (!function_exists("year")) { function year(...$args){ return $Hm->Macro->doProxyMethod("year", "fs", ...$args); } }
if (!function_exists("month")) { function month(...$args){ return $Hm->Macro->doProxyMethod("month", "fs", ...$args); } }
if (!function_exists("day")) { function day(...$args){ return $Hm->Macro->doProxyMethod("day", "fs", ...$args); } }
if (!function_exists("hour")) { function hour(...$args){ return $Hm->Macro->doProxyMethod("hour", "fs", ...$args); } }
if (!function_exists("minute")) { function minute(...$args){ return $Hm->Macro->doProxyMethod("minute", "fs", ...$args); } }
if (!function_exists("second")) { function second(...$args){ return $Hm->Macro->doProxyMethod("second", "fs", ...$args); } }
if (!function_exists("dayofweek")) { function dayofweek(...$args){ return $Hm->Macro->doProxyMethod("dayofweek", "fs", ...$args); } }
if (!function_exists("str")) { function str(...$args){ return $Hm->Macro->doProxyMethod("str", "fs", ...$args); } }
if (!function_exists("char")) { function char(...$args){ return $Hm->Macro->doProxyMethod("char", "fs", ...$args); } }
if (!function_exists("unichar")) { function unichar(...$args){ return $Hm->Macro->doProxyMethod("unichar", "fs", ...$args); } }
if (!function_exists("sprintf1")) { function sprintf2(...$args){ return $Hm->Macro->doProxyMethod("sprintf", "fs", ...$args); } }
if (!function_exists("leftstr")) { function leftstr(...$args){ return $Hm->Macro->doProxyMethod("leftstr", "fs", ...$args); } }
if (!function_exists("rightstr")) { function rightstr(...$args){ return $Hm->Macro->doProxyMethod("rightstr", "fs", ...$args); } }
if (!function_exists("midstr")) { function midstr(...$args){ return $Hm->Macro->doProxyMethod("midstr", "fs", ...$args); } }
if (!function_exists("wcsleftstr")) { function wcsleftstr(...$args){ return $Hm->Macro->doProxyMethod("wcsleftstr", "fs", ...$args); } }
if (!function_exists("wcsrightstr")) { function wcsrightstr(...$args){ return $Hm->Macro->doProxyMethod("wcsrightstr", "fs", ...$args); } }
if (!function_exists("wcsmidstr")) { function wcsmidstr(...$args){ return $Hm->Macro->doProxyMethod("wcsmidstr", "fs", ...$args); } }
if (!function_exists("ucs4leftstr")) { function ucs4leftstr(...$args){ return $Hm->Macro->doProxyMethod("ucs4leftstr", "fs", ...$args); } }
if (!function_exists("ucs4rightstr")) { function ucs4rightstr(...$args){ return $Hm->Macro->doProxyMethod("ucs4rightstr", "fs", ...$args); } }
if (!function_exists("ucs4midstr")) { function ucs4midstr(...$args){ return $Hm->Macro->doProxyMethod("ucs4midstr", "fs", ...$args); } }
if (!function_exists("cmuleftstr")) { function cmuleftstr(...$args){ return $Hm->Macro->doProxyMethod("cmuleftstr", "fs", ...$args); } }
if (!function_exists("cmurightstr")) { function cmurightstr(...$args){ return $Hm->Macro->doProxyMethod("cmurightstr", "fs", ...$args); } }
if (!function_exists("cmumidstr")) { function cmumidstr(...$args){ return $Hm->Macro->doProxyMethod("cmumidstr", "fs", ...$args); } }
if (!function_exists("gculeftstr")) { function gculeftstr(...$args){ return $Hm->Macro->doProxyMethod("gculeftstr", "fs", ...$args); } }
if (!function_exists("gcurightstr")) { function gcurightstr(...$args){ return $Hm->Macro->doProxyMethod("gcurightstr", "fs", ...$args); } }
if (!function_exists("gcumidstr")) { function gcumidstr(...$args){ return $Hm->Macro->doProxyMethod("gcumidstr", "fs", ...$args); } }
if (!function_exists("gettext1")) { function gettext1(...$args){ return $Hm->Macro->doProxyMethod("gettext", "fs", ...$args); } }
if (!function_exists("gettext2")) { function gettext2(...$args){ return $Hm->Macro->doProxyMethod("gettext2", "fs", ...$args); } }
if (!function_exists("gettext_wcs")) { function gettext_wcs(...$args){ return $Hm->Macro->doProxyMethod("gettext_wcs", "fs", ...$args); } }
if (!function_exists("gettext_ucs4")) { function gettext_ucs4(...$args){ return $Hm->Macro->doProxyMethod("gettext_ucs4", "fs", ...$args); } }
if (!function_exists("gettext_cmu")) { function gettext_cmu(...$args){ return $Hm->Macro->doProxyMethod("gettext_cmu", "fs", ...$args); } }
if (!function_exists("gettext_gcu")) { function gettext_gcu(...$args){ return $Hm->Macro->doProxyMethod("gettext_gcu", "fs", ...$args); } }
# if (!function_exists("getenv")) { function getenv(...$args){ return $Hm->Macro->doProxyMethod("getenv", "fs", ...$args); } }
if (!function_exists("getgrepfilehist")) { function getgrepfilehist(...$args){ return $Hm->Macro->doProxyMethod("getgrepfilehist", "fs", ...$args); } }
if (!function_exists("enumcolormarkerlayer")) { function enumcolormarkerlayer(...$args){ return $Hm->Macro->doProxyMethod("enumcolormarkerlayer", "fs", ...$args); } }
if (!function_exists("getfiletime")) { function getfiletime(...$args){ return $Hm->Macro->doProxyMethod("getfiletime", "fs", ...$args); } }
if (!function_exists("getoutlineitem")) { function getoutlineitem(...$args){ return $Hm->Macro->doProxyMethod("getoutlineitem", "fs", ...$args); } }
if (!function_exists("getarg")) { function getarg(...$args){ return $Hm->Macro->doProxyMethod("getarg", "fs", ...$args); } }
if (!function_exists("getautocompitem")) { function getautocompitem(...$args){ return $Hm->Macro->doProxyMethod("getautocompitem", "fs", ...$args); } }
if (!function_exists("getcolormarker")) { function getcolormarker(...$args){ return $Hm->Macro->doProxyMethod("getcolormarker", "fs", ...$args); } }
if (!function_exists("getfilehist")) { function getfilehist(...$args){ return $Hm->Macro->doProxyMethod("getfilehist", "fs", ...$args); } }
if (!function_exists("getpathhist")) { function getpathhist(...$args){ return $Hm->Macro->doProxyMethod("getpathhist", "fs", ...$args); } }
if (!function_exists("getreplacehist")) { function getreplacehist(...$args){ return $Hm->Macro->doProxyMethod("getreplacehist", "fs", ...$args); } }
if (!function_exists("getsearchhist")) { function getsearchhist(...$args){ return $Hm->Macro->doProxyMethod("getsearchhist", "fs", ...$args); } }
if (!function_exists("gettagsfile")) { function gettagsfile(...$args){ return $Hm->Macro->doProxyMethod("gettagsfile", "fs", ...$args); } }
if (!function_exists("gettitle")) { function gettitle(...$args){ return $Hm->Macro->doProxyMethod("gettitle", "fs", ...$args); } }
if (!function_exists("browsefile")) { function browsefile(...$args){ return $Hm->Macro->doProxyMethod("browsefile", "fs", ...$args); } }
if (!function_exists("quote")) { function quote(...$args){ return $Hm->Macro->doProxyMethod("quote", "fs", ...$args); } }
if (!function_exists("strreplace")) { function strreplace(...$args){ return $Hm->Macro->doProxyMethod("strreplace", "fs", ...$args); } }
# jsmodeには無いがphpには必要
if (!function_exists("encodeuri")) { function encodeuri(...$args){ return $Hm->Macro->doProxyMethod("encodeuri", "fs", ...$args); } }
if (!function_exists("decodeuri")) { function decodeuri(...$args){ return $Hm->Macro->doProxyMethod("decodeuri", "fs", ...$args); } }

# ２つの値を返す
if (!function_exists("enumregvalue")) {
    function enumregvalue(...$args){
        list($_result, $_args, $_error, $_message) = $Hm->Macro->doFunction->enumregvalue(...$args);
        return array($_result, $_args[1]);
    }
}

# ２つの値を返す
if (!function_exists("getlinecount")) {
    function getlinecount(...$args){
        list($_result, $_args, $_error, $_message) = $Hm->Macro->doFunction->getlinecount(...$args);
        return array($_result, $_args[2]);
    }
}

# 分岐あり
if (!function_exists("hidemaruversion")) {
    function hidemaruversion(...$args){
        if (count($args)>=1 && gettype($args[0]) == "string") {
            return $Hm->Macro->doProxyMethod("hidemaruversion", "st", ...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("hidemaruversion", "fs", ...$args);
        }
    }
}

# 分岐あり
if (!function_exists("toupper")) {
    function toupper(...$args){
        if (count($args)>=1 && gettype($args[0]) == "string") {
            return $Hm->Macro->doProxyMethod("toupper", "fs", ...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("toupper", "st", ...$args);
        }
    }
}

# 分岐あり
if (!function_exists("tolower")) {
    function tolower(...$args){
        if (count($args)>=1 && gettype($args[0]) == "string") {
            return $Hm->Macro->doProxyMethod("tolower", "fs", ...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("tolower", "st", ...$args);
        }
    }
}

# 分岐あり
if (!function_exists("filter")) {
    function filter(...$args){
        if (count($args)>=4) {
            return $Hm->Macro->doProxyMethod("filter", "fs", ...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("filter", "st", ...$args);
        }
    }
}

if (!function_exists("input")) { function input(...$args){ return $Hm->Macro->doProxyMethod("input", "fs", ...$args); } }
if (!function_exists("getinistr")) { function getinistr(...$args){ return $Hm->Macro->doProxyMethod("getinistr", "fs", ...$args); } }
if (!function_exists("getinistrw")) { function getinistrw(...$args){ return $Hm->Macro->doProxyMethod("getinistrw", "fs", ...$args); } }
if (!function_exists("getregbinary")) { function getregbinary(...$args){ return $Hm->Macro->doProxyMethod("getregbinary", "fs", ...$args); } }
if (!function_exists("getregstr")) { function getregstr(...$args){ return $Hm->Macro->doProxyMethod("getregstr", "fs", ...$args); } }
if (!function_exists("enumregkey")) { function enumregkey(...$args){ return $Hm->Macro->doProxyMethod("enumregkey", "fs", ...$args); } }
if (!function_exists("gettabstop")) { function gettabstop(...$args){ return $Hm->Macro->doProxyMethod("gettabstop", "fs", ...$args); } }
if (!function_exists("getstaticvariable")) { function getstaticvariable(...$args){ return $Hm->Macro->doProxyMethod("getstaticvariable", "fs", ...$args); } }
if (!function_exists("getclipboard")) { function getclipboard(...$args){ return $Hm->Macro->doProxyMethod("getclipboard", "fs", ...$args); } }
if (!function_exists("dllfuncstr")) { function dllfuncstr(...$args){ return $Hm->Macro->doProxyMethod("dllfuncstr", "fs", ...$args); } }
if (!function_exists("dllfuncstrw")) { function dllfuncstrw(...$args){ return $Hm->Macro->doProxyMethod("dllfuncstrw", "fs", ...$args); } }
if (!function_exists("getloaddllfile")) { function getloaddllfile(...$args){ return $Hm->Macro->doProxyMethod("getloaddllfile", "fs", ...$args); } }
if (!function_exists("browserpaneurl")) { function browserpaneurl(...$args){ return $Hm->Macro->doProxyMethod("browserpaneurl", "fs", ...$args); } }
if (!function_exists("browserpanecommand")) { function browserpanecommand(...$args){ return $Hm->Macro->doProxyMethod("browserpanecommand", "fs", ...$args); } }
if (!function_exists("renderpanecommand")) { function renderpanecommand(...$args){ return $Hm->Macro->doProxyMethod("renderpanecommand", "fs", ...$args); } }
if (!function_exists("getselectedrange")) { function getselectedrange(...$args){ return $Hm->Macro->doProxyMethod("getselectedrange", "fs", ...$args); } }

if (!function_exists("refreshdatetime")) { function refreshdatetime(...$args){ return $Hm->Macro->doProxyMethod("refreshdatetime", "st", ...$args); } }
if (!function_exists("newfile")) { function newfile(...$args){ return $Hm->Macro->doProxyMethod("newfile", "st", ...$args); } }
if (!function_exists("openfile")) { function openfile(...$args){ return $Hm->Macro->doProxyMethod("openfile", "st", ...$args); } }
if (!function_exists("loadfile")) { function loadfile(...$args){ return $Hm->Macro->doProxyMethod("loadfile", "st", ...$args); } }
if (!function_exists("openfilepart")) { function openfilepart(...$args){ return $Hm->Macro->doProxyMethod("openfilepart", "st", ...$args); } }
if (!function_exists("closenew")) { function closenew(...$args){ return $Hm->Macro->doProxyMethod("closenew", "st", ...$args); } }
if (!function_exists("saveas")) { function saveas(...$args){ return $Hm->Macro->doProxyMethod("saveas", "st", ...$args); } }
if (!function_exists("appendsave")) { function appendsave(...$args){ return $Hm->Macro->doProxyMethod("appendsave", "st", ...$args); } }
if (!function_exists("changename")) { function changename(...$args){ return $Hm->Macro->doProxyMethod("changename", "st", ...$args); } }
if (!function_exists("insertfile")) { function insertfile(...$args){ return $Hm->Macro->doProxyMethod("insertfile", "st", ...$args); } }
if (!function_exists("readonlyopenfile")) { function readonlyopenfile(...$args){ return $Hm->Macro->doProxyMethod("readonlyopenfile", "st", ...$args); } }
if (!function_exists("readonlyloadfile")) { function readonlyloadfile(...$args){ return $Hm->Macro->doProxyMethod("readonlyloadfile", "st", ...$args); } }
if (!function_exists("save")) { function save(...$args){ return $Hm->Macro->doProxyMethod("save", "st", ...$args); } }
if (!function_exists("savelf")) { function savelf(...$args){ return $Hm->Macro->doProxyMethod("savelf", "st", ...$args); } }
if (!function_exists("print1")) { function print2(...$args){ return $Hm->Macro->doProxyMethod("print", "st", ...$args); } }
if (!function_exists("saveall")) { function saveall(...$args){ return $Hm->Macro->doProxyMethod("saveall", "st", ...$args); } }
if (!function_exists("saveupdatedall")) { function saveupdatedall(...$args){ return $Hm->Macro->doProxyMethod("saveupdatedall", "st", ...$args); } }
if (!function_exists("openbyshell")) { function openbyshell(...$args){ return $Hm->Macro->doProxyMethod("openbyshell", "st", ...$args); } }
if (!function_exists("openbyhidemaru")) { function openbyhidemaru(...$args){ return $Hm->Macro->doProxyMethod("openbyhidemaru", "st", ...$args); } }
if (!function_exists("setfilehist")) { function setfilehist(...$args){ return $Hm->Macro->doProxyMethod("setfilehist", "st", ...$args); } }
if (!function_exists("setpathhist")) { function setpathhist(...$args){ return $Hm->Macro->doProxyMethod("setpathhist", "st", ...$args); } }
if (!function_exists("setencode")) { function setencode(...$args){ return $Hm->Macro->doProxyMethod("setencode", "st", ...$args); } }
if (!function_exists("stophistoryswitch")) { function stophistoryswitch(...$args){ return $Hm->Macro->doProxyMethod("stophistoryswitch", "st", ...$args); } }
if (!function_exists("deletefilehist")) { function deletefilehist(...$args){ return $Hm->Macro->doProxyMethod("deletefilehist", "st", ...$args); } }
if (!function_exists("OPEN_dialog")) { function OPEN_dialog(...$args){ return $Hm->Macro->doProxyMethod("OPEN", "st", ...$args); } }
if (!function_exists("SAVEAS_with_dialog")) { function SAVEAS_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("SAVEAS", "st", ...$args); } }
if (!function_exists("LOAD_with_dialog")) { function LOAD_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("LOAD", "st", ...$args); } }
if (!function_exists("APPENDSAVE_with_dialog")) { function APPENDSAVE_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("APPENDSAVE", "st", ...$args); } }
if (!function_exists("CHANGENAME_with_dialog")) { function CHANGENAME_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("CHANGENAME", "st", ...$args); } }
if (!function_exists("INSERTFILE_with_dialog")) { function INSERTFILE_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("INSERTFILE", "st", ...$args); } }
if (!function_exists("OPENFILEPART_with_dialog")) { function OPENFILEPART_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("OPENFILEPART", "st", ...$args); } }
if (!function_exists("deletefile")) { function deletefile(...$args){ return $Hm->Macro->doProxyMethod("deletefile", "st", ...$args); } }
if (!function_exists("propertydialog")) { function propertydialog(...$args){ return $Hm->Macro->doProxyMethod("propertydialog", "st", ...$args); } }

if (!function_exists("up")) { function up(...$args){ return $Hm->Macro->doProxyMethod("up", "st", ...$args); } }
if (!function_exists("down")) { function down(...$args){ return $Hm->Macro->doProxyMethod("down", "st", ...$args); } }
if (!function_exists("right")) { function right(...$args){ return $Hm->Macro->doProxyMethod("right", "st", ...$args); } }
if (!function_exists("left")) { function left(...$args){ return $Hm->Macro->doProxyMethod("left", "st", ...$args); } }
if (!function_exists("up_nowrap")) { function up_nowrap(...$args){ return $Hm->Macro->doProxyMethod("up_nowrap", "st", ...$args); } }
if (!function_exists("down_nowrap")) { function down_nowrap(...$args){ return $Hm->Macro->doProxyMethod("down_nowrap", "st", ...$args); } }
if (!function_exists("shiftup")) { function shiftup(...$args){ return $Hm->Macro->doProxyMethod("shiftup", "st", ...$args); } }
if (!function_exists("shiftdown")) { function shiftdown(...$args){ return $Hm->Macro->doProxyMethod("shiftdown", "st", ...$args); } }
if (!function_exists("shiftright")) { function shiftright(...$args){ return $Hm->Macro->doProxyMethod("shiftright", "st", ...$args); } }
if (!function_exists("shiftleft")) { function shiftleft(...$args){ return $Hm->Macro->doProxyMethod("shiftleft", "st", ...$args); } }
if (!function_exists("gofileend")) { function gofileend(...$args){ return $Hm->Macro->doProxyMethod("gofileend", "st", ...$args); } }
if (!function_exists("gofiletop")) { function gofiletop(...$args){ return $Hm->Macro->doProxyMethod("gofiletop", "st", ...$args); } }
if (!function_exists("gokakko")) { function gokakko(...$args){ return $Hm->Macro->doProxyMethod("gokakko", "st", ...$args); } }
if (!function_exists("golastupdated")) { function golastupdated(...$args){ return $Hm->Macro->doProxyMethod("golastupdated", "st", ...$args); } }
if (!function_exists("goleftkakko")) { function goleftkakko(...$args){ return $Hm->Macro->doProxyMethod("goleftkakko", "st", ...$args); } }
if (!function_exists("gorightkakko")) { function gorightkakko(...$args){ return $Hm->Macro->doProxyMethod("gorightkakko", "st", ...$args); } }
if (!function_exists("golinetop")) { function golinetop(...$args){ return $Hm->Macro->doProxyMethod("golinetop", "st", ...$args); } }
if (!function_exists("golinetop2")) { function golinetop2(...$args){ return $Hm->Macro->doProxyMethod("golinetop2", "st", ...$args); } }
if (!function_exists("golineend")) { function golineend(...$args){ return $Hm->Macro->doProxyMethod("golineend", "st", ...$args); } }
if (!function_exists("golineend2")) { function golineend2(...$args){ return $Hm->Macro->doProxyMethod("golineend2", "st", ...$args); } }
if (!function_exists("golineend3")) { function golineend3(...$args){ return $Hm->Macro->doProxyMethod("golineend3", "st", ...$args); } }
if (!function_exists("goscreenend")) { function goscreenend(...$args){ return $Hm->Macro->doProxyMethod("goscreenend", "st", ...$args); } }
if (!function_exists("goscreentop")) { function goscreentop(...$args){ return $Hm->Macro->doProxyMethod("goscreentop", "st", ...$args); } }
if (!function_exists("jump")) { function jump(...$args){ return $Hm->Macro->doProxyMethod("jump", "st", ...$args); } }
if (!function_exists("moveto")) { function moveto(...$args){ return $Hm->Macro->doProxyMethod("moveto", "st", ...$args); } }
if (!function_exists("movetolineno")) { function movetolineno(...$args){ return $Hm->Macro->doProxyMethod("movetolineno", "st", ...$args); } }
if (!function_exists("movetoview")) { function movetoview(...$args){ return $Hm->Macro->doProxyMethod("movetoview", "st", ...$args); } }
if (!function_exists("moveto2")) { function moveto2(...$args){ return $Hm->Macro->doProxyMethod("moveto2", "st", ...$args); } }
if (!function_exists("moveto_wcs")) { function moveto_wcs(...$args){ return $Hm->Macro->doProxyMethod("moveto_wcs", "st", ...$args); } }
if (!function_exists("moveto_ucs4")) { function moveto_ucs4(...$args){ return $Hm->Macro->doProxyMethod("moveto_ucs4", "st", ...$args); } }
if (!function_exists("moveto_cmu")) { function moveto_cmu(...$args){ return $Hm->Macro->doProxyMethod("moveto_cmu", "st", ...$args); } }
if (!function_exists("moveto_gcu")) { function moveto_gcu(...$args){ return $Hm->Macro->doProxyMethod("moveto_gcu", "st", ...$args); } }
if (!function_exists("nextpage")) { function nextpage(...$args){ return $Hm->Macro->doProxyMethod("nextpage", "st", ...$args); } }
if (!function_exists("prevpage")) { function prevpage(...$args){ return $Hm->Macro->doProxyMethod("prevpage", "st", ...$args); } }
if (!function_exists("halfnextpage")) { function halfnextpage(...$args){ return $Hm->Macro->doProxyMethod("halfnextpage", "st", ...$args); } }
if (!function_exists("halfprevpage")) { function halfprevpage(...$args){ return $Hm->Macro->doProxyMethod("halfprevpage", "st", ...$args); } }
if (!function_exists("rollup")) { function rollup(...$args){ return $Hm->Macro->doProxyMethod("rollup", "st", ...$args); } }
if (!function_exists("rollup2")) { function rollup2(...$args){ return $Hm->Macro->doProxyMethod("rollup2", "st", ...$args); } }
if (!function_exists("rolldown")) { function rolldown(...$args){ return $Hm->Macro->doProxyMethod("rolldown", "st", ...$args); } }
if (!function_exists("rolldown2")) { function rolldown2(...$args){ return $Hm->Macro->doProxyMethod("rolldown2", "st", ...$args); } }
if (!function_exists("wordleft")) { function wordleft(...$args){ return $Hm->Macro->doProxyMethod("wordleft", "st", ...$args); } }
if (!function_exists("wordleft2")) { function wordleft2(...$args){ return $Hm->Macro->doProxyMethod("wordleft2", "st", ...$args); } }
if (!function_exists("wordright")) { function wordright(...$args){ return $Hm->Macro->doProxyMethod("wordright", "st", ...$args); } }
if (!function_exists("wordright2")) { function wordright2(...$args){ return $Hm->Macro->doProxyMethod("wordright2", "st", ...$args); } }
if (!function_exists("wordrightsalnen")) { function wordrightsalnen(...$args){ return $Hm->Macro->doProxyMethod("wordrightsalnen", "st", ...$args); } }
if (!function_exists("wordrightsalnen2")) { function wordrightsalnen2(...$args){ return $Hm->Macro->doProxyMethod("wordrightsalnen2", "st", ...$args); } }
if (!function_exists("gowordtop")) { function gowordtop(...$args){ return $Hm->Macro->doProxyMethod("gowordtop", "st", ...$args); } }
if (!function_exists("gowordend")) { function gowordend(...$args){ return $Hm->Macro->doProxyMethod("gowordend", "st", ...$args); } }
if (!function_exists("gowordtop2")) { function gowordtop2(...$args){ return $Hm->Macro->doProxyMethod("gowordtop2", "st", ...$args); } }
if (!function_exists("gowordend2")) { function gowordend2(...$args){ return $Hm->Macro->doProxyMethod("gowordend2", "st", ...$args); } }
if (!function_exists("prevpos")) { function prevpos(...$args){ return $Hm->Macro->doProxyMethod("prevpos", "st", ...$args); } }
if (!function_exists("prevposhistback")) { function prevposhistback(...$args){ return $Hm->Macro->doProxyMethod("prevposhistback", "st", ...$args); } }
if (!function_exists("prevposhistforward")) { function prevposhistforward(...$args){ return $Hm->Macro->doProxyMethod("prevposhistforward", "st", ...$args); } }
if (!function_exists("setmark")) { function setmark(...$args){ return $Hm->Macro->doProxyMethod("setmark", "st", ...$args); } }
if (!function_exists("clearallmark")) { function clearallmark(...$args){ return $Hm->Macro->doProxyMethod("clearallmark", "st", ...$args); } }
if (!function_exists("marklist")) { function marklist(...$args){ return $Hm->Macro->doProxyMethod("marklist", "st", ...$args); } }
if (!function_exists("nextmark")) { function nextmark(...$args){ return $Hm->Macro->doProxyMethod("nextmark", "st", ...$args); } }
if (!function_exists("prevmark")) { function prevmark(...$args){ return $Hm->Macro->doProxyMethod("prevmark", "st", ...$args); } }
if (!function_exists("prevfunc")) { function prevfunc(...$args){ return $Hm->Macro->doProxyMethod("prevfunc", "st", ...$args); } }
if (!function_exists("nextfunc")) { function nextfunc(...$args){ return $Hm->Macro->doProxyMethod("nextfunc", "st", ...$args); } }
if (!function_exists("nextresult")) { function nextresult(...$args){ return $Hm->Macro->doProxyMethod("nextresult", "st", ...$args); } }
if (!function_exists("prevresult")) { function prevresult(...$args){ return $Hm->Macro->doProxyMethod("prevresult", "st", ...$args); } }
if (!function_exists("gotagpair")) { function gotagpair(...$args){ return $Hm->Macro->doProxyMethod("gotagpair", "st", ...$args); } }
if (!function_exists("backtab")) { function backtab(...$args){ return $Hm->Macro->doProxyMethod("backtab", "st", ...$args); } }
if (!function_exists("forwardtab")) { function forwardtab(...$args){ return $Hm->Macro->doProxyMethod("forwardtab", "st", ...$args); } }
if (!function_exists("appendcopy")) { function appendcopy(...$args){ return $Hm->Macro->doProxyMethod("appendcopy", "st", ...$args); } }
if (!function_exists("appendcut")) { function appendcut(...$args){ return $Hm->Macro->doProxyMethod("appendcut", "st", ...$args); } }
if (!function_exists("beginrect")) { function beginrect(...$args){ return $Hm->Macro->doProxyMethod("beginrect", "st", ...$args); } }
if (!function_exists("beginrectmulti")) { function beginrectmulti(...$args){ return $Hm->Macro->doProxyMethod("beginrectmulti", "st", ...$args); } }
if (!function_exists("beginsel")) { function beginsel(...$args){ return $Hm->Macro->doProxyMethod("beginsel", "st", ...$args); } }
if (!function_exists("beginlinesel")) { function beginlinesel(...$args){ return $Hm->Macro->doProxyMethod("beginlinesel", "st", ...$args); } }
if (!function_exists("endsel")) { function endsel(...$args){ return $Hm->Macro->doProxyMethod("endsel", "st", ...$args); } }
if (!function_exists("copy1")) { function copy1(...$args){ return $Hm->Macro->doProxyMethod("copy", "st", ...$args); } }
if (!function_exists("copy2")) { function copy2(...$args){ return $Hm->Macro->doProxyMethod("copy2", "st", ...$args); } }
if (!function_exists("cut")) { function cut(...$args){ return $Hm->Macro->doProxyMethod("cut", "st", ...$args); } }
if (!function_exists("copyline")) { function copyline(...$args){ return $Hm->Macro->doProxyMethod("copyline", "st", ...$args); } }
if (!function_exists("cutline")) { function cutline(...$args){ return $Hm->Macro->doProxyMethod("cutline", "st", ...$args); } }
if (!function_exists("cutafter")) { function cutafter(...$args){ return $Hm->Macro->doProxyMethod("cutafter", "st", ...$args); } }
if (!function_exists("copyword")) { function copyword(...$args){ return $Hm->Macro->doProxyMethod("copyword", "st", ...$args); } }
if (!function_exists("cutword")) { function cutword(...$args){ return $Hm->Macro->doProxyMethod("cutword", "st", ...$args); } }
if (!function_exists("escapeselect")) { function escapeselect(...$args){ return $Hm->Macro->doProxyMethod("escapeselect", "st", ...$args); } }
if (!function_exists("paste")) { function paste(...$args){ return $Hm->Macro->doProxyMethod("paste", "st", ...$args); } }
if (!function_exists("pasterect")) { function pasterect(...$args){ return $Hm->Macro->doProxyMethod("pasterect", "st", ...$args); } }
if (!function_exists("refpaste")) { function refpaste(...$args){ return $Hm->Macro->doProxyMethod("refpaste", "st", ...$args); } }
if (!function_exists("refcopy")) { function refcopy(...$args){ return $Hm->Macro->doProxyMethod("refcopy", "st", ...$args); } }
if (!function_exists("refcopy2")) { function refcopy2(...$args){ return $Hm->Macro->doProxyMethod("refcopy2", "st", ...$args); } }
if (!function_exists("selectall")) { function selectall(...$args){ return $Hm->Macro->doProxyMethod("selectall", "st", ...$args); } }
if (!function_exists("selectline")) { function selectline(...$args){ return $Hm->Macro->doProxyMethod("selectline", "st", ...$args); } }
if (!function_exists("selectword")) { function selectword(...$args){ return $Hm->Macro->doProxyMethod("selectword", "st", ...$args); } }
if (!function_exists("selectword2")) { function selectword2(...$args){ return $Hm->Macro->doProxyMethod("selectword2", "st", ...$args); } }
if (!function_exists("showcliphist")) { function showcliphist(...$args){ return $Hm->Macro->doProxyMethod("showcliphist", "st", ...$args); } }
if (!function_exists("poppaste")) { function poppaste(...$args){ return $Hm->Macro->doProxyMethod("poppaste", "st", ...$args); } }
if (!function_exists("poppaste2")) { function poppaste2(...$args){ return $Hm->Macro->doProxyMethod("poppaste2", "st", ...$args); } }
if (!function_exists("getcliphist")) { function getcliphist(...$args){ return $Hm->Macro->doProxyMethod("getcliphist", "st", ...$args); } }
if (!function_exists("clearcliphist")) { function clearcliphist(...$args){ return $Hm->Macro->doProxyMethod("clearcliphist", "st", ...$args); } }
if (!function_exists("selectcfunc")) { function selectcfunc(...$args){ return $Hm->Macro->doProxyMethod("selectcfunc", "st", ...$args); } }
if (!function_exists("copyurl")) { function copyurl(...$args){ return $Hm->Macro->doProxyMethod("copyurl", "st", ...$args); } }
if (!function_exists("copyformed")) { function copyformed(...$args){ return $Hm->Macro->doProxyMethod("copyformed", "st", ...$args); } }
if (!function_exists("selectcolumn")) { function selectcolumn(...$args){ return $Hm->Macro->doProxyMethod("selectcolumn", "st", ...$args); } }
if (!function_exists("tomultiselect")) { function tomultiselect(...$args){ return $Hm->Macro->doProxyMethod("tomultiselect", "st", ...$args); } }
if (!function_exists("invertselection")) { function invertselection(...$args){ return $Hm->Macro->doProxyMethod("invertselection", "st", ...$args); } }
if (!function_exists("reservemultisel")) { function reservemultisel(...$args){ return $Hm->Macro->doProxyMethod("reservemultisel", "st", ...$args); } }
if (!function_exists("selectreservedmultisel")) { function selectreservedmultisel(...$args){ return $Hm->Macro->doProxyMethod("selectreservedmultisel", "st", ...$args); } }
if (!function_exists("clearreservedmultisel")) { function clearreservedmultisel(...$args){ return $Hm->Macro->doProxyMethod("clearreservedmultisel", "st", ...$args); } }
if (!function_exists("clearreservedmultiselall")) { function clearreservedmultiselall(...$args){ return $Hm->Macro->doProxyMethod("clearreservedmultiselall", "st", ...$args); } }
if (!function_exists("backspace")) { function backspace(...$args){ return $Hm->Macro->doProxyMethod("backspace", "st", ...$args); } }
if (!function_exists("delete")) { function delete(...$args){ return $Hm->Macro->doProxyMethod("del", "st", ...$args); } }
if (!function_exists("del")) { function del(...$args){ return $Hm->Macro->doProxyMethod("del", "st", ...$args); } }
if (!function_exists("deleteafter")) { function deleteafter(...$args){ return $Hm->Macro->doProxyMethod("deleteafter", "st", ...$args); } }
if (!function_exists("deletebefore")) { function deletebefore(...$args){ return $Hm->Macro->doProxyMethod("deletebefore", "st", ...$args); } }
if (!function_exists("deleteline")) { function deleteline(...$args){ return $Hm->Macro->doProxyMethod("deleteline", "st", ...$args); } }
if (!function_exists("deleteline2")) { function deleteline2(...$args){ return $Hm->Macro->doProxyMethod("deleteline2", "st", ...$args); } }
if (!function_exists("deleteword")) { function deleteword(...$args){ return $Hm->Macro->doProxyMethod("deleteword", "st", ...$args); } }
if (!function_exists("deletewordall")) { function deletewordall(...$args){ return $Hm->Macro->doProxyMethod("deletewordall", "st", ...$args); } }
if (!function_exists("deletewordfront")) { function deletewordfront(...$args){ return $Hm->Macro->doProxyMethod("deletewordfront", "st", ...$args); } }
if (!function_exists("dupline")) { function dupline(...$args){ return $Hm->Macro->doProxyMethod("dupline", "st", ...$args); } }
if (!function_exists("insertline")) { function insertline(...$args){ return $Hm->Macro->doProxyMethod("insertline", "st", ...$args); } }
if (!function_exists("insertreturn")) { function insertreturn(...$args){ return $Hm->Macro->doProxyMethod("insertreturn", "st", ...$args); } }
if (!function_exists("tab")) { function tab(...$args){ return $Hm->Macro->doProxyMethod("tab", "st", ...$args); } }
if (!function_exists("undelete")) { function undelete(...$args){ return $Hm->Macro->doProxyMethod("undelete", "st", ...$args); } }
if (!function_exists("undo")) { function undo(...$args){ return $Hm->Macro->doProxyMethod("undo", "st", ...$args); } }
if (!function_exists("redo")) { function redo(...$args){ return $Hm->Macro->doProxyMethod("redo", "st", ...$args); } }
if (!function_exists("casechange")) { function casechange(...$args){ return $Hm->Macro->doProxyMethod("casechange", "st", ...$args); } }
if (!function_exists("indent")) { function indent(...$args){ return $Hm->Macro->doProxyMethod("indent", "st", ...$args); } }
if (!function_exists("unindent")) { function unindent(...$args){ return $Hm->Macro->doProxyMethod("unindent", "st", ...$args); } }
if (!function_exists("shifttab")) { function shifttab(...$args){ return $Hm->Macro->doProxyMethod("shifttab", "st", ...$args); } }
if (!function_exists("tospace")) { function tospace(...$args){ return $Hm->Macro->doProxyMethod("tospace", "st", ...$args); } }
if (!function_exists("totab")) { function totab(...$args){ return $Hm->Macro->doProxyMethod("totab", "st", ...$args); } }
if (!function_exists("tohankaku")) { function tohankaku(...$args){ return $Hm->Macro->doProxyMethod("tohankaku", "st", ...$args); } }
if (!function_exists("tozenkakuhira")) { function tozenkakuhira(...$args){ return $Hm->Macro->doProxyMethod("tozenkakuhira", "st", ...$args); } }
if (!function_exists("tozenkakukata")) { function tozenkakukata(...$args){ return $Hm->Macro->doProxyMethod("tozenkakukata", "st", ...$args); } }
if (!function_exists("capslockforgot")) { function capslockforgot(...$args){ return $Hm->Macro->doProxyMethod("capslockforgot", "st", ...$args); } }
if (!function_exists("imeconvforgot")) { function imeconvforgot(...$args){ return $Hm->Macro->doProxyMethod("imeconvforgot", "st", ...$args); } }
if (!function_exists("reopen")) { function reopen(...$args){ return $Hm->Macro->doProxyMethod("reopen", "st", ...$args); } }
if (!function_exists("filtermenu")) { function filtermenu(...$args){ return $Hm->Macro->doProxyMethod("filtermenu", "st", ...$args); } }
if (!function_exists("autocomplete")) { function autocomplete(...$args){ return $Hm->Macro->doProxyMethod("autocomplete", "st", ...$args); } }
if (!function_exists("form")) { function form(...$args){ return $Hm->Macro->doProxyMethod("form", "st", ...$args); } }
if (!function_exists("unform")) { function unform(...$args){ return $Hm->Macro->doProxyMethod("unform", "st", ...$args); } }
if (!function_exists("showformline")) { function showformline(...$args){ return $Hm->Macro->doProxyMethod("showformline", "st", ...$args); } }
if (!function_exists("clearundobuffer")) { function clearundobuffer(...$args){ return $Hm->Macro->doProxyMethod("clearundobuffer", "st", ...$args); } }
if (!function_exists("template")) { function template(...$args){ return $Hm->Macro->doProxyMethod("template", "st", ...$args); } }
if (!function_exists("find1")) { function find1(...$args){ return $Hm->Macro->doProxyMethod("find", "st", ...$args); } }
if (!function_exists("find2")) { function find2(...$args){ return $Hm->Macro->doProxyMethod("find2", "st", ...$args); } }
if (!function_exists("findword")) { function findword(...$args){ return $Hm->Macro->doProxyMethod("findword", "st", ...$args); } }
if (!function_exists("replace1")) { function replace1(...$args){ return $Hm->Macro->doProxyMethod("replace", "st", ...$args); } }
if (!function_exists("replaceall")) { function replaceall(...$args){ return $Hm->Macro->doProxyMethod("replaceall", "st", ...$args); } }
if (!function_exists("replaceallfast")) { function replaceallfast(...$args){ return $Hm->Macro->doProxyMethod("replaceallfast", "st", ...$args); } }
if (!function_exists("replaceallquick")) { function replaceallquick(...$args){ return $Hm->Macro->doProxyMethod("replaceallquick", "st", ...$args); } }
if (!function_exists("finddown")) { function finddown(...$args){ return $Hm->Macro->doProxyMethod("finddown", "st", ...$args); } }
if (!function_exists("finddown2")) { function finddown2(...$args){ return $Hm->Macro->doProxyMethod("finddown2", "st", ...$args); } }
if (!function_exists("findup")) { function findup(...$args){ return $Hm->Macro->doProxyMethod("findup", "st", ...$args); } }
if (!function_exists("findup2")) { function findup2(...$args){ return $Hm->Macro->doProxyMethod("findup2", "st", ...$args); } }
if (!function_exists("getsearch")) { function getsearch(...$args){ return $Hm->Macro->doProxyMethod("getsearch", "st", ...$args); } }
if (!function_exists("gosearchstarted")) { function gosearchstarted(...$args){ return $Hm->Macro->doProxyMethod("gosearchstarted", "st", ...$args); } }
if (!function_exists("setsearch")) { function setsearch(...$args){ return $Hm->Macro->doProxyMethod("setsearch", "st", ...$args); } }
if (!function_exists("setsearchhist")) { function setsearchhist(...$args){ return $Hm->Macro->doProxyMethod("setsearchhist", "st", ...$args); } }
if (!function_exists("setreplace")) { function setreplace(...$args){ return $Hm->Macro->doProxyMethod("setreplace", "st", ...$args); } }
if (!function_exists("setreplacehist")) { function setreplacehist(...$args){ return $Hm->Macro->doProxyMethod("setreplacehist", "st", ...$args); } }
if (!function_exists("setgrepfile")) { function setgrepfile(...$args){ return $Hm->Macro->doProxyMethod("setgrepfile", "st", ...$args); } }
if (!function_exists("forceinselect")) { function forceinselect(...$args){ return $Hm->Macro->doProxyMethod("forceinselect", "st", ...$args); } }
if (!function_exists("goupdatedown")) { function goupdatedown(...$args){ return $Hm->Macro->doProxyMethod("goupdatedown", "st", ...$args); } }
if (!function_exists("goupdateup")) { function goupdateup(...$args){ return $Hm->Macro->doProxyMethod("goupdateup", "st", ...$args); } }
if (!function_exists("clearupdates")) { function clearupdates(...$args){ return $Hm->Macro->doProxyMethod("clearupdates", "st", ...$args); } }
if (!function_exists("grep")) { function grep(...$args){ return $Hm->Macro->doProxyMethod("grep", "st", ...$args); } }
if (!function_exists("grepdialog")) { function grepdialog(...$args){ return $Hm->Macro->doProxyMethod("grepdialog", "st", ...$args); } }
if (!function_exists("grepdialog2")) { function grepdialog2(...$args){ return $Hm->Macro->doProxyMethod("grepdialog2", "st", ...$args); } }
if (!function_exists("localgrep")) { function localgrep(...$args){ return $Hm->Macro->doProxyMethod("localgrep", "st", ...$args); } }
if (!function_exists("grepreplace")) { function grepreplace(...$args){ return $Hm->Macro->doProxyMethod("grepreplace", "st", ...$args); } }
if (!function_exists("grepreplacedialog2")) { function grepreplacedialog2(...$args){ return $Hm->Macro->doProxyMethod("grepreplacedialog2", "st", ...$args); } }
if (!function_exists("escapeinselect")) { function escapeinselect(...$args){ return $Hm->Macro->doProxyMethod("escapeinselect", "st", ...$args); } }
if (!function_exists("hilightfound")) { function hilightfound(...$args){ return $Hm->Macro->doProxyMethod("hilightfound", "st", ...$args); } }
if (!function_exists("colormarker")) { function colormarker(...$args){ return $Hm->Macro->doProxyMethod("colormarker", "st", ...$args); } }
if (!function_exists("nextcolormarker")) { function nextcolormarker(...$args){ return $Hm->Macro->doProxyMethod("nextcolormarker", "st", ...$args); } }
if (!function_exists("prevcolormarker")) { function prevcolormarker(...$args){ return $Hm->Macro->doProxyMethod("prevcolormarker", "st", ...$args); } }
if (!function_exists("colormarkerdialog")) { function colormarkerdialog(...$args){ return $Hm->Macro->doProxyMethod("colormarkerdialog", "st", ...$args); } }
if (!function_exists("deletecolormarker")) { function deletecolormarker(...$args){ return $Hm->Macro->doProxyMethod("deletecolormarker", "st", ...$args); } }
if (!function_exists("deletecolormarkerall")) { function deletecolormarkerall(...$args){ return $Hm->Macro->doProxyMethod("deletecolormarkerall", "st", ...$args); } }
if (!function_exists("selectcolormarker")) { function selectcolormarker(...$args){ return $Hm->Macro->doProxyMethod("selectcolormarker", "st", ...$args); } }
if (!function_exists("selectallfound")) { function selectallfound(...$args){ return $Hm->Macro->doProxyMethod("selectallfound", "st", ...$args); } }
if (!function_exists("colormarkerallfound")) { function colormarkerallfound(...$args){ return $Hm->Macro->doProxyMethod("colormarkerallfound", "st", ...$args); } }
if (!function_exists("clearcolormarkerallfound")) { function clearcolormarkerallfound(...$args){ return $Hm->Macro->doProxyMethod("clearcolormarkerallfound", "st", ...$args); } }
if (!function_exists("foundlist")) { function foundlist(...$args){ return $Hm->Macro->doProxyMethod("foundlist", "st", ...$args); } }
if (!function_exists("foundlistoutline")) { function foundlistoutline(...$args){ return $Hm->Macro->doProxyMethod("foundlistoutline", "st", ...$args); } }
if (!function_exists("findmarkerlist")) { function findmarkerlist(...$args){ return $Hm->Macro->doProxyMethod("findmarkerlist", "st", ...$args); } }
if (!function_exists("selectinselect")) { function selectinselect(...$args){ return $Hm->Macro->doProxyMethod("selectinselect", "st", ...$args); } }
if (!function_exists("setinselect2")) { function setinselect2(...$args){ return $Hm->Macro->doProxyMethod("setinselect2", "st", ...$args); } }
if (!function_exists("settargetcolormarker")) { function settargetcolormarker(...$args){ return $Hm->Macro->doProxyMethod("settargetcolormarker", "st", ...$args); } }
if (!function_exists("colormarkersnapshot")) { function colormarkersnapshot(...$args){ return $Hm->Macro->doProxyMethod("colormarkersnapshot", "st", ...$args); } }
if (!function_exists("restoredesktop")) { function restoredesktop(...$args){ return $Hm->Macro->doProxyMethod("restoredesktop", "st", ...$args); } }
if (!function_exists("savedesktop")) { function savedesktop(...$args){ return $Hm->Macro->doProxyMethod("savedesktop", "st", ...$args); } }
if (!function_exists("scrolllink")) { function scrolllink(...$args){ return $Hm->Macro->doProxyMethod("scrolllink", "st", ...$args); } }
if (!function_exists("split")) { function split(...$args){ return $Hm->Macro->doProxyMethod("split", "st", ...$args); } }
if (!function_exists("setsplitinfo")) { function setsplitinfo(...$args){ return $Hm->Macro->doProxyMethod("setsplitinfo", "st", ...$args); } }
if (!function_exists("splitswitch")) { function splitswitch(...$args){ return $Hm->Macro->doProxyMethod("splitswitch", "st", ...$args); } }
if (!function_exists("windowcascade")) { function windowcascade(...$args){ return $Hm->Macro->doProxyMethod("windowcascade", "st", ...$args); } }
if (!function_exists("windowhorz")) { function windowhorz(...$args){ return $Hm->Macro->doProxyMethod("windowhorz", "st", ...$args); } }
if (!function_exists("windowtiling")) { function windowtiling(...$args){ return $Hm->Macro->doProxyMethod("windowtiling", "st", ...$args); } }
if (!function_exists("windowvert")) { function windowvert(...$args){ return $Hm->Macro->doProxyMethod("windowvert", "st", ...$args); } }
if (!function_exists("windowlist")) { function windowlist(...$args){ return $Hm->Macro->doProxyMethod("windowlist", "st", ...$args); } }
if (!function_exists("compfile")) { function compfile(...$args){ return $Hm->Macro->doProxyMethod("compfile", "st", ...$args); } }
if (!function_exists("COMPFILE_with_dialog")) { function COMPFILE_with_dialog(...$args){ return $Hm->Macro->doProxyMethod("COMPFILE", "st", ...$args); } }
if (!function_exists("nextcompfile")) { function nextcompfile(...$args){ return $Hm->Macro->doProxyMethod("nextcompfile", "st", ...$args); } }
if (!function_exists("prevcompfile")) { function prevcompfile(...$args){ return $Hm->Macro->doProxyMethod("prevcompfile", "st", ...$args); } }
if (!function_exists("alwaystopswitch")) { function alwaystopswitch(...$args){ return $Hm->Macro->doProxyMethod("alwaystopswitch", "st", ...$args); } }
if (!function_exists("settabmode")) { function settabmode(...$args){ return $Hm->Macro->doProxyMethod("settabmode", "st", ...$args); } }
if (!function_exists("settabgroup")) { function settabgroup(...$args){ return $Hm->Macro->doProxyMethod("settabgroup", "st", ...$args); } }
if (!function_exists("settaborder")) { function settaborder(...$args){ return $Hm->Macro->doProxyMethod("settaborder", "st", ...$args); } }
if (!function_exists("iconthistab")) { function iconthistab(...$args){ return $Hm->Macro->doProxyMethod("iconthistab", "st", ...$args); } }
if (!function_exists("fullscreen")) { function fullscreen(...$args){ return $Hm->Macro->doProxyMethod("fullscreen", "st", ...$args); } }
if (!function_exists("backtagjump")) { function backtagjump(...$args){ return $Hm->Macro->doProxyMethod("backtagjump", "st", ...$args); } }
if (!function_exists("directtagjump")) { function directtagjump(...$args){ return $Hm->Macro->doProxyMethod("directtagjump", "st", ...$args); } }
if (!function_exists("freecursorswitch")) { function freecursorswitch(...$args){ return $Hm->Macro->doProxyMethod("freecursorswitch", "st", ...$args); } }
if (!function_exists("imeswitch")) { function imeswitch(...$args){ return $Hm->Macro->doProxyMethod("imeswitch", "st", ...$args); } }
if (!function_exists("imeregisterword")) { function imeregisterword(...$args){ return $Hm->Macro->doProxyMethod("imeregisterword", "st", ...$args); } }
if (!function_exists("help1")) { function help1(...$args){ return $Hm->Macro->doProxyMethod("help", "st", ...$args); } }
if (!function_exists("help2")) { function help2(...$args){ return $Hm->Macro->doProxyMethod("help2", "st", ...$args); } }
if (!function_exists("help3")) { function help3(...$args){ return $Hm->Macro->doProxyMethod("help3", "st", ...$args); } }
if (!function_exists("help4")) { function help4(...$args){ return $Hm->Macro->doProxyMethod("help4", "st", ...$args); } }
if (!function_exists("help5")) { function help5(...$args){ return $Hm->Macro->doProxyMethod("help5", "st", ...$args); } }
if (!function_exists("help6")) { function help6(...$args){ return $Hm->Macro->doProxyMethod("help6", "st", ...$args); } }
if (!function_exists("hidemaruhelp")) { function hidemaruhelp(...$args){ return $Hm->Macro->doProxyMethod("hidemaruhelp", "st", ...$args); } }
if (!function_exists("macrohelp")) { function macrohelp(...$args){ return $Hm->Macro->doProxyMethod("macrohelp", "st", ...$args); } }
if (!function_exists("overwriteswitch")) { function overwriteswitch(...$args){ return $Hm->Macro->doProxyMethod("overwriteswitch", "st", ...$args); } }
if (!function_exists("readonlyswitch")) { function readonlyswitch(...$args){ return $Hm->Macro->doProxyMethod("readonlyswitch", "st", ...$args); } }
if (!function_exists("showcode")) { function showcode(...$args){ return $Hm->Macro->doProxyMethod("showcode", "st", ...$args); } }
if (!function_exists("showcharcount")) { function showcharcount(...$args){ return $Hm->Macro->doProxyMethod("showcharcount", "st", ...$args); } }
if (!function_exists("showlineno")) { function showlineno(...$args){ return $Hm->Macro->doProxyMethod("showlineno", "st", ...$args); } }
if (!function_exists("tagjump")) { function tagjump(...$args){ return $Hm->Macro->doProxyMethod("tagjump", "st", ...$args); } }
if (!function_exists("redraw")) { function redraw(...$args){ return $Hm->Macro->doProxyMethod("redraw", "st", ...$args); } }
if (!function_exists("browsemodeswitch")) { function browsemodeswitch(...$args){ return $Hm->Macro->doProxyMethod("browsemodeswitch", "st", ...$args); } }
if (!function_exists("clist")) { function clist(...$args){ return $Hm->Macro->doProxyMethod("clist", "st", ...$args); } }
if (!function_exists("clearupdated")) { function clearupdated(...$args){ return $Hm->Macro->doProxyMethod("clearupdated", "st", ...$args); } }
if (!function_exists("refreshtabstop")) { function refreshtabstop(...$args){ return $Hm->Macro->doProxyMethod("refreshtabstop", "st", ...$args); } }
if (!function_exists("refreshtabstop_pause")) { function refreshtabstop_pause(...$args){ return $Hm->Macro->doProxyMethod("refreshtabstop_pause", "st", ...$args); } }
if (!function_exists("refreshtabstop_shrink")) { function refreshtabstop_shrink(...$args){ return $Hm->Macro->doProxyMethod("refreshtabstop_shrink", "st", ...$args); } }
if (!function_exists("refreshtabstop_current")) { function refreshtabstop_current(...$args){ return $Hm->Macro->doProxyMethod("refreshtabstop_current", "st", ...$args); } }
if (!function_exists("autospellcheckswitch")) { function autospellcheckswitch(...$args){ return $Hm->Macro->doProxyMethod("autospellcheckswitch", "st", ...$args); } }
if (!function_exists("spellcheckdialog")) { function spellcheckdialog(...$args){ return $Hm->Macro->doProxyMethod("spellcheckdialog", "st", ...$args); } }
if (!function_exists("savebacktagjump")) { function savebacktagjump(...$args){ return $Hm->Macro->doProxyMethod("savebacktagjump", "st", ...$args); } }
if (!function_exists("fold")) { function fold(...$args){ return $Hm->Macro->doProxyMethod("fold", "st", ...$args); } }
if (!function_exists("unfold")) { function unfold(...$args){ return $Hm->Macro->doProxyMethod("unfold", "st", ...$args); } }
if (!function_exists("nextfoldable")) { function nextfoldable(...$args){ return $Hm->Macro->doProxyMethod("nextfoldable", "st", ...$args); } }
if (!function_exists("prevfoldable")) { function prevfoldable(...$args){ return $Hm->Macro->doProxyMethod("prevfoldable", "st", ...$args); } }
if (!function_exists("selectfoldable")) { function selectfoldable(...$args){ return $Hm->Macro->doProxyMethod("selectfoldable", "st", ...$args); } }
if (!function_exists("foldall")) { function foldall(...$args){ return $Hm->Macro->doProxyMethod("foldall", "st", ...$args); } }
if (!function_exists("unfoldall")) { function unfoldall(...$args){ return $Hm->Macro->doProxyMethod("unfoldall", "st", ...$args); } }
if (!function_exists("rangeeditin")) { function rangeeditin(...$args){ return $Hm->Macro->doProxyMethod("rangeeditin", "st", ...$args); } }
if (!function_exists("rangeeditout")) { function rangeeditout(...$args){ return $Hm->Macro->doProxyMethod("rangeeditout", "st", ...$args); } }
if (!function_exists("nextoutlineitem")) { function nextoutlineitem(...$args){ return $Hm->Macro->doProxyMethod("nextoutlineitem", "st", ...$args); } }
if (!function_exists("prevoutlineitem")) { function prevoutlineitem(...$args){ return $Hm->Macro->doProxyMethod("prevoutlineitem", "st", ...$args); } }
if (!function_exists("showoutline")) { function showoutline(...$args){ return $Hm->Macro->doProxyMethod("showoutline", "st", ...$args); } }
if (!function_exists("showoutlinebar")) { function showoutlinebar(...$args){ return $Hm->Macro->doProxyMethod("showoutlinebar", "st", ...$args); } }
if (!function_exists("showfoldingbar")) { function showfoldingbar(...$args){ return $Hm->Macro->doProxyMethod("showfoldingbar", "st", ...$args); } }
if (!function_exists("syncoutline")) { function syncoutline(...$args){ return $Hm->Macro->doProxyMethod("syncoutline", "st", ...$args); } }
if (!function_exists("refreshoutline")) { function refreshoutline(...$args){ return $Hm->Macro->doProxyMethod("refreshoutline", "st", ...$args); } }
if (!function_exists("setoutlinesize")) { function setoutlinesize(...$args){ return $Hm->Macro->doProxyMethod("setoutlinesize", "st", ...$args); } }
if (!function_exists("beep")) { function beep(...$args){ return $Hm->Macro->doProxyMethod("beep", "st", ...$args); } }
if (!function_exists("play")) { function play(...$args){ return $Hm->Macro->doProxyMethod("play", "st", ...$args); } }
if (!function_exists("playsync")) { function playsync(...$args){ return $Hm->Macro->doProxyMethod("playsync", "st", ...$args); } }
if (!function_exists("debuginfo")) { function debuginfo(...$args){ return $Hm->Macro->doProxyMethod("debuginfo", "st", ...$args); } }
if (!function_exists("showvars")) { function showvars(...$args){ return $Hm->Macro->doProxyMethod("showvars", "st", ...$args); } }
if (!function_exists("title")) { function title(...$args){ return $Hm->Macro->doProxyMethod("title", "st", ...$args); } }
if (!function_exists("run")) { function run(...$args){ return $Hm->Macro->doProxyMethod("run", "st", ...$args); } }
if (!function_exists("runsync")) { function runsync(...$args){ return $Hm->Macro->doProxyMethod("runsync", "st", ...$args); } }
if (!function_exists("runsync2")) { function runsync2(...$args){ return $Hm->Macro->doProxyMethod("runsync2", "st", ...$args); } }
if (!function_exists("runex")) { function runex(...$args){ return $Hm->Macro->doProxyMethod("runex", "st", ...$args); } }
if (!function_exists("disabledraw")) { function disabledraw(...$args){ return $Hm->Macro->doProxyMethod("disabledraw", "st", ...$args); } }
if (!function_exists("enabledraw")) { function enabledraw(...$args){ return $Hm->Macro->doProxyMethod("enabledraw", "st", ...$args); } }
if (!function_exists("disabledraw2")) { function disabledraw2(...$args){ return $Hm->Macro->doProxyMethod("disabledraw2", "st", ...$args); } }
if (!function_exists("enablebreak")) { function enablebreak(...$args){ return $Hm->Macro->doProxyMethod("enablebreak", "st", ...$args); } }
if (!function_exists("disablebreak")) { function disablebreak(...$args){ return $Hm->Macro->doProxyMethod("disablebreak", "st", ...$args); } }
if (!function_exists("disableinvert")) { function disableinvert(...$args){ return $Hm->Macro->doProxyMethod("disableinvert", "st", ...$args); } }
if (!function_exists("enableinvert")) { function enableinvert(...$args){ return $Hm->Macro->doProxyMethod("enableinvert", "st", ...$args); } }
if (!function_exists("disableerrormsg")) { function disableerrormsg(...$args){ return $Hm->Macro->doProxyMethod("disableerrormsg", "st", ...$args); } }
if (!function_exists("enableerrormsg")) { function enableerrormsg(...$args){ return $Hm->Macro->doProxyMethod("enableerrormsg", "st", ...$args); } }
if (!function_exists("disablehistory")) { function disablehistory(...$args){ return $Hm->Macro->doProxyMethod("disablehistory", "st", ...$args); } }
if (!function_exists("sleep1")) { function sleep2(...$args){ return $Hm->Macro->doProxyMethod("sleep", "st", ...$args); } }
if (!function_exists("setfloatmode")) { function setfloatmode(...$args){ return $Hm->Macro->doProxyMethod("setfloatmode", "st", ...$args); } }
if (!function_exists("seterrormode")) { function seterrormode(...$args){ return $Hm->Macro->doProxyMethod("seterrormode", "st", ...$args); } }
if (!function_exists("setbackgroundmode")) { function setbackgroundmode(...$args){ return $Hm->Macro->doProxyMethod("setbackgroundmode", "st", ...$args); } }
if (!function_exists("inputpos")) { function inputpos(...$args){ return $Hm->Macro->doProxyMethod("inputpos", "st", ...$args); } }
if (!function_exists("menu")) { function menu(...$args){ return $Hm->Macro->doProxyMethod("menu", "st", ...$args); } }
if (!function_exists("mousemenu")) { function mousemenu(...$args){ return $Hm->Macro->doProxyMethod("mousemenu", "st", ...$args); } }
if (!function_exists("setmenudelay")) { function setmenudelay(...$args){ return $Hm->Macro->doProxyMethod("setmenudelay", "st", ...$args); } }
if (!function_exists("writeininum")) { function writeininum(...$args){ return $Hm->Macro->doProxyMethod("writeininum", "st", ...$args); } }
if (!function_exists("writeininumw")) { function writeininumw(...$args){ return $Hm->Macro->doProxyMethod("writeininumw", "st", ...$args); } }
if (!function_exists("writeinistr")) { function writeinistr(...$args){ return $Hm->Macro->doProxyMethod("writeinistr", "st", ...$args); } }
if (!function_exists("writeinistrw")) { function writeinistrw(...$args){ return $Hm->Macro->doProxyMethod("writeinistrw", "st", ...$args); } }
if (!function_exists("openreg")) { function openreg(...$args){ return $Hm->Macro->doProxyMethod("openreg", "st", ...$args); } }
if (!function_exists("createreg")) { function createreg(...$args){ return $Hm->Macro->doProxyMethod("createreg", "st", ...$args); } }
if (!function_exists("closereg")) { function closereg(...$args){ return $Hm->Macro->doProxyMethod("closereg", "st", ...$args); } }
if (!function_exists("writeregbinary")) { function writeregbinary(...$args){ return $Hm->Macro->doProxyMethod("writeregbinary", "st", ...$args); } }
if (!function_exists("writeregnum")) { function writeregnum(...$args){ return $Hm->Macro->doProxyMethod("writeregnum", "st", ...$args); } }
if (!function_exists("writeregstr")) { function writeregstr(...$args){ return $Hm->Macro->doProxyMethod("writeregstr", "st", ...$args); } }
if (!function_exists("deletereg")) { function deletereg(...$args){ return $Hm->Macro->doProxyMethod("deletereg", "st", ...$args); } }
if (!function_exists("configset")) { function configset(...$args){ return $Hm->Macro->doProxyMethod("configset", "st", ...$args); } }
if (!function_exists("config")) { function config(...$args){ return $Hm->Macro->doProxyMethod("config", "st", ...$args); } }
if (!function_exists("configcolor")) { function configcolor(...$args){ return $Hm->Macro->doProxyMethod("configcolor", "st", ...$args); } }
if (!function_exists("saveconfig")) { function saveconfig(...$args){ return $Hm->Macro->doProxyMethod("saveconfig", "st", ...$args); } }
if (!function_exists("setconfigstate")) { function setconfigstate(...$args){ return $Hm->Macro->doProxyMethod("setconfigstate", "st", ...$args); } }
if (!function_exists("setfiletype")) { function setfiletype(...$args){ return $Hm->Macro->doProxyMethod("setfiletype", "st", ...$args); } }
if (!function_exists("envchanged")) { function envchanged(...$args){ return $Hm->Macro->doProxyMethod("envchanged", "st", ...$args); } }
if (!function_exists("loadkeyassign")) { function loadkeyassign(...$args){ return $Hm->Macro->doProxyMethod("loadkeyassign", "st", ...$args); } }
if (!function_exists("savekeyassign")) { function savekeyassign(...$args){ return $Hm->Macro->doProxyMethod("savekeyassign", "st", ...$args); } }
if (!function_exists("loadhilight")) { function loadhilight(...$args){ return $Hm->Macro->doProxyMethod("loadhilight", "st", ...$args); } }
if (!function_exists("savehilight")) { function savehilight(...$args){ return $Hm->Macro->doProxyMethod("savehilight", "st", ...$args); } }
if (!function_exists("loadbookmark")) { function loadbookmark(...$args){ return $Hm->Macro->doProxyMethod("loadbookmark", "st", ...$args); } }
if (!function_exists("savebookmark")) { function savebookmark(...$args){ return $Hm->Macro->doProxyMethod("savebookmark", "st", ...$args); } }
if (!function_exists("setfontchangemode")) { function setfontchangemode(...$args){ return $Hm->Macro->doProxyMethod("setfontchangemode", "st", ...$args); } }
if (!function_exists("settabstop")) { function settabstop(...$args){ return $Hm->Macro->doProxyMethod("settabstop", "st", ...$args); } }
if (!function_exists("convert_return_in_cell")) { function convert_return_in_cell(...$args){ return $Hm->Macro->doProxyMethod("convert_return_in_cell", "st", ...$args); } }
if (!function_exists("showwindow")) { function showwindow(...$args){ return $Hm->Macro->doProxyMethod("showwindow", "st", ...$args); } }
if (!function_exists("setmonitor")) { function setmonitor(...$args){ return $Hm->Macro->doProxyMethod("setmonitor", "st", ...$args); } }
if (!function_exists("setwindowpos")) { function setwindowpos(...$args){ return $Hm->Macro->doProxyMethod("setwindowpos", "st", ...$args); } }
if (!function_exists("setwindowsize")) { function setwindowsize(...$args){ return $Hm->Macro->doProxyMethod("setwindowsize", "st", ...$args); } }
if (!function_exists("setfocus")) { function setfocus(...$args){ return $Hm->Macro->doProxyMethod("setfocus", "st", ...$args); } }
if (!function_exists("begingroupundo")) { function begingroupundo(...$args){ return $Hm->Macro->doProxyMethod("begingroupundo", "st", ...$args); } }
if (!function_exists("endgroupundo")) { function endgroupundo(...$args){ return $Hm->Macro->doProxyMethod("endgroupundo", "st", ...$args); } }
if (!function_exists("findspecial")) { function findspecial(...$args){ return $Hm->Macro->doProxyMethod("findspecial", "st", ...$args); } }
if (!function_exists("setstaticvariable")) { function setstaticvariable(...$args){ return $Hm->Macro->doProxyMethod("setstaticvariable", "st", ...$args); } }
if (!function_exists("setregularcache")) { function setregularcache(...$args){ return $Hm->Macro->doProxyMethod("setregularcache", "st", ...$args); } }
if (!function_exists("closehidemaru")) { function closehidemaru(...$args){ return $Hm->Macro->doProxyMethod("closehidemaru", "st", ...$args); } }
if (!function_exists("closehidemaruforced")) { function closehidemaruforced(...$args){ return $Hm->Macro->doProxyMethod("closehidemaruforced", "st", ...$args); } }
if (!function_exists("beginclipboardread")) { function beginclipboardread(...$args){ return $Hm->Macro->doProxyMethod("beginclipboardread", "st", ...$args); } }
if (!function_exists("seteventnotify")) { function seteventnotify(...$args){ return $Hm->Macro->doProxyMethod("seteventnotify", "st", ...$args); } }

if (!function_exists("freedll")) { function freedll(...$args){ return $Hm->Macro->doProxyMethod("freedll", "st", ...$args); } }
if (!function_exists("setdlldetachfunc")) { function setdlldetachfunc(...$args){ return $Hm->Macro->doProxyMethod("setdlldetachfunc", "st", ...$args); } }
if (!function_exists("keepdll")) { function keepdll(...$args){ return $Hm->Macro->doProxyMethod("keepdll", "st", ...$args); } }
if (!function_exists("setcomdetachmethod")) { function setcomdetachmethod(...$args){ return $Hm->Macro->doProxyMethod("setcomdetachmethod", "st", ...$args); } }
if (!function_exists("keepobject")) { function keepobject(...$args){ return $Hm->Macro->doProxyMethod("keepobject", "st", ...$args); } }
if (!function_exists("releaseobject")) { function releaseobject(...$args){ return $Hm->Macro->doProxyMethod("releaseobject", "st", ...$args); } }
if (!function_exists("showbrowserpane")) { function showbrowserpane(...$args){ return $Hm->Macro->doProxyMethod("showbrowserpane", "st", ...$args); } }
if (!function_exists("refreshbrowserpane")) { function refreshbrowserpane(...$args){ return $Hm->Macro->doProxyMethod("refreshbrowserpane", "st", ...$args); } }
if (!function_exists("setbrowserpanesize")) { function setbrowserpanesize(...$args){ return $Hm->Macro->doProxyMethod("setbrowserpanesize", "st", ...$args); } }
if (!function_exists("setbrowserpaneurl")) { function setbrowserpaneurl(...$args){ return $Hm->Macro->doProxyMethod("setbrowserpaneurl", "st", ...$args); } }
if (!function_exists("setbrowserpanetarget")) { function setbrowserpanetarget(...$args){ return $Hm->Macro->doProxyMethod("setbrowserpanetarget", "st", ...$args); } }
if (!function_exists("setselectionrange")) { function setselectionrange(...$args){ return $Hm->Macro->doProxyMethod("setselectionrange", "st", ...$args); } }
if (!function_exists("clearkeyhook")) { function clearkeyhook(...$args){ return $Hm->Macro->doProxyMethod("clearkeyhook", "st", ...$args); } }

# 配列展開
if (!function_exists("menuarray")) { function menuarray(...$args){ return menu(...$args[0]); } }

# 配列展開
if (!function_exists("mousemenuarray")) { function mousemenuarray(...$args){ return mousemenu(...$args[0]); } }

if (!function_exists("message")) { function message(...$args){ return $Hm->Macro->doProxyMethod("message", "fn1s2s", ...$args); } }

if (!function_exists("insert")) { function insert(...$args){ return $Hm->Macro->doProxyMethod("insert", "st1s", ...$args); } }
if (!function_exists("insertfix")) { function insertfix(...$args){ return $Hm->Macro->doProxyMethod("insertfix", "st1s", ...$args); } }
if (!function_exists("searchdialog")) { function searchdialog(...$args){ return $Hm->Macro->doProxyMethod("searchdialog", "st1s", ...$args); } }
if (!function_exists("searchdown")) { function searchdown(...$args){ return $Hm->Macro->doProxyMethod("searchdown", "st1s", ...$args); } }
if (!function_exists("searchdown2")) { function searchdown2(...$args){ return $Hm->Macro->doProxyMethod("searchdown2", "st1s", ...$args); } }
if (!function_exists("searchup")) { function searchup(...$args){ return $Hm->Macro->doProxyMethod("searchup", "st1s", ...$args); } }
if (!function_exists("searchup2")) { function searchup2(...$args){ return $Hm->Macro->doProxyMethod("searchup2", "st1s", ...$args); } }
if (!function_exists("question")) { function question(...$args){ return $Hm->Macro->doProxyMethod("question", "st1s", ...$args); } }
if (!function_exists("setclipboard")) { function setclipboard(...$args){ return $Hm->Macro->doProxyMethod("setclipboard", "st1s", ...$args); } }
if (!function_exists("addclipboard")) { function addclipboard(...$args){ return $Hm->Macro->doProxyMethod("addclipboard", "st1s", ...$args); } }

if (!function_exists("replacedialog")) { function replacedialog(...$args){ return $Hm->Macro->doProxyMethod("replacedialog", "st1s2s", ...$args); } }
if (!function_exists("replacedown")) { function replacedown(...$args){ return $Hm->Macro->doProxyMethod("replacedown", "st1s2s", ...$args); } }
if (!function_exists("replaceup")) { function replaceup(...$args){ return $Hm->Macro->doProxyMethod("replaceup", "st1s2s", ...$args); } }

if (!function_exists("getresultex")) {
    function getresultex(...$args){ 
        // この時だけ文字列が返る
        if ($args[0] == -1) {
            return getresultex_rstr(...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("getresultex", "fsn", ...$args);
        }
    }
    function getresultex_rstr(...$args){ 
        $Hm->Macro->setVar('#__getresultex_rstr_arg0__', $args[0]);
        $eval_ret = $Hm->Macro->doEval('$__temp_getresultex_rstr__ = getresultex(#__getresultex_rstr_arg0__);');
        $func_ret = $Hm->Macro->getVar('$__temp_getresultex_rstr__');
        $Hm->Macro->setVar('$__temp_getresultex_rstr__', '');
        $Hm->Macro->setVar('#__getresultex_rstr_arg0__', 0);
        return $func_ret;
    }
}

if (!function_exists("geteventparam")) {
    function geteventparam(...$args){ 
        // この時だけ文字列が返る
        if ($args[0] == 0 && event() == 9) {
            return geteventparam_rstr(...$args);
        }
        else if ($args[0] == 0 && event() == 10) {
            return geteventparam_rstr(...$args);
        }
        else {
            return $Hm->Macro->doProxyMethod("geteventparam", "fsn", ...$args);
        }
    }
    function geteventparam_rstr(...$args) {
        $Hm->Macro->setVar('#__geteventparam_rstr_arg0__', $args[0]);
        $eval_ret = $Hm->Macro->doEval('$__temp_geteventparam_rstr__ = geteventparam(#__geteventparam_rstr_arg0__);');
        $func_ret = $Hm->Macro->getVar('$__temp_geteventparam_rstr__');
        $Hm->Macro->setVar('$__temp_geteventparam_rstr__', '');
        $Hm->Macro->setVar('#__geteventparam_rstr_arg0__', 0);
        return $func_ret;
    }
}

if (!function_exists("getconfig")) {
    # 特に問題はないだろうから、文字列のみで返すようにする
    function getconfig(...$args) {
        $Hm->Macro->setVar('$__getconfig_rstr_arg0__', $args[0]);
        $eval_ret = $Hm->Macro->doEval('$__temp_getconfig_rstr__ = getconfig($__getconfig_rstr_arg0__);');
        $func_ret = $Hm->Macro->getVar('$__temp_getconfig_rstr__');
        $Hm->Macro->setVar('$__temp_getconfig_rstr__', '');
        $Hm->Macro->setVar('$__getconfig_rstr_arg0__', '');
        return $func_ret;
    }

    # 数値型で欲しい場合には、こちらを使えば、数値に変換可能なら数値型に変換して返す。
    function getconfig_rnum(...$args) {
        $ret = getconfig(...$args);
        try {
            $num = intval($ret);
            if ("$num" == $ret ) {
                return $num;
            }
        } catch(e) {

        }
        return 0;
    }
}

if (!function_exists("member_rnum")) {
    function member_rnum(...$args) {
        $arg_name_list = [];
        for($i=0; $i<count($args); $i++) {
            $arg = $args[$i];
            $typename = gettype($arg);
            if ($typename == gettype(true) || $typename == gettype(10) || $typename == gettype(10.5)) {
                $var_name = '#__member_rnum_arg' . "$i" . "__";
                array_push($arg_name_list, $var_name);
                $Hm->Macro->setVar($var_name, intval($arg));
            } else {
                $var_name = '$__member_rnum_arg' . "$i" . "__";
                array_push($arg_name_list, $var_name);
                $Hm->Macro->setVar($var_name, "$arg");
            }
        }

        $var_arg_list = join(', ', $arg_name_list);
        $eval_ret = $Hm->Macro->doEval('#__temp_member_rnum__ = member( ' . $var_arg_list . ');');
        $func_ret = $Hm->Macro->getVar('#__temp_member_rnum__');
        $Hm->Macro->setVar('#__temp_member_rnum__',  0);

        foreach($arg_name_list as $var_name) {
            if (str_starts_with($var_name, '#')) {
                $Hm->Macro->setVar($var_name, 0);
            } else {
                $Hm->Macro->setVar($var_name, "");
            }
        }

        return $func_ret;
    }
}

if (!function_exists("member_rstr")) {
    function member_rstr(...$args) {
        $arg_name_list = [];
        for($i=0; $i<count($args); $i++) {
            $arg = $args[$i];
            $typename = gettype($arg);
            if ($typename == gettype(true) || $typename == gettype(10) || $typename == gettype(10.5)) {
                $var_name = '#__member_rstr_arg' . "$i" . "__";
                array_push($arg_name_list, $var_name);
                $Hm->Macro->setVar($var_name, intval($arg));
            } else {
                $var_name = '$__member_rstr_arg' . "$i" . "__";
                array_push($arg_name_list, $var_name);
                $Hm->Macro->setVar($var_name, "$arg");
            }
        }

        $var_arg_list = join(', ', $arg_name_list);
        $eval_ret = $Hm->Macro->doEval('$__temp_member_rstr__ = member( ' . $var_arg_list . ');');
        $func_ret = $Hm->Macro->getVar('$__temp_member_rstr__');
        $Hm->Macro->setVar('$__temp_member_rstr__',  "");

        foreach($arg_name_list as $var_name) {
            if (str_starts_with($var_name, '#')) {
                $Hm->Macro->setVar($var_name, 0);
            } else {
                $Hm->Macro->setVar($var_name, "");
            }
        }

        return $func_ret;
    }
}
?>