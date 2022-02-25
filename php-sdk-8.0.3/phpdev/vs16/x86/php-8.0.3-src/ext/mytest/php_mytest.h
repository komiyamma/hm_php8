/* mytest extension for PHP */

#ifndef PHP_MYTEST_H
# define PHP_MYTEST_H

extern zend_module_entry mytest_module_entry;
# define phpext_mytest_ptr &mytest_module_entry

# define PHP_MYTEST_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_MYTEST)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_MYTEST_H */
