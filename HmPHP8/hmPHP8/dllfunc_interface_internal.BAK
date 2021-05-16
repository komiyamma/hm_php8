/* 
 * Copyright (c) 2017 Akitsugu Komiyama
 * under the Apache License Version 2.0
 */

#include <windows.h>

#include "hidemaruexe_export.h"
#include "dllfunc_interface_internal.h"

using namespace std;

extern HMODULE hmod_php_hidemaru;

CDynamicValue TestDynamicVar;
void CDynamicValue::Clear() {
	this->num = 0;
	this->wstr = L"";
	this->type = TDynamicType::TypeUnknown;
}


using PFNSetDynamicVar = intHM_t(*)(const void* synamic_value);
PFNSetDynamicVar pSetDynamicVar = NULL;
// 秀丸の変数が文字列か数値かの判定用
MACRO_DLL intHM_t SetDynamicVar(const void* dynamic_value) {

	if (!hmod_php_hidemaru) {
		return 0;
	}

	if (!pSetDynamicVar) {
		pSetDynamicVar = (PFNSetDynamicVar)GetProcAddress(hmod_php_hidemaru, "SetDynamicVar");
	}

	if (pSetDynamicVar) {
		return pSetDynamicVar(dynamic_value);
	}

	return 0;
}


using PFNPopNumVar = intHM_t(*)();
PFNPopNumVar pPopNumVar = NULL;

// スタックした変数を秀丸マクロから取り出す。内部処理用
MACRO_DLL intHM_t PopNumVar() {

	if (!hmod_php_hidemaru) {
		return 0;
	}

	if (!pPopNumVar) {
		pPopNumVar = (PFNPopNumVar)GetProcAddress(hmod_php_hidemaru, "PopNumVar");
	}

	if (pPopNumVar) {
		return pPopNumVar();
	}

	return 0;
}

using PFNPushNumVar = intHM_t(*)(intHM_t i_tmp_num);
PFNPushNumVar pPushNumVar = NULL;

// 変数を秀丸マクロから取り出すためにスタック。内部処理用
MACRO_DLL intHM_t PushNumVar(intHM_t i_tmp_num) {

	if (!hmod_php_hidemaru) {
		return 0;
	}

	if (!pPushNumVar) {
		pPushNumVar = (PFNPushNumVar)GetProcAddress(hmod_php_hidemaru, "PushNumVar");
	}

	if (pPushNumVar) {
		return pPushNumVar(i_tmp_num);
	}

	return 0;
}

using PFNPopStrVar = const wchar_t* (*)();
PFNPopStrVar pPopStrVar = NULL;

// スタックした変数を秀丸マクロから取り出す。内部処理用
static wstring popstrvar;
MACRO_DLL const wchar_t * PopStrVar() {

	if (!hmod_php_hidemaru) {
		return L"";
	}

	if (!pPopStrVar) {
		pPopStrVar = (PFNPopStrVar)GetProcAddress(hmod_php_hidemaru, "PopStrVar");
	}

	popstrvar.clear();
	if (pPopStrVar) {
		popstrvar = pPopStrVar();
		return popstrvar.data();
	}

	return L"";
}

using PFNPushStrVar = intHM_t(*)(const wchar_t* sz_tmp_str);
PFNPushStrVar pPushStrVar = NULL;

// 変数を秀丸マクロから取り出すためにスタック。内部処理用
MACRO_DLL intHM_t PushStrVar(const wchar_t *sz_tmp_str) {

	if (!hmod_php_hidemaru) {
		return 0;
	}

	if (!pPushStrVar) {
		pPushStrVar = (PFNPushStrVar)GetProcAddress(hmod_php_hidemaru, "PushStrVar");
	}

	if (pPushStrVar) {
		return pPushStrVar(sz_tmp_str);
	}

	return 0;
}

