# hmPeach

![hmPeach latest version](https://img.shields.io/github/v/release/komiyamma/hm_php8?label=hmPeach&style=flat&color=6479ff&prefix=)
[![Apache 2.0](https://img.shields.io/badge/license-Apache_2.0-blue.svg?style=flat)](LICENSE)
![Hidemaru 8.98 x86](https://img.shields.io/badge/Hidemaru-v8.98_(32bit_version_only)-6479ff.svg)
![PHP 8.1.3](https://img.shields.io/badge/PHP-v8.1.3_(x86)-6479ff.svg?logo=php&logoColor=white)

秀丸マクロで「PHPと秀丸マクロをシームレスに近い形で利用する」ためのコンポーネントとなります。

[https://秀丸マクロ.net/?page=nobu_tool_hm_php](https://秀丸マクロ.net/?page=nobu_tool_hm_php)

## 機能 (Features)

`hmPeach`は、高機能テキストエディタ「秀丸」のマクロから、スクリプト言語「PHP」をシームレスに利用するためのコンポーネント群です。本プロジェクトを利用することで、秀丸マクロの拡張性を大幅に向上させ、より複雑で高度なテキスト処理や自動化を実現できます。

*   **PHP 8.1.3の実行環境**: 秀丸エディタ内でPHPのパワフルな機能を利用できます。
*   **秀丸エディタとの連携**: PHPスクリプトから、現在開いているファイルのテキスト取得・編集、カーソル位置の操作、マクロの実行など、秀丸エディタの様々な機能を直接コントロールできます。
*   **アウトプット枠／エクスプローラ枠との連携**: PHPからの出力を秀丸のアウトプット枠に表示したり、エクスプローラ枠を操作したりすることが可能です。

## 構成 (Architecture)

`hmPeach`は、主に3つのコンポーネントから構成されています。

1.  **`hmPeach.dll` (コアエンジン)**
    *   このDLLは、PHPの実行エンジンそのものを内包しています。秀丸マクロから呼び出されることを想定しており、PHPスクリプトの実行環境を初期化し、スクリプトを実行する役割を担います。
    *   PHPの出力を秀丸のアウトプット枠にリダイレクトする機能も持っています。

2.  **`php_hidemaru.dll` (PHP拡張モジュール)**
    *   `hmPeach.dll`によって自動的に読み込まれるPHPの拡張モジュールです。
    *   この拡張モジュールにより、PHPのコード内から `hidemaru_` という接頭辞で始まる低レベルな関数群が利用可能になります。

3.  **`hmPeach.php` (PHPラッパーライブラリ)**
    *   `hmPeach.dll`によって自動的にインクルードされるPHPスクリプトです。
    *   `php_hidemaru.dll`が提供する低レベル関数を、より使いやすいオブジェクト指向の形式でラップします（例: `$Hm->Edit->getTotalText()`）。
    *   さらに、多くの秀丸マクロ関数（`find`, `golineend`など）と同名のPHP関数を提供し、マクロ開発者が既存の知識を活かして直感的にコーディングできる互換性レイヤーとしての役割も果たします。

### 動作フロー

1.  ユーザーが秀丸マクロから `hmPeach.dll` の関数を呼び出します。
2.  `hmPeach.dll` はPHP実行環境を準備し、まず `hmPeach.php` を読み込み、続いてユーザー指定のPHPスクリプトを実行します。
3.  PHPスクリプト内で `$Hm` オブジェクトや互換関数が呼び出されると、最終的に `php_hidemaru.dll` を通じて秀丸エディタへの命令が実行されます。
4.  PHPスクリプトの `echo` や `print` などの出力は、秀丸のアウトプット枠に表示されます。

## 提供されるPHP関数 (Provided PHP Functions)

`php_hidemaru.dll`拡張機能により、以下の関数がPHPスクリプト内で利用可能になります。

### 秀丸本体・デバッグ

-   `hidemaru_version()`: 秀丸のバージョンを数値で返します。
-   `hidemaru_getwindowhandle()`: 秀丸本体のウィンドウハンドルを取得します。
-   `hidemaru_debuginfo(string $message)`: デバッグ用のメッセージを出力します。（主に開発者向け）

### エディタ操作

-   `hidemaru_edit_getfilepath()`: 現在編集中のファイルのフルパスを取得します。
-   `hidemaru_edit_getcursorpos()`: カーソル位置を行番号と桁位置の配列で取得します。例: `[10, 5]`
-   `hidemaru_edit_getcursorposfrommousepos()`: マウスカーソル位置に最も近いエディタ上のカーソル位置を取得します。
-   `hidemaru_edit_gettotaltext()`: 現在のファイルの全テキストを取得します。
-   `hidemaru_edit_settotaltext(string $text)`: 現在のファイルの全テキストを指定した文字列で置き換えます。
-   `hidemaru_edit_getselectedtext()`: 選択範囲のテキストを取得します。
-   `hidemaru_edit_setselectedtext(string $text)`: 選択範囲を指定した文字列で置き換えます。
-   `hidemaru_edit_getlinetext()`: カーソルがある行のテキストを取得します。
-   `hidemaru_edit_setlinetext(string $text)`: カーソルがある行を指定した文字列で置き換えます。

### マクロ操作

-   `hidemaru_macro_isexecuting()`: 秀丸マクロが実行中であるかを確認します。
-   `hidemaru_macro_eval(string $expression)`: 秀丸マクロの式を評価（実行）します。
-   `hidemaru_macro_eval_function(string $expression)`: 秀丸マクロの関数式を評価し、その結果を返します。
-   `hidemaru_macro_exec_eval_memory(string $expression)`: マクロをメモリ内で実行し、結果を取得します。
-   `hidemaru_macro_getvar(string $var_name)`: 秀丸マクロの変数（`$`で始まる文字列変数、`#`で始まる数値変数）の値を取得します。
-   `hidemaru_macro_setvar(string $var_name, string|int $value)`: 秀丸マクロの変数に値を設定します。

### アウトプット枠操作

-   `hidemaru_outputpane_output(string $message)`: アウトプット枠に文字列を出力します。
-   `hidemaru_outputpane_setbasedir(string $path)`: アウトプット枠の基準ディレクトリを設定します。
-   `hidemaru_outputpane_push()`: 現在のアウトプット枠の内容をスタックに退避します。
-   `hidemaru_outputpane_pop()`: スタックからアウトプット枠の内容を復元します。
-   `hidemaru_outputpane_getwindowhandle()`: アウトプット枠のウィンドウハンドルを取得します。
-   `hidemaru_outputpane_sendmessage(int $command_id)`: アウトプット枠にコマンドメッセージを送信します。（例: `1009`でクリア）
-   `hidemaru_outputpane_clear()`: アウトプット枠の内容をクリアします。

### エクスプローラ枠操作

-   `hidemaru_explorerpane_setmode(int $mode)`: エクスプローラ枠のモードを設定します。
-   `hidemaru_explorerpane_getmode()`: エクスプローラ枠の現在のモードを取得します。
-   `hidemaru_explorerpane_loadproject(string $path)`: プロジェクトファイルを読み込みます。
-   `hidemaru_explorerpane_saveproject(string $path)`: プロジェクトファイルを保存します。
-   `hidemaru_explorerpane_getupdated()`: プロジェクトが更新されたかどうかを取得します。
-   `hidemaru_explorerpane_getwindowhandle()`: エクスプローラ枠のウィンドウハンドルを取得します。
-   `hidemaru_explorerpane_sendmessage(int $command_id)`: エクスプローラ枠にコマンドメッセージを送信します。
-   `hidemaru_explorerpane_getcurrentdir()`: エクスプローラ枠で現在選択されているディレクトリを取得します。
-   `hidemaru_explorerpane_getproject()`: 現在のプロジェクトファイルのパスを取得します。

## ディレクトリ構成 (Directory Structure)

-   **`HmPHP8/`**
    -   PHP実行エンジンを埋め込んだコアDLL (`hmPeach.dll`) のVisual Studioプロジェクトとソースコードが含まれています。

-   **`ext/hidemaru/`**
    -   秀丸エディタ連携機能を提供するPHP拡張機能 (`php_hidemaru.dll`) のソースコードが含まれています。