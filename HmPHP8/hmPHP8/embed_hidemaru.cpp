﻿#include "sapi/embed/php_embed.h"
#include "main/php.h"
#include "Zend/zend_API.h"
#include <string>
#include "convert_string.h"
#include "windows.h"
#include "dllfunc_interface.h"
#include "self_dll_info.h"
#include "hidemaruexe_export.h"
#include "hm_original_encode_mapfunc.h"
using namespace std;


/*
    wstring中の特定文字列をwstringで置換する
*/
std::wstring wstrReplaceString
(
    std::wstring str1  // 置き換え対象
    , std::wstring str2  // 検索対象
    , std::wstring str3  // 置き換える内容
)
{
    std::wstring::size_type  iposition(str1.find(str2));

    while (iposition != std::string::npos)
    {
        str1.replace(iposition, str2.length(), str3);
        iposition = str1.find(str2, iposition + str3.length());
    }

    return str1;
}


BOOL HmOutputPane_Output(wstring utf16_message) {
    utf16_message = wstrReplaceString(utf16_message, L"\n", L"\r\n");
    utf16_message = wstrReplaceString(utf16_message, L"\r\r", L"\r");

    // ちゃんと関数がある時だけ
    if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
        HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
        if (CHidemaruExeExport::HmOutputPane_OutputW) {

            BOOL result = CHidemaruExeExport::HmOutputPane_OutputW(hHidemaruWindow, (wchar_t*)utf16_message.data());
            return result;
        }
        else if (CHidemaruExeExport::HmOutputPane_Output) {

            auto encode_byte_data = EncodeWStringToOriginalEncodeVector(utf16_message);
            BOOL result = CHidemaruExeExport::HmOutputPane_Output(hHidemaruWindow, encode_byte_data.data());
            return result;
        }
    }

    return false;
}


static size_t php_ub_write(const char* str, size_t str_length)
{
    // printf(str);
    wstring utf16_message = utf8_to_utf16(str);

    bool OutputPaneDone = false;
    if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
        HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
        bool result = HmOutputPane_Output(utf16_message);
        if (result) {
            OutputPaneDone = true;
        }
    }
    if (!OutputPaneDone) {
        OutputDebugStringW(utf16_message.c_str());
        OutputDebugStringW(L"\n");

    }
    return str_length;
}



static void php_log_message(const char* message, int syslog_type_int)

{
    // printf("php_log_message:");
    // printf(message);
    wstring utf16_message = utf8_to_utf16(message);
    utf16_message += L"\n";
    bool OutputPaneDone = false;
    if (CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle) {
        HWND hHidemaruWindow = CHidemaruExeExport::Hidemaru_GetCurrentWindowHandle();
        bool result = HmOutputPane_Output(utf16_message);
        if (result) {
            OutputPaneDone = true;
        }
    }
    if (!OutputPaneDone) {
        OutputDebugStringW(utf16_message.c_str());
        OutputDebugStringW(L"\n");

    }
    // printf("\n");
}



static void php_sapi_error(int type, const char* fmt, ...)
{
    va_list va;
    va_start(va, fmt);
    // printf("php_sapi_error: ");
    vprintf(fmt, va);
    va_end(va);
}

void SetGlobalStringVariable(wstring utf16_key, wstring utf16_value) {
    string utf8_key = utf16_to_utf8(utf16_key);
    string utf8_value = utf16_to_utf8(utf16_value);
    zval zkey;
    ZVAL_STRING(&zkey, utf8_key.c_str());
    zval ztmp;
    ZVAL_STRING(&ztmp, utf8_value.c_str());
    zend_hash_update(&EG(symbol_table), Z_STR_P(&zkey), &ztmp);
}

void SetGlobalZLongVariable(wstring utf16_key, zend_long value) {
    string utf8_key = utf16_to_utf8(utf16_key);
    zval ztmp;
    ZVAL_LONG(&ztmp,(zend_long)value);
    zval zkey;
    ZVAL_STRING(&zkey, utf8_key.c_str());
    zend_hash_update(&EG(symbol_table), Z_STR_P(&zkey), &ztmp);
}


wstring GetGlobalStringVariable(wstring utf16_key) {
    string utf8_key = utf16_to_utf8(utf16_key);
    zval zkey;
    zend_eval_string_ex((char*)("$" + utf8_key).c_str(), &zkey, (char*)"getglobalstringvariable", 1);
    zend_string* str = zval_get_string(&zkey);
    string ret = str->val;
    zend_string_release(str);
    return utf8_to_utf16(ret);
}

zend_long GetGlobalZLongVariable(wstring utf16_key) {
    string utf8_key = utf16_to_utf8(utf16_key);
    zval zkey;
    zend_eval_string_ex((char*)("$" + utf8_key).c_str(), &zkey, (char*)"getgloballongvariable", 1);
    zend_long lval = zval_get_long(&zkey);
    return lval;
}

void AddPath() {
    wchar_t szEnv[4096] = { L"\0" };

    wstring selfdir = CSelfDllInfo::GetSelfModuleDir();
    wstring path_of_selfdir = selfdir + L";";

    GetEnvironmentVariableW(L"PATH", szEnv, _countof(szEnv));
    if (wcsstr(szEnv, path_of_selfdir.c_str()) == NULL) {
        wstring new_path = path_of_selfdir + szEnv;
        SetEnvironmentVariableW(L"PATH", new_path.c_str());
    }
    else {
        ; // パスを追加する必要はなし
    }
}

HMODULE hmod_php_hidemaru = NULL;
void PinToPhpHidemaruDll()
{
    wstring selfdir = CSelfDllInfo::GetSelfModuleDir();
    wstring php_hidemaru_dllpath = selfdir + L"\\ext\\php_hidemaru.dll";

    hmod_php_hidemaru = LoadLibraryW(php_hidemaru_dllpath.c_str());
    if (hmod_php_hidemaru) {

        using PFNSetEntryIsHidemaru = void (*)();
        PFNSetEntryIsHidemaru set_entry_is_hidemaru = (PFNSetEntryIsHidemaru)GetProcAddress(hmod_php_hidemaru, "set_entry_is_hidemaru");

        if (set_entry_is_hidemaru) {
            set_entry_is_hidemaru();
        }
        else {
            ; // 秀丸以外からの起動
        }

        using PFNSetEmbedHidemaruDllPath = void (*)(const wchar_t *);
        PFNSetEmbedHidemaruDllPath set_embed_hideamru_dll_path = (PFNSetEmbedHidemaruDllPath)GetProcAddress(hmod_php_hidemaru, "set_embed_hideamru_dll_path");

        if (set_embed_hideamru_dll_path) {
            set_embed_hideamru_dll_path(CSelfDllInfo::GetSelfModuleFullPath().c_str());
        }
        else {
            ;
        }

    }
}

void UnPinToPhpHidemaruDll() {
    if (hmod_php_hidemaru) {
        FreeLibrary(hmod_php_hidemaru);
    }
}

void PinToPhpHidemaruDll();
void UnPinToPhpHidemaruDll();
void SetPhpEmbedModule();

BOOL IsValidPhpEngine = FALSE;
BOOL HasDoneZendFirstTry = FALSE;
BOOL DoCreate() {
    if (IsValidPhpEngine) {
        return TRUE;
    }

    AddPath();
    PinToPhpHidemaruDll();

    SetPhpEmbedModule();

    php_embed_init(0, NULL);

    zend_first_try{
    }
    zend_catch{
    }
    zend_end_try();

    IsValidPhpEngine = TRUE;

    return TRUE;
}

char szPhpIniPath[MAX_PATH * 2] = "";
void SetPhpEmbedModule()
{
    php_embed_module.ub_write = php_ub_write;
    php_embed_module.log_message = php_log_message;
    php_embed_module.sapi_error = php_sapi_error;

    // ただポインタを渡せばいいわけではなく、staticなバッファーが必要。
    wstring selfdir = CSelfDllInfo::GetSelfModuleDir();
    strcpy_s(szPhpIniPath, utf16_to_utf8(selfdir + L"/php.ini").c_str());
    char* pPhpIniPath = szPhpIniPath;

    php_embed_module.php_ini_path_override = pPhpIniPath;
    // php_embed_module.executable_location = argv[0];
}
extern BOOL DoDestroy();
extern BOOL DoErrorDestroy();

intHM_t Include(const wchar_t* utf16_filepath) {
    zval retval;

    bool CauseError = FALSE;
    zend_try{
        wstring selfdir = CSelfDllInfo::GetSelfModuleDir();
        wstring include_cmd = L"include_once '" + selfdir + L"\\hmPeach.php';";
        string utf8_include_cmd = utf16_to_utf8(include_cmd);
        zend_eval_string_ex((char*)utf8_include_cmd.c_str(), &retval, (char*)"onEmbedHidemaru_01", 1);
        SetGlobalStringVariable(L"UserIncludePhpFullPath", utf16_filepath);
        zend_eval_string_ex((char*)"include $UserIncludePhpFullPath;", &retval, (char*)"onEmbedMain_02", 1);
    }
    zend_catch{
        CauseError = true;
        MessageBoxW(NULL, L"PHP 実行中に深刻なエラーが発生しました。", L"PHP 致命的エラー", NULL);
    }
    zend_end_try();

    if (CauseError) {
        DoDestroy();
    }

    return 0;
}

BOOL DoErrorDestroy() {
    if (IsValidPhpEngine) {
        UnPinToPhpHidemaruDll();

        php_embed_shutdown();
    }

    CSelfDllInfo::ClearKeepDll();
    IsValidPhpEngine = FALSE;
    HasDoneZendFirstTry = FALSE;

    return TRUE;
}

BOOL DoDestroy() {
    UnPinToPhpHidemaruDll();

    zval retval;

    if (IsValidPhpEngine) {
        zend_try{
            zend_eval_string_ex((char*)"$Hm->onDisposeScope();", &retval, (char*)"onDisposeScope_02", 1);
        }
        zend_catch{
        }
        zend_end_try();

        php_embed_shutdown();
    }

    CSelfDllInfo::ClearKeepDll();
    IsValidPhpEngine = FALSE;
    HasDoneZendFirstTry = FALSE;
    return TRUE;
}

// 対象のシンボル名の値を数値として得る
intHM_t PHPGetNumVar(const wchar_t* utf16_simbol) {

    // 値を得て…
    zend_long value = 0; 

    zend_try{
        HasDoneZendFirstTry = TRUE;
        value = GetGlobalZLongVariable(utf16_simbol);
    }
    zend_catch {
    }
    zend_end_try();

    return value;
}

// 対象のシンボル名の値に数値を代入する
BOOL PHPSetNumVar(const wchar_t* utf16_simbol, intHM_t value) {

    BOOL success = FALSE;

    zend_try{
        HasDoneZendFirstTry = TRUE;
        SetGlobalZLongVariable(utf16_simbol, value);
        success = TRUE;
    }
    zend_catch{
    }
    zend_end_try();

    return success;
}

wstring PHPGetStrVar(wstring utf16_simbol) {
    // 値を得て…
    wstring value = L"";

    zend_try{
        HasDoneZendFirstTry = TRUE;
        value = GetGlobalStringVariable(utf16_simbol);
    }
        zend_catch{
    }
    zend_end_try();

    return value;
}

// 対象のシンボル名の値に文字列を代入する
BOOL PHPSetStrVar(wstring utf16_simbol, wstring utf16_value) {

    BOOL success = FALSE;

    zend_try{
        HasDoneZendFirstTry = TRUE;
        SetGlobalStringVariable(utf16_simbol, utf16_value);
        success = TRUE;
    }
    zend_catch{
    }
    zend_end_try();

    return success;
}
