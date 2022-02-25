/* hidemaru extension for PHP */

#ifndef PHP_HIDEMARU_H
# define PHP_HIDEMARU_H

extern zend_module_entry hidemaru_module_entry;
# define phpext_hidemaru_ptr &hidemaru_module_entry

# define PHP_HIDEMARU_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_HIDEMARU)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_HIDEMARU_H */
