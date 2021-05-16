/* mytest extension for PHP */
#define ZEND_DEBUG 0

/* hidemaru extension for PHP */

// そうである

#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include "php.h"
#include "zend_types.h"
#include "ext/standard/info.h"
#include "php_hidemaru.h"
#include "hidemaru_arginfo.h"
#include "convert_string.h"
#include "hidemaru_interface.h"
#include "hidemaruexe_export.h"
#include "hm_original_encode_mapfunc.h"
#include "output_debugstream.h"
#include "dllfunc_interface_internal.h"
#include "self_dll_info.h"

/* For compatibility with older PHP versions */
#ifndef ZEND_PARSE_PARAMETERS_NONE
#define ZEND_PARSE_PARAMETERS_NONE() \
	ZEND_PARSE_PARAMETERS_START(0, 0) \
	ZEND_PARSE_PARAMETERS_END()
#endif


/* {{{ double hidemaru_version() */
PHP_FUNCTION(hidemaru_version)
{
	ZEND_PARSE_PARAMETERS_NONE();

	double ret_var = CHidemaruExeExport::hm_version;

	RETURN_DOUBLE(ret_var);
}

/* {{{ void hidemaru_debuginfo( [ string $var ] ) */
PHP_FUNCTION(hidemaru_debuginfo)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	wstring utf16_value = utf8_to_utf16(var);
	OutputDebugStream(utf16_value.c_str());
}
/* }}}*/

/* {{{ int hidemaru_getwindowhandle() */
PHP_FUNCTION(hidemaru_getwindowhandle)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	HWND hWndHidemaru = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
	RETURN_LONG((zend_long)hWndHidemaru);
}
/* }}}*/


/* {{{ string hidemaru_edit_getfilepath() */
PHP_FUNCTION(hidemaru_edit_getfilepath)
{
	ZEND_PARSE_PARAMETERS_NONE();

	const int WM_HIDEMARUINFO = WM_USER + 181;
	const int HIDEMARUINFO_GETFILEFULLPATH = 4;
	HWND hWndHidemaru = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
	if (hWndHidemaru) {
		wchar_t filepath[MAX_PATH*3] = L"";
		int cwch = SendMessageW(hWndHidemaru, WM_HIDEMARUINFO, HIDEMARUINFO_GETFILEFULLPATH, (LPARAM)filepath);
		string utf8_filepath = utf16_to_utf8(filepath);
		RETURN_STRING(utf8_filepath.c_str());
	}

	RETURN_STRING("");
}
/* }}}*/

/* {{{ string hidemaru_edit_getcursorpos() */
PHP_FUNCTION(hidemaru_edit_getcursorpos)
{
	ZEND_PARSE_PARAMETERS_NONE();

	auto pos = CHidemaruExeExport::GetCursorPos();

	zval lineno, column;

	ZVAL_LONG(&lineno, pos.lineno);
	ZVAL_LONG(&column, pos.column);

	zval pos_arr;
	array_init(&pos_arr);
	zend_hash_index_add(Z_ARRVAL(pos_arr), 0, &lineno);
	zend_hash_index_add(Z_ARRVAL(pos_arr), 1, &column);

	RETURN_ARR(Z_ARRVAL(pos_arr));
}
/* }}}*/

/* {{{ string hidemaru_edit_getcursorposfrommousepos() */
PHP_FUNCTION(hidemaru_edit_getcursorposfrommousepos)
{
	ZEND_PARSE_PARAMETERS_NONE();

	auto pos = CHidemaruExeExport::GetCursorPosFromMousePos();

	zval lineno, column, x, y;

	ZVAL_LONG(&lineno, pos.lineno);
	ZVAL_LONG(&column, pos.column);
	ZVAL_LONG(&x, pos.x);
	ZVAL_LONG(&y, pos.y);

	zval pos_arr;
	array_init(&pos_arr);
	zend_hash_index_add(Z_ARRVAL(pos_arr), 0, &lineno);
	zend_hash_index_add(Z_ARRVAL(pos_arr), 1, &column);
	zend_hash_index_add(Z_ARRVAL(pos_arr), 2, &x);
	zend_hash_index_add(Z_ARRVAL(pos_arr), 3, &y);

	RETURN_ARR(Z_ARRVAL(pos_arr));
}
/* }}}*/


/* {{{ string hidemaru_edit_gettotaltext() */
PHP_FUNCTION(hidemaru_edit_gettotaltext)
{
	ZEND_PARSE_PARAMETERS_NONE();

	wstring utf16_totaltext = CHidemaruExeExport::GetTotalText();
	string utf8_totaltext = utf16_to_utf8(utf16_totaltext);
	RETURN_STRING(utf8_totaltext.c_str());
}
/* }}}*/

/* {{{ bool hidemaru_edit_settotaltext( [ string $var ] ) */
PHP_FUNCTION(hidemaru_edit_settotaltext)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	BOOL success = 0;

	auto dll_invocant = CSelfDllInfo::GetInvocantString();

	wstring utf16_value = utf8_to_utf16(var);
	PushStrVar(utf16_value.data());
	wstring cmd =
		L"begingroupundo;\n"
		L"selectall;\n"
		L"insert dllfuncstrw( " + dll_invocant + L"\"PopStrVar\" );\n"
		L"endgroupundo;\n";

	success = CHidemaruExeExport::EvalMacro(cmd);

	if (success) {
		RETURN_TRUE;
	}
	else {
		RETURN_FALSE;
	}
}
/* }}}*/

/* {{{ string hidemaru_edit_getselectedtext() */
PHP_FUNCTION(hidemaru_edit_getselectedtext)
{
	ZEND_PARSE_PARAMETERS_NONE();

	wstring utf16_selectedtext = CHidemaruExeExport::GetSelectedText();
	string utf8_selectedtext = utf16_to_utf8(utf16_selectedtext);

	RETURN_STRING(utf8_selectedtext.c_str());
}
/* }}}*/

/* {{{ bool hidemaru_edit_setselectedtext( [ string $var ] ) */
PHP_FUNCTION(hidemaru_edit_setselectedtext)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	BOOL success = 0;

	auto dll_invocant = CSelfDllInfo::GetInvocantString();

	wstring utf16_value = utf8_to_utf16(var);
	PushStrVar(utf16_value.data());
	wstring cmd =
		L"if (selecting) {\n"
		L"insert dllfuncstrw( " + dll_invocant + L"\"PopStrVar\" );\n"
		L"};\n";

	success = CHidemaruExeExport::EvalMacro(cmd);

	if (success) {
		RETURN_TRUE;
	}
	else {
		RETURN_FALSE;
	}
}
/* }}}*/


/* {{{ string hidemaru_edit_getlinetext() */
PHP_FUNCTION(hidemaru_edit_getlinetext)
{
	ZEND_PARSE_PARAMETERS_NONE();

	wstring utf16_linetext = CHidemaruExeExport::GetLineText();
	string utf8_linetext = utf16_to_utf8(utf16_linetext);

	RETURN_STRING(utf8_linetext.c_str());
}
/* }}}*/

/* {{{ bool hidemaru_edit_setlinetext( [ string $var ] ) */
PHP_FUNCTION(hidemaru_edit_setlinetext)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	BOOL success = 0;
	auto dll_invocant = CSelfDllInfo::GetInvocantString();

	auto pos = CHidemaruExeExport::GetCursorPos();

	wstring utf16_value = utf8_to_utf16(var);
	PushStrVar(utf16_value.data());
	wstring cmd =
		L"begingroupundo;\n"
		L"selectline;\n"
		L"insert dllfuncstrw( " + dll_invocant + L"\"PopStrVar\" );\n"
		L"moveto2 " + std::to_wstring(pos.column) + L", " + std::to_wstring(pos.lineno) + L";\n" +
		L"endgroupundo;\n";

	success = CHidemaruExeExport::EvalMacro(cmd);

	if (success) {
		RETURN_TRUE;
	}
	else {
		RETURN_FALSE;
	}
}
/* }}}*/

BOOL Macro_IsExecuting() {

	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		const int WM_ISMACROEXECUTING = WM_USER + 167;
		LRESULT r = SendMessageW(hHidemaruWindow, WM_ISMACROEXECUTING, 0, 0);
		return (BOOL)r;
	}

	return FALSE;
}

/* {{{ int hidemaru_macro_isexecuting() */
PHP_FUNCTION(hidemaru_macro_isexecuting)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	int result = Macro_IsExecuting();
	if (result) {
		RETURN_TRUE;
	}

	RETURN_FALSE;
}


/* {{{ bool hidemaru_macro_eval( [ string $var ] ) */
PHP_FUNCTION(hidemaru_macro_eval)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	wstring utf16_expression = utf8_to_utf16(var);
	BOOL success = CHidemaruExeExport::EvalMacro(utf16_expression);
	if (success) {
		RETURN_TRUE;
	}
	else {
		/*
		*
		php_error(L"マクロの実行に失敗しました。\n");
		OutputDebugStream(L"マクロ内容:\n");
		OutputDebugStream(utf16_expression);
		*/
		RETURN_FALSE;
	}
}
/* }}}*/

/* {{{ (int, object) hidemaru_macro_eval_function( [ string $var ] ) */
PHP_FUNCTION(hidemaru_macro_eval_function)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	wstring utf16_expression = utf8_to_utf16(var);
	TestDynamicVar.Clear();
	auto dll_invocant = CSelfDllInfo::GetInvocantString();
	wstring cmd =
		L"##_tmp_dll_id_ret = dllfuncw( " + dll_invocant + L"\"SetDynamicVar\", " + utf16_expression + L");\n"
		L"##_tmp_dll_id_ret = 0;\n";

	BOOL success = CHidemaruExeExport::EvalMacro(cmd);

	// 数値なら
	if (TestDynamicVar.type == CDynamicValue::TDynamicType::TypeInteger)
	{
		zval result;
		ZVAL_LONG(&result, success);

		zval ivalue;
		ZVAL_LONG(&ivalue, TestDynamicVar.num);

		zval ret_arr;
		array_init(&ret_arr);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 0, &result);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 1, &ivalue);

		RETURN_ARR(Z_ARRVAL(ret_arr));
	}
	// 文字列なら
	else {
		zval result;
		ZVAL_LONG(&result, success);

		string utf8_value = utf16_to_utf8(TestDynamicVar.wstr);
		zval svalue;
		ZVAL_STRING(&svalue, utf8_value.c_str());

		zval ret_arr;
		array_init(&ret_arr);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 0, &result);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 1, &svalue);

		RETURN_ARR(Z_ARRVAL(ret_arr));
	}
}
/* }}}*/


struct TMacroResult {
	int Result;
	wstring Message;
	wstring Error;
};
TMacroResult Macro_Exec_EvalMemory(wstring utf16_expression) {
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {

		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		const int WM_REMOTE_EXECMACRO_MEMORY = WM_USER + 272;

		WCHAR wszReturn[65535];
		*(WORD*)wszReturn = sizeof(wszReturn) / sizeof(wszReturn[0]); // 最初のバイトにバッファーのサイズを格納することで秀丸本体がバッファーサイズの上限を知る。
		LRESULT lRet = SendMessage(hHidemaruWindow, WM_REMOTE_EXECMACRO_MEMORY, (WPARAM)wszReturn, (LPARAM)utf16_expression.c_str());
		if (lRet) {
			TMacroResult ret_tuple;
			ret_tuple.Result = lRet;
			ret_tuple.Message = wszReturn;
			ret_tuple.Error = L"";
			return ret_tuple;
		}
		else {
			OutputDebugStream(L"マクロの実行に失敗しました。\n");
			OutputDebugStream(L"マクロ内容:\n");
			OutputDebugStream(utf16_expression);
			TMacroResult ret_tuple;
			ret_tuple.Result = lRet;
			ret_tuple.Message = L"";
			ret_tuple.Error = L"HidemaruMacroExecEvalException";
			return ret_tuple;
		}
	}
	TMacroResult ret_tuple;
	ret_tuple.Result = 0;
	ret_tuple.Message = L"";
	ret_tuple.Error = L"HidemaruMacroExecEvalException";
	return ret_tuple;
}

/* {{{ bool hidemaru_macro_exec_eval_memory( [ string $var ] ) */
PHP_FUNCTION(hidemaru_macro_exec_eval_memory)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	wstring utf16_expression = utf8_to_utf16(var);
	TMacroResult ret = Macro_Exec_EvalMemory(utf16_expression);
	if (ret.Result > 0) {
		zval result;
		ZVAL_LONG(&result, ret.Result);

		string utf8_message = utf16_to_utf8(ret.Message);
		zval message = { {0} };
		ZVAL_STRING(&message, utf8_message.c_str());

		string utf8_error = utf16_to_utf8(ret.Error);
		zval error = { {0} };
		ZVAL_STRING(&error, utf8_error.c_str());

		zval ret_arr;
		array_init(&ret_arr);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 0, &result);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 1, &message);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 2, &error);

		RETURN_ARR(Z_ARRVAL(ret_arr));
	}
	else {
		zval result;
		ZVAL_LONG(&result, ret.Result);

		zval message = { {0} };
		ZVAL_STRING(&message, "");

		string utf8_error = utf16_to_utf8(ret.Error);
		zval error = { {0} };
		ZVAL_STRING(&error, utf8_error.c_str());

		zval ret_arr;
		array_init(&ret_arr);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 0, &result);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 1, &message);
		zend_hash_index_add(Z_ARRVAL(ret_arr), 2, &error);

		RETURN_ARR(Z_ARRVAL(ret_arr));
	}
}
/* }}}*/


/* {{{ bool hidemaru_macro_getvar( [ string $var ] ) */
PHP_FUNCTION(hidemaru_macro_getvar)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
	Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	wstring utf16_simbol = utf8_to_utf16(var);
	TestDynamicVar.Clear();
	auto dll_invocant = CSelfDllInfo::GetInvocantString();
	wstring cmd =
	L"##_tmp_dll_id_ret = dllfuncw( " + dll_invocant + L"\"SetDynamicVar\", " + utf16_simbol + L");\n"
	L"##_tmp_dll_id_ret = 0;\n";

	BOOL success = CHidemaruExeExport::EvalMacro(cmd);

	// 数値なら
	if (TestDynamicVar.type == CDynamicValue::TDynamicType::TypeInteger)
	{
		RETURN_LONG(TestDynamicVar.num);
	}
	// 文字列なら
	else {
		string utf8_value = utf16_to_utf8(TestDynamicVar.wstr);
		RETURN_STRING(utf8_value.c_str());
	}
}
/* }}}*/


/* {{{ bool hidemaru_macro_setvar( [ string $var ] ) */
PHP_FUNCTION(hidemaru_macro_setvar)
{
	char* var = NULL;
	size_t var_size;
	char* value = NULL;
	size_t value_size;

	ZEND_PARSE_PARAMETERS_START(2, 2)
		Z_PARAM_STRING(var, var_size)
		Z_PARAM_STRING(value, value_size)
	ZEND_PARSE_PARAMETERS_END();

	BOOL success = 0;
	auto dll_invocant = CSelfDllInfo::GetInvocantString();

	wstring utf16_simbol = utf8_to_utf16(var);
	wchar_t start = utf16_simbol[0];
	if (start == L'#') {

		wstring utf16_value = utf8_to_utf16(value);

		// 数字を数値にトライ。ダメなら0だよ。
		intHM_t n = 0;
		try {
			n = (intHM_t)std::stoll(utf16_value);
		}
		catch (...) {}

		PushNumVar(n);
		wstring cmd = L" = dllfuncw( " + dll_invocant + L"\"PopNumVar\" );\n";
		cmd = utf16_simbol + cmd;
		success = CHidemaruExeExport::EvalMacro(cmd);
	}
	else if (start == L'$') {

		wstring utf16_value = utf8_to_utf16(value);

		PushStrVar(utf16_value.data());
		wstring cmd = L" = dllfuncstrw( " + dll_invocant + L"\"PopStrVar\" );\n";
		cmd = utf16_simbol + cmd;
		success = CHidemaruExeExport::EvalMacro(cmd);
	}

	if (success) {
		RETURN_TRUE;
	}
	else {
		RETURN_FALSE;
	}
}
/* }}}*/


/* {{{ bool hidemaru_outputpane_output( [ string $var ] ) */
PHP_FUNCTION(hidemaru_outputpane_output)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmOutputPane_Output) {

			wstring utf16_value = utf8_to_utf16(var);

			auto encode_byte_data = EncodeWStringToOriginalEncodeVector(utf16_value);
			BOOL result = CHidemaruExeExport::HmOutputPane_Output(hHidemaruWindow, encode_byte_data.data());
			if (result) {
				RETURN_TRUE;
			}
		}
	}

	RETURN_FALSE;
}
/* }}}*/

/* {{{ bool hidemaru_outputpane_setbasedir( [ string $var ] ) */
PHP_FUNCTION(hidemaru_outputpane_setbasedir)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmOutputPane_SetBaseDir) {

			wstring utf16_value = utf8_to_utf16(var);

			auto encode_byte_data = EncodeWStringToOriginalEncodeVector(utf16_value);
			BOOL result = CHidemaruExeExport::HmOutputPane_SetBaseDir(hHidemaruWindow, encode_byte_data.data());
			if (result) {
				RETURN_TRUE;
			}
		}
	}

	RETURN_FALSE;
}
/* }}}*/


/* {{{ bool hidemaru_outputpane_push() */
PHP_FUNCTION(hidemaru_outputpane_push)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmOutputPane_Push) {

			BOOL result = CHidemaruExeExport::HmOutputPane_Push(hHidemaruWindow);
			if (result) {
				RETURN_TRUE;
			}
		}
	}

	RETURN_FALSE;
}
/* }}}*/

/* {{{ bool hidemaru_outputpane_pop() */
PHP_FUNCTION(hidemaru_outputpane_pop)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmOutputPane_Pop) {

			BOOL result = CHidemaruExeExport::HmOutputPane_Pop(hHidemaruWindow);
			if (result) {
				RETURN_TRUE;
			}
		}
	}

	RETURN_FALSE;
}
/* }}}*/

// アウトプットパネルのハンドルの取得  (この関数はPHP層へは公開していない)
static HWND OutputPane_GetWindowHanndle() {

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmOutputPane_GetWindowHandle) {
			return CHidemaruExeExport::HmOutputPane_GetWindowHandle(hHidemaruWindow);
		}
	}

	return NULL;
}

/* {{{ int hidemaru_outputpane_getwindowhandle() */
PHP_FUNCTION(hidemaru_outputpane_getwindowhandle)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	HWND OutputWindowHandle = OutputPane_GetWindowHanndle();
	RETURN_LONG((zend_long)OutputWindowHandle);
}
/* }}}*/

/* {{{ int hidemaru_outputpane_sendmessage( [int $var] ) */
PHP_FUNCTION(hidemaru_outputpane_sendmessage)
{
	zend_long var;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_LONG(var)
	ZEND_PARSE_PARAMETERS_END();

	zend_long retval = 0;

	// ちゃんと関数がある時だけ
	HWND OutputWindowHandle = OutputPane_GetWindowHanndle();
	if (OutputWindowHandle) {
		// (#h,0x111/*WM_COMMAND*/,1009,0); //1009=クリア
		// 0x111 = WM_COMMAND
		zend_long command_id = var;
		LRESULT r = SendMessageW(OutputWindowHandle, 0x111, command_id, 0);
		retval = r;
		RETURN_LONG(retval);
	}

	RETURN_LONG(0);
}
/* }}}*/

/* {{{ int hidemaru_outputpane_clear() */
PHP_FUNCTION(hidemaru_outputpane_clear)
{
	ZEND_PARSE_PARAMETERS_NONE();

	zend_long retval = 0;

	// ちゃんと関数がある時だけ
	HWND OutputWindowHandle = OutputPane_GetWindowHanndle();
	if (OutputWindowHandle) {
		// (#h,0x111/*WM_COMMAND*/,1009,0); //1009=クリア
		// 0x111 = WM_COMMAND
		zend_long command_id = 1009;
		LRESULT r = SendMessageW(OutputWindowHandle, 0x111, command_id, 0);
		retval = r;
		RETURN_LONG(retval);
	}

	RETURN_LONG(0);
}
/* }}}*/

/* {{{ int hidemaru_explorerpane_setmode( [int $var] ) */
PHP_FUNCTION(hidemaru_explorerpane_setmode)
{
	zend_long var;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_LONG(var)
	ZEND_PARSE_PARAMETERS_END();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();

		zend_long mode = var;
		int result = CHidemaruExeExport::HmExplorerPane_SetMode(hHidemaruWindow, mode);
		if (result) {
			RETURN_TRUE;
		}
	}

	RETURN_FALSE;
}
/* }}}*/

/* {{{ int hidemaru_explorerpane_getmode() */
PHP_FUNCTION(hidemaru_explorerpane_getmode)
{
	ZEND_PARSE_PARAMETERS_NONE();

	zend_long retval = 0;

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		int r = CHidemaruExeExport::HmExplorerPane_GetMode(hHidemaruWindow);
		retval = r;
		RETURN_LONG(retval);
	}

	RETURN_LONG(0);
}
/* }}}*/

/* {{{ bool hidemaru_explorerpane_loadproject( [ string $var ] ) */
PHP_FUNCTION(hidemaru_explorerpane_loadproject)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmExplorerPane_LoadProject) {

			wstring utf16_value = utf8_to_utf16(var);

			auto encode_byte_data = EncodeWStringToOriginalEncodeVector(utf16_value);
			BOOL result = CHidemaruExeExport::HmExplorerPane_LoadProject(hHidemaruWindow, encode_byte_data.data());
			if (result) {
				RETURN_TRUE;
			}
		}
	}

	RETURN_FALSE;
}
/* }}}*/

/* {{{ bool hidemaru_explorerpane_saveproject( [ string $var ] ) */
PHP_FUNCTION(hidemaru_explorerpane_saveproject)
{
	char* var = NULL;
	size_t var_size;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(var, var_size)
	ZEND_PARSE_PARAMETERS_END();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmExplorerPane_SaveProject) {

			wstring utf16_value = utf8_to_utf16(var);

			auto encode_byte_data = EncodeWStringToOriginalEncodeVector(utf16_value);
			BOOL result = CHidemaruExeExport::HmExplorerPane_SaveProject(hHidemaruWindow, encode_byte_data.data());
			if (result) {
				RETURN_TRUE;
			}
		}
	}

	RETURN_FALSE;
}
/* }}}*/

/* {{{ int hidemaru_explorerpane_getupdated() */
PHP_FUNCTION(hidemaru_explorerpane_getupdated)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		int result = CHidemaruExeExport::HmExplorerPane_GetUpdated(hHidemaruWindow);
		if (result) {
			RETURN_TRUE;
		}
	}

	RETURN_FALSE;
}
/* }}}*/

// アウトプットパネルのハンドルの取得  (この関数はPHP層へは公開していない)
static HWND ExplorerPane_GetWindowHanndle() {

	// ちゃんと関数がある時だけ
	if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
		HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
		if (CHidemaruExeExport::HmExplorerPane_GetWindowHandle) {
			return CHidemaruExeExport::HmExplorerPane_GetWindowHandle(hHidemaruWindow);
		}
	}

	return NULL;
}

/* {{{ int hidemaru_explorerpane_getwindowhandle() */
PHP_FUNCTION(hidemaru_explorerpane_getwindowhandle)
{
	ZEND_PARSE_PARAMETERS_NONE();

	// ちゃんと関数がある時だけ
	HWND ExplorerWindowHandle = ExplorerPane_GetWindowHanndle();
	RETURN_LONG((zend_long)ExplorerWindowHandle);
}
/* }}}*/

/* {{{ int hidemaru_explorerpane_sendmessage( [int $var] ) */
PHP_FUNCTION(hidemaru_explorerpane_sendmessage)
{
	zend_long var;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_LONG(var)
	ZEND_PARSE_PARAMETERS_END();

	zend_long retval = 0;

	// ちゃんと関数がある時だけ
	HWND ExplorerWindowHandle = ExplorerPane_GetWindowHanndle();
	if (ExplorerWindowHandle) {
		// (#h,0x111/*WM_COMMAND*/,1009,0); //1009=クリア
		// 0x111 = WM_COMMAND
		zend_long command_id = var;
		LRESULT r = SendMessageW(ExplorerWindowHandle, 0x111, command_id, 0);
		retval = r;
		RETURN_LONG(retval);
	}

	RETURN_LONG(0);
}
/* }}}*/

/* {{{ string hidemaru_explorerpane_getcurrentdir() */
PHP_FUNCTION(hidemaru_explorerpane_getcurrentdir)
{
	ZEND_PARSE_PARAMETERS_NONE();

	if (CHidemaruExeExport::HmExplorerPane_GetCurrentDir) {
		if (Macro_IsExecuting()) {
			wstring utf16_expression = LR"RAW(dllfuncstr(loaddll("HmExplorerPane"), "GetCurrentDir", hidemaruhandle(0)))RAW";
			TestDynamicVar.Clear();
			auto dll_invocant = CSelfDllInfo::GetInvocantString();
			wstring cmd =
				L"##_tmp_dll_id_ret = dllfuncw( " + dll_invocant + L"\"SetDynamicVar\", " + utf16_expression + L");\n"
				L"##_tmp_dll_id_ret = 0;\n";

			BOOL success = CHidemaruExeExport::EvalMacro(cmd);
			string utf8_value = utf16_to_utf8(TestDynamicVar.wstr);
			TestDynamicVar.Clear();
			RETURN_STRING(utf8_value.c_str());
		}
		else {
			wstring utf16_expression = LR"RAW(endmacro dllfuncstr(loaddll("HmExplorerPane"), "GetCurrentDir", hidemaruhandle(0));)RAW";
			TMacroResult ret = Macro_Exec_EvalMemory(utf16_expression);
			string utf8_value = utf16_to_utf8(ret.Message);
			RETURN_STRING(utf8_value.c_str());
		}
	}

	RETURN_STRING("");
}
/* }}}*/

/* {{{ string hidemaru_explorerpane_getproject() */
PHP_FUNCTION(hidemaru_explorerpane_getproject)
{
	ZEND_PARSE_PARAMETERS_NONE();

	if (CHidemaruExeExport::HmExplorerPane_GetProject) {
		if (Macro_IsExecuting()) {
			wstring utf16_expression = LR"RAW(dllfuncstr(loaddll("HmExplorerPane"), "GetProject", hidemaruhandle(0)))RAW";
			TestDynamicVar.Clear();
			auto dll_invocant = CSelfDllInfo::GetInvocantString();
			wstring cmd =
				L"##_tmp_dll_id_ret = dllfuncw( " + dll_invocant + L"\"SetDynamicVar\", " + utf16_expression + L");\n"
				L"##_tmp_dll_id_ret = 0;\n";

			BOOL success = CHidemaruExeExport::EvalMacro(cmd);
			string utf8_value = utf16_to_utf8(TestDynamicVar.wstr);
			TestDynamicVar.Clear();
			RETURN_STRING(utf8_value.c_str());
		}
		else {
			wstring utf16_expression = LR"RAW(endmacro dllfuncstr(loaddll("HmExplorerPane"), "GetProject", hidemaruhandle(0));)RAW";
			TMacroResult ret = Macro_Exec_EvalMemory(utf16_expression);
			string utf8_value = utf16_to_utf8(ret.Message);
			RETURN_STRING(utf8_value.c_str());
		}
	}

	RETURN_STRING("");
}
/* }}}*/

/*
* 
RETURN_RESOURCE(l)
RETURN_BOOL(b)
RETURN_NULL()
RETURN_LONG(l)
RETURN_DOUBLE(d)
RETURN_STRING(s, dup)
RETURN_STRINGL(s, l, dup)
RETURN_EMPTY_STRING()
RETURN_FALSE
RETURN_TRUE
*/

/* {{{ PHP_RINIT_FUNCTION */
PHP_RINIT_FUNCTION(hidemaru)
{
#if defined(ZTS) && defined(COMPILE_DL_HIDEMARU)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif
	CHidemaruExeExport::init();
	zend_string* str;
	str = zend_string_init("Hm", strlen("Hm"), 0);
	zend_bool zb = false;
	if (zend_register_auto_global(str, zb, NULL) == FAILURE) {
		// zend_string_release(str);
	}
	else {
	}
	// zend_string_release(str);
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION */
PHP_MINFO_FUNCTION(hidemaru)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "hidemaru support", "enabled");
	php_info_print_table_end();
}
/* }}} */

/* {{{ hidemaru_module_entry */
zend_module_entry hidemaru_module_entry = {
	STANDARD_MODULE_HEADER,
	"hidemaru",					/* Extension name */
	ext_functions,					/* zend_function_entry */
	NULL,							/* PHP_MINIT - Module initialization */
	NULL,							/* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(hidemaru),			/* PHP_RINIT - Request initialization */
	NULL,							/* PHP_RSHUTDOWN - Request shutdown */
	PHP_MINFO(hidemaru),			/* PHP_MINFO - Module info */
	PHP_HIDEMARU_VERSION,		/* Version */
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_HIDEMARU
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(hidemaru)
#endif


//--------------------------------------------------------------------------------

