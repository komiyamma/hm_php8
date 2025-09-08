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

## ディレクトリ構成 (Directory Structure)

-   **`HmPHP8/`**
    -   PHP実行エンジンを埋め込んだコアDLL (`hmPeach.dll`) のVisual Studioプロジェクトとソースコードが含まれています。

-   **`ext/hidemaru/`**
    -   秀丸エディタ連携機能を提供するPHP拡張機能 (`php_hidemaru.dll`) のソースコードが含まれています。
