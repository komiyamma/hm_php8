/* mytest extension for PHP */
#define ZEND_DEBUG 0

/* hidemaru extension for PHP */


#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "php_hidemaru.h"
#include "hidemaru_arginfo.h"
#include "convert_string.h"
#include "hidemaru_interface.h"
#include "hidemaruexe_export.h"

/* For compatibility with older PHP versions */
#ifndef ZEND_PARSE_PARAMETERS_NONE
#define ZEND_PARSE_PARAMETERS_NONE() \
	ZEND_PARSE_PARAMETERS_START(0, 0) \
	ZEND_PARSE_PARAMETERS_END()
#endif

/* {{{ void test1() */
PHP_FUNCTION(test1)
{
	ZEND_PARSE_PARAMETERS_NONE();

	php_printf("The extension %s is loaded and working!\r\n", "hidemaru");
}
/* }}} */

/* {{{ string test2( [ string $var ] ) */
PHP_FUNCTION(test2)
{
	auto str = getaaa();
	char *var = (char *)str.c_str();
	size_t var_len = str.length() - 1;
	zend_string *retval;

	ZEND_PARSE_PARAMETERS_START(0, 1)
		Z_PARAM_OPTIONAL
		Z_PARAM_STRING(var, var_len)
	ZEND_PARSE_PARAMETERS_END();

	retval = strpprintf(0, "Hello %s", var);

	RETURN_STR(retval);
}
/* }}}*/

/* {{{ double hidemaru_version() */
PHP_FUNCTION(hidemaru_version)
{
	ZEND_PARSE_PARAMETERS_NONE();

	double ret_var = CHidemaruExeExport::hm_version;

	RETURN_DOUBLE(ret_var);
}

/* {{{ string hidemaru_edit_totaltext() */
PHP_FUNCTION(hidemaru_edit_totaltext)
{
	ZEND_PARSE_PARAMETERS_NONE();

	wstring utf16_totaltext = CHidemaruExeExport::GetTotalText();
	string utf8_totaltext = utf16_to_utf8(utf16_totaltext);
	zend_string* retval;
	retval = strpprintf(0, "%s", utf8_totaltext.c_str());

	RETURN_STR(retval);
}
/* }}}*/

/* {{{ string hidemaru_edit_selectedtext() */
PHP_FUNCTION(hidemaru_edit_selectedtext)
{
	ZEND_PARSE_PARAMETERS_NONE();

	wstring utf16_selectedtext = CHidemaruExeExport::GetSelectedText();
	string utf8_selectedtext = utf16_to_utf8(utf16_selectedtext);
	zend_string* retval;
	retval = strpprintf(0, "%s", utf8_selectedtext.c_str());

	RETURN_STR(retval);
}
/* }}}*/

/* {{{ string hidemaru_edit_linetext() */
PHP_FUNCTION(hidemaru_edit_linetext)
{
	ZEND_PARSE_PARAMETERS_NONE();

	wstring utf16_linetext = CHidemaruExeExport::GetLineText();
	string utf8_linetext = utf16_to_utf8(utf16_linetext);
	zend_string* retval;
	retval = strpprintf(0, "%s", utf8_linetext.c_str());

	RETURN_STR(retval);
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
