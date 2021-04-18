#include "windows.h"
#include <string>
using namespace std;

wchar_t szEnv[4096] = { L"\0" };

extern "C" BOOL WINAPI DllMain(HINSTANCE hinstDll, DWORD dwReason, LPVOID lpReserved) {

	switch (dwReason) {

	case DLL_PROCESS_ATTACH: // DLLがプロセスのアドレス空間にマッピングされた。
		if (wcsstr(szEnv, L"C:\\usr\\php;") == NULL) {
			GetEnvironmentVariableW(L"PATH", szEnv, _countof(szEnv));
			wstring path = wstring(L"C:\\usr\\php;") + szEnv;
			SetEnvironmentVariableW(L"PATH", path.c_str());
		}

		break;

	case DLL_THREAD_ATTACH: // スレッドが作成されようとしている。
		break;

	case DLL_THREAD_DETACH: // スレッドが破棄されようとしている。
		break;

	case DLL_PROCESS_DETACH: // DLLのマッピングが解除されようとしている。
		break;

	}

	return TRUE;
}

extern "C" __declspec(dllexport) intptr_t DoFile(const wchar_t* utf16_filepath) {

	HMODULE hmod = LoadLibrary(L"C:/usr/php/php8embed_hidemaru.dll");
	if (hmod) {
		MessageBox(NULL, L"HasMod", L"HasMod", NULL);
		using PFNDOFILE = intptr_t(WINAPI*)(const wchar_t*);

		PFNDOFILE pDoFile = (PFNDOFILE)GetProcAddress(hmod, "DoFile");

		if (pDoFile) {
			MessageBox(NULL, L"pDoFile", L"pDoFile", NULL);
			return pDoFile(utf16_filepath);
		}
	}

	return 0;
}
