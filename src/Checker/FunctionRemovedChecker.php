<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Checker;

use Joli\Php7Checker\Error\Error;
use PhpParser\Node;

/**
 * PHP 7 removed a bunch of functions that you can no longer use.
 *
 * Most removed functions are a consequence of a deprecated extension removal.
 * Some are simply removed in favor of an alternative function.
 *
 * This checker cannot detects class method calls because it's not that easy to
 * determine the class of a variable.
 */
class FunctionRemovedChecker extends AbstractChecker
{
    /** @var string[string]  */
    private static $removedFunctions = array(
        'call_user_method' => 'Use the call_user_func() function instead',
        'call_user_method_array' => 'Use the call_user_func_array() function instead',

        'mcrypt_generic_end' => 'Use the mcrypt_generic_deinit() function instead',

        'mcrypt_ecb' => 'Use the mcrypt_decrypt() function with a MCRYPT_MODE_* constant instead',
        'mcrypt_cbc' => 'Use the mcrypt_decrypt() function with a MCRYPT_MODE_* constant instead',
        'mcrypt_cfb' => 'Use the mcrypt_decrypt() function with a MCRYPT_MODE_* constant instead',
        'mcrypt_ofb' => 'Use the mcrypt_decrypt() function with a MCRYPT_MODE_* constant instead',

        'datefmt_set_timezone_id' => 'Use the datefmt_set_timezone() function instead',
        //'IntlDateFormatter::setTimeZoneID' => 'Use the IntlDateFormatter::setTimeZone() function instead',

        'set_magic_quotes_runtime' => '',
        'magic_quotes_runtime' => '',

        'set_socket_blocking' => 'Use the stream_set_blocking() function instead',

        'dl' => 'The function was removed on fpm-fcgi',

        'imagepsbbox' => 'The T1Lib support were removed',
        'imagepsencodefont' => 'The T1Lib support were removed',
        'imagepsextendedfont' => 'The T1Lib support were removed',
        'imagepsfreefont' => 'The T1Lib support were removed',
        'imagepsloadfont' => 'The T1Lib support were removed',
        'imagepsslantfont' => 'The T1Lib support were removed',
        'imagepstext' => 'The T1Lib support were removed',

        'ereg_replace' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',
        'ereg' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',
        'eregi_replace' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',
        'eregi' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',
        'split' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',
        'spliti' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',
        'sql_regcase' => 'The ereg extension were removed, you should use the PCRE extension and preg_*() functions instead',

        'mysql_affected_rows' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_client_encoding' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_close' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_connect' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_create_db' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_data_seek' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_db_name' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_db_query' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_drop_db' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_errno' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_error' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_escape_string' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_fetch_array' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_fetch_assoc' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_fetch_field' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_fetch_lengths' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_fetch_object' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_fetch_row' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_field_flags' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_field_len' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_field_name' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_field_seek' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_field_table' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_field_type' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_free_result' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_get_client_info' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_get_host_info' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_get_proto_info' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_get_server_info' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_info' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_insert_id' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_list_dbs' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_list_fields' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_list_processes' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_list_tables' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_num_fields' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_num_rows' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_pconnect' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_ping' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_query' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_real_escape_string' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_result' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_select_db' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_set_charset' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_stat' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_tablename' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_thread_id' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',
        'mysql_unbuffered_query' => 'The mysql extension were removed, you should use the mysqli extension with its mysqli_*() function or the PDO extension',

        'mssql_bind' => 'The mssql extension were removed',
        'mssql_close' => 'The mssql extension were removed',
        'mssql_connect' => 'The mssql extension were removed',
        'mssql_data_seek' => 'The mssql extension were removed',
        'mssql_execute' => 'The mssql extension were removed',
        'mssql_fetch_array' => 'The mssql extension were removed',
        'mssql_fetch_assoc' => 'The mssql extension were removed',
        'mssql_fetch_batch' => 'The mssql extension were removed',
        'mssql_fetch_field' => 'The mssql extension were removed',
        'mssql_fetch_object' => 'The mssql extension were removed',
        'mssql_fetch_row' => 'The mssql extension were removed',
        'mssql_field_length' => 'The mssql extension were removed',
        'mssql_field_name' => 'The mssql extension were removed',
        'mssql_field_seek' => 'The mssql extension were removed',
        'mssql_field_type' => 'The mssql extension were removed',
        'mssql_free_result' => 'The mssql extension were removed',
        'mssql_free_statement' => 'The mssql extension were removed',
        'mssql_get_last_message' => 'The mssql extension were removed',
        'mssql_guid_string' => 'The mssql extension were removed',
        'mssql_init' => 'The mssql extension were removed',
        'mssql_min_error_severity' => 'The mssql extension were removed',
        'mssql_min_message_severity' => 'The mssql extension were removed',
        'mssql_next_result' => 'The mssql extension were removed',
        'mssql_num_fields' => 'The mssql extension were removed',
        'mssql_num_rows' => 'The mssql extension were removed',
        'mssql_pconnect' => 'The mssql extension were removed',
        'mssql_query' => 'The mssql extension were removed',
        'mssql_result' => 'The mssql extension were removed',
        'mssql_rows_affected' => 'The mssql extension were removed',
        'mssql_select_db' => 'The mssql extension were removed',
    );

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $name = strtolower($node->name);
            if (array_key_exists($name, self::$removedFunctions)) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    sprintf('Function %s() was removed', $name),
                    self::$removedFunctions[$name]
                ));
            }
        }
    }
}
