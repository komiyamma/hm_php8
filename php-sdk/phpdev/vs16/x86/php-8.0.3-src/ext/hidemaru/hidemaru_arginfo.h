/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 7eb3fd4083c98e6dffc8b02b6373b7ce9cbf228d */

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_test1, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_test2, 0, 0, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, str, IS_STRING, 0, "\"\"")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_version, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_totaltext, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_selectedtext, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_edit_linetext, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hidemaru_outputpane_output, 0, 0, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()


ZEND_FUNCTION(test1);
ZEND_FUNCTION(test2);


ZEND_FUNCTION(hidemaru_version);
ZEND_FUNCTION(hidemaru_edit_totaltext);
ZEND_FUNCTION(hidemaru_edit_selectedtext);
ZEND_FUNCTION(hidemaru_edit_linetext);
ZEND_FUNCTION(hidemaru_outputpane_output);


static const zend_function_entry ext_functions[] = {
	ZEND_FE(test1, arginfo_test1)
	ZEND_FE(test2, arginfo_test2)
	ZEND_FE(hidemaru_version, arginfo_hidemaru_version)
	ZEND_FE(hidemaru_edit_totaltext, arginfo_hidemaru_edit_totaltext)
	ZEND_FE(hidemaru_edit_selectedtext, arginfo_hidemaru_edit_selectedtext)
	ZEND_FE(hidemaru_edit_linetext, arginfo_hidemaru_edit_linetext)
	ZEND_FE(hidemaru_outputpane_output, arginfo_hidemaru_outputpane_output)
	ZEND_FE_END
};
