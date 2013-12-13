<?php
// define functions
function p_r($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

function d_r($var) {
    $backTrace = current(debug_backtrace());
    $baseFilename = basename($backTrace['file']);
    echo "
    <table border=\"1\" cellspacing=\"0\">
        <tbody>
            <tr>
                <th align=\"center\" bgcolor=\"#e9b96e\"><i>File</i></th>
                <th align=\"center\" bgcolor=\"#e9b96e\"><i>Line</i></th>
                <th align=\"center\" bgcolor=\"#e9b96e\"><i>Dump</i></th>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#eeeeec\" valign=\"top\">
                    <pre><a href=\"#!\" title=\"{$backTrace['file']}\" alt=\"{$backTrace['file']}\">{$baseFilename}</a></pre>
                </td>
                <td align=\"center\" bgcolor=\"#eeeeec\" valign=\"top\">
                    <pre>{$backTrace['line']}</pre>
                </td>
                <td align=\"left\" bgcolor=\"#eeeeec\" valign=\"top\">
                    <pre>";
    var_dump($var);
    echo "</pre>
                </td>
            </tr>
        </tbody>
    </table>
    ";
}

function d_e($var) {
    $backTrace = current(debug_backtrace());
    $baseFilename = basename($backTrace['file']);
    echo "
    <table border=\"1\" cellspacing=\"0\">
        <tbody>
            <tr>
                <th align=\"center\" bgcolor=\"#e9b96e\"><i>File</i></th>
                <th align=\"center\" bgcolor=\"#e9b96e\"><i>Line</i></th>
                <th align=\"center\" bgcolor=\"#e9b96e\"><i>Dump</i></th>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#eeeeec\" valign=\"top\">
                    <pre><a href=\"#!\" title=\"{$backTrace['file']}\" alt=\"{$backTrace['file']}\">{$baseFilename}</a></pre>
                </td>
                <td align=\"center\" bgcolor=\"#eeeeec\" valign=\"top\">
                    <pre>{$backTrace['line']}</pre>
                </td>
                <td align=\"left\" bgcolor=\"#eeeeec\" valign=\"top\">
                    <pre>";
    var_dump($var);
    echo "</pre>
                </td>
            </tr>
        </tbody>
    </table>
    ";
    exit;
}
