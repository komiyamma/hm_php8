/* 
 * Copyright (c) 2017 Akitsugu Komiyama
 * under the Apache License Version 2.0
 */


#include <windows.h>

#include "convert_string.h"
#include "hidemaruexe_export.h"
#include "self_dll_info.h"

#include "dllfunc_interface.h"
#include "dllfunc_interface_internal.h"


using namespace std;

extern BOOL DoCreate();
extern BOOL DoDestroy();
extern intHM_t Include(const wchar_t* utf16_expression);
extern intHM_t PHPGetNumVar(const wchar_t* utf16_simbol);
extern intHM_t PHPSetNumVar(const wchar_t* utf16_simbol, intHM_t value);
extern wstring PHPGetStrVar(wstring utf16_simbol);
//------------------------------------------------------------------------------------
// 対象のシンボル名の値を数値として得る
MACRO_DLL intHM_t GetNumVar(const wchar_t *utf16_simbol) {
	if (DoCreate() == FALSE)
	{
		return 0;
	}

	CSelfDllInfo::SetBindDllHandle();

	intHM_t r = PHPGetNumVar(utf16_simbol);
	return r;
}

// 対象のシンボル名の値に数値を代入する
MACRO_DLL intHM_t SetNumVar(const wchar_t *utf16_simbol, intHM_t value) {
	if (DoCreate() == FALSE)
	{
		return 0;
	}

	CSelfDllInfo::SetBindDllHandle();

	BOOL r = PHPSetNumVar(utf16_simbol, value);
	return (intHM_t)r;
}

// 対象のシンボル名の値を文字列として得る
static wstring strvars; // 秀丸のキャッシュのため
MACRO_DLL const wchar_t * GetStrVar(const wchar_t *utf16_simbol) {
	// クリア必須
	strvars.clear();

	if (DoCreate() == FALSE)
	{
		return strvars.c_str();
	}

	CSelfDllInfo::SetBindDllHandle();

	strvars = PHPGetStrVar(utf16_simbol);

	return strvars.c_str();
}

// 対象のシンボル名の値に文字列を代入する
MACRO_DLL intHM_t SetStrVar(const wchar_t *utf16_simbol, const wchar_t *utf16_value) {
	if (DoCreate() == FALSE)
	{
		return 0;
	}

	CSelfDllInfo::SetBindDllHandle();

	// BOOL r = PHPEngine::SetStrVar(utf16_simbol, utf16_value);

	intHM_t r = 0;
	return (intHM_t)r;
}

// 対象の文字列をPythonの複数式とみなして評価する
MACRO_DLL intHM_t DoFile(const wchar_t *utf16_expression) {
	if (DoCreate() == FALSE)
	{
		return 0;
	}

	// DoStringされる度にdllのBindの在り方を確認更新する。
	CSelfDllInfo::SetBindDllHandle();

	//-------------------------------------------------------------------------
	// ほとんどの場合、この「DoString」しか使われないハズなので、
	// この関数だけチェックしておく。
	//-------------------------------------------------------------------------
	auto rtn_type = (CHidemaruExeExport::DLLFUNCRETURN)CHidemaruExeExport::Hidemaru_GetDllFuncCalledType(0); // 0は返り値の型
	if (rtn_type == CHidemaruExeExport::DLLFUNCRETURN::CHAR_PTR || rtn_type == CHidemaruExeExport::DLLFUNCRETURN::WCHAR_PTR) {
		MessageBox(NULL, L"返り値の型が異なります。\ndllfuncstrではなく、dllfuncw文を利用してください。", L"返り値の型が異なります", MB_ICONERROR);
	}

	auto arg_type = (CHidemaruExeExport::DLLFUNCPARAM)CHidemaruExeExport::Hidemaru_GetDllFuncCalledType(1); // 1は1番目の引数
	if (arg_type != CHidemaruExeExport::DLLFUNCPARAM::WCHAR_PTR) {
		MessageBox(NULL, L"引数の型が異なります。\ndllfuncではなく、dllfuncw文を利用してください。", L"引数の型が異なります", MB_ICONERROR);
	}

	intHM_t r = Include(utf16_expression);

	return r;
}

// エンジンの破棄
MACRO_DLL intHM_t DestroyScope() {
	intHM_t r = DoDestroy();
	return r;
}


// マクロでfreedllが呼ばれた時(暗黙の呼び出し含む)、freedllを呼び出していないなら、秀丸の該当プロセスが終了した際に呼ばれる
MACRO_DLL intHM_t DllDetachFunc_After_Hm866() {
	return DestroyScope();
}

