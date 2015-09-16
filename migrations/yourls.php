<?php
/*
# Copyright (C) 2013-2015 Chaoyi Zha
# Polr is an open-source project licensed under the GPL.
# The above copyright notice and the following license are applicable to
# the entire project, unless explicitly defined otherwise.
# http://github.com/cydrobolt/polr
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or (at
# your option) any later version.
# See http://www.gnu.org/copyleft/gpl.html  for the full text of the
# license.
#


# YOURLS -> Polr Migration
*/

require_once 'lib-core.php';

function perform_migration($yourls_host, $yourls_user, $yourls_passwd, $yourls_db)
    $yourls_mysqli = new mysqli($yourls_host, $yourls_user, $yourls_passwd, $yourls_db);

    $qp = "SELECT (`keyword`, `url`, `ip`, `clicks`, `timestamp`) FROM `URL`";
    $stmt = $yourls_mysqli->prepare($qp);
    $stmt->bind_param('s', $wval);
    $stmt->execute();
    $result = $stmt->get_result();

    $yourls_rows = $yourls_mysqli->fetch_array($result, MYSQLI_NUM);
    foreach ($yourls_rows as $ylsr) {
        // for each YOURLS row, insert the row into the Polr database
        $qpi = "INSERT INTO `redirinfo` (`baseval`, `rurl`, `ip`, `clicks`, `date`) VALUES (?, ?, ?, ?, ?)";
        $stmti = $mysqli->prepare($qpi);
        $stmt->bind_param('sssss', $ylsr['keyword'], $ylsr['url'], $ylsr['clicks'], $ylsr['timestamp']);
        $stmti->execute();
    }
