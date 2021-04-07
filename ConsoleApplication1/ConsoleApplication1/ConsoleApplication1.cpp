#include "sapi/embed/php_embed.h"
#include "main/php.h"
#include "Zend/zend_API.h"
#include <string>
#include "convert_string.h"
using namespace std;

/*
PHP_FUNCTION(add_extension) {
    // 引数の格納先
    zend_long val1, val2;

    // 引数をパースして格納先に代入
    ZEND_PARSE_PARAMETERS_START(2, 2)
        Z_PARAM_LONG(val1)
        Z_PARAM_LONG(val2)
    ZEND_PARSE_PARAMETERS_END();

    // 足し算
    zend_long res = val1 + val2;

    // 結果を return する
    RETURN_LONG(res);
}
// add_extension.c
const zend_function_entry add_extension_functions[] = {
    PHP_FE(add_extension, NULL)
    PHP_FE_END
};

// Reflectionあり
ZEND_BEGIN_ARG_INFO_EX(arginfo_add_extension_functions, 0, 0, 2)
ZEND_ARG_INFO(0, val1)
ZEND_ARG_INFO(0, val2)
ZEND_END_ARG_INFO()

*/

static size_t php_ub_write(const char* str, unsigned int str_length)
{
    printf(str);
    return str_length;
}



static void php_log_message(const char* message, int syslog_type_int)

{
    // printf("php_log_message:");
    printf(message);
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


int main(int argc, char* argv[]) {

    php_embed_module.ub_write = php_ub_write;
    php_embed_module.log_message = php_log_message;
    php_embed_module.sapi_error = php_sapi_error;
    
    php_embed_module.php_ini_path_override = (char *)"php.ini";

    PHP_EMBED_START_BLOCK(0, NULL);

    zval retval;
    zend_try{
        SetGlobalStringVariable(L"hello2", L"aあいうえお👪bcefg");

        zend_eval_string_ex((char *)"include 'C:/abc/main.php';", &retval, (char *)"main", 1);
        wstring ret = GetGlobalStringVariable(L"hello3");
        MessageBoxW(NULL, ret.c_str(), ret.c_str(), NULL);
        zend_long ret2 = GetGlobalZLongVariable(L"hello4");
        printf("%d", ret2);
    }
    zend_catch {
        ;
    }
    zend_end_try();

    PHP_EMBED_END_BLOCK();

    return 0;
}