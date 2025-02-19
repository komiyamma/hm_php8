#pragma once

#include "windows.h"
#include <string>

#include "dllfunc_interface.h"

using namespace std;

extern BOOL DoCreate();
extern BOOL DoDestroy();
extern intHM_t Include(const wchar_t* utf16_expression);
extern intHM_t PHPGetNumVar(const wchar_t* utf16_simbol);
extern intHM_t PHPSetNumVar(const wchar_t* utf16_simbol, intHM_t value);
extern wstring PHPGetStrVar(wstring utf16_simbol);
extern BOOL PHPSetStrVar(wstring utf16_simbol, wstring utf16_value);
