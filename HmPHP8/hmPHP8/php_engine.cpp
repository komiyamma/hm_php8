/* 
 * Copyright (c) 2017 Akitsugu Komiyama
 * under the Apache License Version 2.0
 */

#include "php_engine.h"

namespace PythonEngine {

	// エンジンが有効になった
	BOOL m_isValid = false;
	// 秀丸の名前格納
	wchar_t *m_wstr_program = NULL;

	wchar_t szHidemaruFullPath[MAX_PATH] = L"";

	BOOL IsValid() {
		return m_isValid;
	}

	// エンジン生成
	int Create()
	{
		m_isValid = FALSE;

		try {

		// エンジンとして駄目
		return FALSE;
	}

	// 対象のシンボル名の値を数値として得る
	intHM_t GetNumVar(wstring utf16_simbol) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// 値を得て…
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			auto value = global[utf8_simbol.c_str()];

			// 文字列化するのは、実態がなんのオブジェクトかわからないため
			string utf8_value = py::str(value);
			wstring utf16_value = utf8_to_utf16(utf8_value);

			// 数字を数値に変換トライ。ダメなら0だよ。
			intHM_t n = 0;
			try {
				n = (intHM_t)std::stoll(utf16_value);
			}
			catch (...) {
				n = 0;
			}
			return n;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"エラー:\n" + what);
			MessageBox(NULL, what.data(), L"システム例外", NULL);
		}

		return 0;
	}

	// 対象のシンボル名の値に数値を代入する
	BOOL SetNumVar(wstring utf16_simbol, intHM_t value) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// 値を代入
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			global[utf8_simbol.c_str()] = value;

			return TRUE;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"エラー:\n" + what);
			MessageBox(NULL, what.data(), L"システム例外", NULL);
		}

		return FALSE;
	}

	// 対象のシンボル名の値を文字列として得る
	wstring GetStrVar(wstring utf16_simbol) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// 値を得て…
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			auto value = global[utf8_simbol.c_str()];

			// 文字列化して
			string utf8_value = py::str(value);

			wstring utf16_value = utf8_to_utf16(utf8_value);
			return utf16_value;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"エラー:\n" + what);
			MessageBox(NULL, what.data(), L"システム例外", NULL);
		}

		return L"";
	}

	// 対象のシンボル名の値に文字列を代入する
	BOOL SetStrVar(wstring utf16_simbol, wstring utf16_value) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// 値を代入
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			string utf8_value = utf16_to_utf8(utf16_value);
			global[utf8_simbol.c_str()] = utf8_value;

			return TRUE;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"エラー:\n" + what);
			MessageBox(NULL, what.data(), L"システム例外", NULL);
		}

		return FALSE;
	}


	// 対象の文字列をPythonの複数式とみなして評価する
	int DoString(wstring utf16_expression) {
		if (!IsValid()) {
			return FALSE;
		}

		try {

			string utf8_expression = utf16_to_utf8(utf16_expression);
			py::eval<py::eval_statements>(utf8_expression);

			return TRUE;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"エラー:\n" + what);
			MessageBox(NULL, what.data(), L"システム例外", NULL);
		}

		return FALSE;


	}

	// エンジンの破棄
	int Destroy() {

		// 有効でないならば、即終了
		if (!IsValid()) {
			return FALSE;
		}

		// 有効な時だけ
		if (m_isValid) {

			try {
				// DestroyScopeというのが、メインモジュール内に定義されていれば、それを実行する
				auto global = py::dict(py::module::import("__main__").attr("__dict__"));

				// マクロを呼び出した元のフォルダはpythonファイルの置き場としても認識する
				py::eval<py::eval_statements>("if 'DestroyScope' in globals(): DestroyScope()");
#pragma region
				/*
				auto func = global["DestroyScope"];
				func();
				*/
#pragma endregion
			}
			catch (py::error_already_set& e) {
				OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
			}
			catch (exception& e) {
				OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
			}
			catch (...) {
				auto what = python_critical_exception_message();
				OutputDebugStream(L"エラー:\n" + what);
				MessageBox(NULL, what.data(), L"システム例外", NULL);
			}

			// 破棄
			try {
				py::finalize_interpreter();

				// PyMem_RawFree(m_wstr_program);
			}
			catch (py::error_already_set& e) {
				OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
			}
			catch (exception& e) {
				OutputDebugStream(L"エラー:\n" + utf8_to_utf16(e.what()));
			}
			catch (...) {
				auto what = python_critical_exception_message();
				OutputDebugStream(L"エラー:\n" + what);
				MessageBox(NULL, what.data(), L"システム例外", NULL);
			}
		}

		// 初期状態へ
		m_isValid = FALSE;
		m_isInitialize = FALSE;
		// m_wstr_program = NULL;

		return TRUE;
	}

}