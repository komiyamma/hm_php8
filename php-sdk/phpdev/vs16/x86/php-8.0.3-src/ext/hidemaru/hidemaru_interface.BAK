#include "windows.h"
#include <string>
#include "convert_string.h"

using namespace std;

BOOL entry_point_is_hidemaru = FALSE;

BOOL isEntryPointIsHidemaru() {
	return entry_point_is_hidemaru;
}

extern "C" __declspec(dllexport) void set_entry_is_hidemaru() {
	entry_point_is_hidemaru = TRUE;
}

wstring embed_hideamru_dll_path = L"";
extern "C" __declspec(dllexport) void set_embed_hideamru_dll_path(const wchar_t* dllpath) {
	embed_hideamru_dll_path = wstring(dllpath);
}

int DllIdOfEmbedHideamruBindType = 0;
extern "C" __declspec(dllexport) void dll_id_of_embed_hideamru(int dll_id_of_embed_hidemaru) {
	DllIdOfEmbedHideamruBindType = dll_id_of_embed_hidemaru;
}




