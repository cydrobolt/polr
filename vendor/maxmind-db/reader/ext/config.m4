PHP_ARG_WITH(maxminddb,
    [Whether to enable the MaxMind DB Reader extension],
    [  --with-maxminddb      Enable MaxMind DB Reader extension support])

PHP_ARG_ENABLE(maxminddb-debug, for MaxMind DB debug support,
    [ --enable-maxminddb-debug    Enable enable MaxMind DB deubg support], no, no)

if test $PHP_MAXMINDDB != "no"; then
    PHP_CHECK_LIBRARY(maxminddb, MMDB_open)

    if test $PHP_MAXMINDDB_DEBUG != "no"; then
        CFLAGS="$CFLAGS -Wall -Wextra -Wno-unused-parameter -Wno-missing-field-initializers -Werror"
    fi

    PHP_ADD_LIBRARY(maxminddb, 1, MAXMINDDB_SHARED_LIBADD)
    PHP_SUBST(MAXMINDDB_SHARED_LIBADD)

    PHP_NEW_EXTENSION(maxminddb, maxminddb.c, $ext_shared)
fi
