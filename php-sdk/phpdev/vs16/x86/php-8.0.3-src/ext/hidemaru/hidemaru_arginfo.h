/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 7eb3fd4083c98e6dffc8b02b6373b7ce9cbf228d */

// ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_test1, 0, 0, IS_VOID, 0)
// ZEND_END_ARG_INFO()

// ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_test2, 0, 0, IS_STRING, 0)
// 	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, str, IS_STRING, 0, "\"\"")
// ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_version, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_debuginfo, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_getwindowhandle, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_getfilepath, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_getcursorpos, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_getcursorposfrommousepos, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_gettotaltext, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_getselectedtext, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_getlinetext, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_macro_eval, 0, 0, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_output, 0, 0, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_setbasedir, 0, 0, _IS_BOOL, 0)
ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_push, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_pop, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_sendmessage, 0, 0, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, var, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_clear, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_getwindowhandle, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

// ZEND_FUNCTION(test1);
// ZEND_FUNCTION(test2);


ZEND_FUNCTION(hidemaru_version);
ZEND_FUNCTION(hidemaru_debuginfo);
ZEND_FUNCTION(hidemaru_getwindowhandle);
ZEND_FUNCTION(hidemaru_edit_getfilepath);
ZEND_FUNCTION(hidemaru_edit_getcursorpos);
ZEND_FUNCTION(hidemaru_edit_getcursorposfrommousepos);
ZEND_FUNCTION(hidemaru_edit_gettotaltext);
ZEND_FUNCTION(hidemaru_edit_getselectedtext);
ZEND_FUNCTION(hidemaru_edit_getlinetext);
ZEND_FUNCTION(hidemaru_macro_eval);
ZEND_FUNCTION(hidemaru_outputpane_output);
ZEND_FUNCTION(hidemaru_outputpane_setbasedir);
ZEND_FUNCTION(hidemaru_outputpane_push);
ZEND_FUNCTION(hidemaru_outputpane_pop);
ZEND_FUNCTION(hidemaru_outputpane_sendmessage);
ZEND_FUNCTION(hidemaru_outputpane_clear);
ZEND_FUNCTION(hidemaru_outputpane_getwindowhandle);


static const zend_function_entry ext_functions[] = {
//	ZEND_FE(test1, arginfo_test1)
//	ZEND_FE(test2, arginfo_test2)
	ZEND_FE(hidemaru_version, arginfo_hidemaru_version)
	ZEND_FE(hidemaru_debuginfo, arginfo_hidemaru_debuginfo)
	ZEND_FE(hidemaru_getwindowhandle, arginfo_hidemaru_getwindowhandle)
	ZEND_FE(hidemaru_edit_getfilepath, arginfo_hidemaru_edit_getfilepath)
	ZEND_FE(hidemaru_edit_getcursorpos, arginfo_hidemaru_edit_getcursorpos)
	ZEND_FE(hidemaru_edit_getcursorposfrommousepos, arginfo_hidemaru_edit_getcursorposfrommousepos)
	ZEND_FE(hidemaru_edit_gettotaltext, arginfo_hidemaru_edit_gettotaltext)
	ZEND_FE(hidemaru_edit_getselectedtext, arginfo_hidemaru_edit_getselectedtext)
	ZEND_FE(hidemaru_edit_getlinetext, arginfo_hidemaru_edit_getlinetext)
	ZEND_FE(hidemaru_macro_eval, arginfo_hidemaru_macro_eval)
	ZEND_FE(hidemaru_outputpane_output, arginfo_hidemaru_outputpane_output)
	ZEND_FE(hidemaru_outputpane_setbasedir, arginfo_hidemaru_outputpane_setbasedir)
	ZEND_FE(hidemaru_outputpane_push, arginfo_hidemaru_outputpane_push)
	ZEND_FE(hidemaru_outputpane_pop, arginfo_hidemaru_outputpane_pop)
	ZEND_FE(hidemaru_outputpane_sendmessage, arginfo_hidemaru_outputpane_sendmessage)
	ZEND_FE(hidemaru_outputpane_clear, arginfo_hidemaru_outputpane_clear)
	ZEND_FE(hidemaru_outputpane_getwindowhandle, arginfo_hidemaru_outputpane_getwindowhandle)
	ZEND_FE_END
};
