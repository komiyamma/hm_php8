/* 
 * Copyright (c) 2017 Akitsugu Komiyama
 * under the Apache License Version 2.0
 */

#include "php_engine.h"

namespace PythonEngine {

	// �G���W�����L���ɂȂ���
	BOOL m_isValid = false;
	// �G�ۂ̖��O�i�[
	wchar_t *m_wstr_program = NULL;

	wchar_t szHidemaruFullPath[MAX_PATH] = L"";

	BOOL IsValid() {
		return m_isValid;
	}

	// �G���W������
	int Create()
	{
		m_isValid = FALSE;

		try {

		// �G���W���Ƃ��đʖ�
		return FALSE;
	}

	// �Ώۂ̃V���{�����̒l�𐔒l�Ƃ��ē���
	intHM_t GetNumVar(wstring utf16_simbol) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// �l�𓾂āc
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			auto value = global[utf8_simbol.c_str()];

			// �����񉻂���̂́A���Ԃ��Ȃ�̃I�u�W�F�N�g���킩��Ȃ�����
			string utf8_value = py::str(value);
			wstring utf16_value = utf8_to_utf16(utf8_value);

			// �����𐔒l�ɕϊ��g���C�B�_���Ȃ�0����B
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
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"�G���[:\n" + what);
			MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
		}

		return 0;
	}

	// �Ώۂ̃V���{�����̒l�ɐ��l��������
	BOOL SetNumVar(wstring utf16_simbol, intHM_t value) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// �l����
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			global[utf8_simbol.c_str()] = value;

			return TRUE;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"�G���[:\n" + what);
			MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
		}

		return FALSE;
	}

	// �Ώۂ̃V���{�����̒l�𕶎���Ƃ��ē���
	wstring GetStrVar(wstring utf16_simbol) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// �l�𓾂āc
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			auto value = global[utf8_simbol.c_str()];

			// �����񉻂���
			string utf8_value = py::str(value);

			wstring utf16_value = utf8_to_utf16(utf8_value);
			return utf16_value;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"�G���[:\n" + what);
			MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
		}

		return L"";
	}

	// �Ώۂ̃V���{�����̒l�ɕ������������
	BOOL SetStrVar(wstring utf16_simbol, wstring utf16_value) {
		try {
			auto global = py::dict(py::module::import("__main__").attr("__dict__"));
			auto local = py::dict();

			// �l����
			string utf8_simbol = utf16_to_utf8(utf16_simbol);
			string utf8_value = utf16_to_utf8(utf16_value);
			global[utf8_simbol.c_str()] = utf8_value;

			return TRUE;
		}
		catch (py::error_already_set& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"�G���[:\n" + what);
			MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
		}

		return FALSE;
	}


	// �Ώۂ̕������Python�̕������Ƃ݂Ȃ��ĕ]������
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
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (exception& e) {
			OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
		}
		catch (...) {
			auto what = python_critical_exception_message();
			OutputDebugStream(L"�G���[:\n" + what);
			MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
		}

		return FALSE;


	}

	// �G���W���̔j��
	int Destroy() {

		// �L���łȂ��Ȃ�΁A���I��
		if (!IsValid()) {
			return FALSE;
		}

		// �L���Ȏ�����
		if (m_isValid) {

			try {
				// DestroyScope�Ƃ����̂��A���C�����W���[�����ɒ�`����Ă���΁A��������s����
				auto global = py::dict(py::module::import("__main__").attr("__dict__"));

				// �}�N�����Ăяo�������̃t�H���_��python�t�@�C���̒u����Ƃ��Ă��F������
				py::eval<py::eval_statements>("if 'DestroyScope' in globals(): DestroyScope()");
#pragma region
				/*
				auto func = global["DestroyScope"];
				func();
				*/
#pragma endregion
			}
			catch (py::error_already_set& e) {
				OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
			}
			catch (exception& e) {
				OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
			}
			catch (...) {
				auto what = python_critical_exception_message();
				OutputDebugStream(L"�G���[:\n" + what);
				MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
			}

			// �j��
			try {
				py::finalize_interpreter();

				// PyMem_RawFree(m_wstr_program);
			}
			catch (py::error_already_set& e) {
				OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
			}
			catch (exception& e) {
				OutputDebugStream(L"�G���[:\n" + utf8_to_utf16(e.what()));
			}
			catch (...) {
				auto what = python_critical_exception_message();
				OutputDebugStream(L"�G���[:\n" + what);
				MessageBox(NULL, what.data(), L"�V�X�e����O", NULL);
			}
		}

		// ������Ԃ�
		m_isValid = FALSE;
		m_isInitialize = FALSE;
		// m_wstr_program = NULL;

		return TRUE;
	}

}