#include "windows.h"
#include <string>
#include "convert_string.h"

using namespace std;

BOOL entry_point_is_hidemaru = FALSE;

BOOL isEntryPointIsHidemaru() {
	return entry_point_is_hidemaru;
}

extern "C" __declspec(dllexport) void abc() {
	entry_point_is_hidemaru = TRUE;
}
