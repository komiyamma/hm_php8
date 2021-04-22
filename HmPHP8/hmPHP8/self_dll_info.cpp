/* 
 * Copyright (c) 2017 Akitsugu Komiyama
 * under the Apache License Version 2.0
 */

#include <windows.h>
#include <shlwapi.h>

#include "self_dll_info.h"
#include "hidemaruexe_export.h"

#pragma comment(lib, "shlwapi.lib")


HMODULE CSelfDllInfo::hModule = NULL;

wchar_t CSelfDllInfo::szSelfModuleFullPath[MAX_PATH] = L"";
wchar_t CSelfDllInfo::szSelfModuleDirPath[MAX_PATH] = L"";

int CSelfDllInfo::iSelfBindedType = 0;

void CSelfDllInfo::InitializeHandle(HMODULE hModule) {
	CSelfDllInfo::hModule = hModule;
	GetModuleFileName(hModule, CSelfDllInfo::szSelfModuleFullPath, _countof(CSelfDllInfo::szSelfModuleFullPath));
	wcscpy_s(CSelfDllInfo::szSelfModuleDirPath, CSelfDllInfo::szSelfModuleFullPath);
	PathRemoveFileSpec(CSelfDllInfo::szSelfModuleDirPath);
}

int CSelfDllInfo::GetBindDllType() {
	return iSelfBindedType;
}



extern HMODULE hmod_php_hidemaru;
using PFNDllIDOfEmbedHidemaru = void (*)(int DllIDOfEmbedHidemaru);
PFNDllIDOfEmbedHidemaru pNDllIDOfEmbedHidemaru = NULL;
BOOL CSelfDllInfo::SetBindDllHandle() {
	// �{�̂���̊֐��̐�������
	CHidemaruExeExport::init();



	// �G��8.66�ȏ�
	if (CHidemaruExeExport::Hidemaru_GetDllFuncCalledType) {
		int dll = CHidemaruExeExport::Hidemaru_GetDllFuncCalledType(-1); // ������dll�̌Ă΂�����`�F�b�N
		CSelfDllInfo::iSelfBindedType = dll;

		MessageBox(NULL, L"SetBindDllHandle", L"SetBindDllHandle in embed_hidemaru", NULL);

		if (hmod_php_hidemaru) {
			if (!pNDllIDOfEmbedHidemaru) {
				pNDllIDOfEmbedHidemaru = (PFNDllIDOfEmbedHidemaru)GetProcAddress(hmod_php_hidemaru, "dll_id_of_embed_hideamru");
			}
			if (pNDllIDOfEmbedHidemaru) {
				pNDllIDOfEmbedHidemaru(iSelfBindedType);
			}
		}

		return TRUE;
	}
	else {
		MessageBox(NULL, L"loadll�̃p�^�[�����F���o���܂���ł����B", L"loadll�̃p�^�[�����F���o���܂���ł����B", MB_ICONERROR);
	}


	return FALSE;

}

wstring CSelfDllInfo::GetInvocantString() {
	if (iSelfBindedType == -1) {
		return L"";
	}
	else {
		return to_wstring(iSelfBindedType) + L",";
	}
}

wstring CSelfDllInfo::GetSelfModuleFullPath() {
	return szSelfModuleFullPath;
}

wstring CSelfDllInfo::GetSelfModuleDir() {
	return szSelfModuleDirPath;
}
