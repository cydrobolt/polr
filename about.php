<?php
@(include('config.php')) or header('Location:setup.php');
require_once 'headerpage.php';
require_once('version.php');
?>

<h1>About Polr</h1>
<br>
<dl>Build Information
    <dt>Version: <?php require_once('req.php');
echo $version; ?>
    <dt>Release date: <?php echo $reldate; ?>
    <dt>App Build : <?php echo $wsn . " by " . $wsa . " on " . $wsb ?>
    <dt>
</dl>
<br><p>Polr is an open source, minimalist link shortening platform. Learn more at <a href='https://github.com/Cydrobolt/polr'>our Github page</a>, our <a href="//project.polr.cf">project site</a>, <a href="//polr.cf/about">or our about page</a>.
    <br>Polr is licensed under the GNU GPL License.</p>
<div style="font-size: 70%; padding-top: 40px">
    The GNU General Public License v3
    <br>
    Copyright (C) 2014 Chaoyi Zha, the Polr Project
    <br />
    This program is free software: you can redistribute it and/or modify<br />
    it under the terms of the GNU General Public License as published by<br />
    the Free Software Foundation, either version 3 of the License, or<br />
    (at your option) any later version.<br />
    <br />
    This program is distributed in the hope that it will be useful,<br />
    but WITHOUT ANY WARRANTY; without even the implied warranty of<br />
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br />
    GNU General Public License for more details.<br />
    <br />
    You should have received a copy of the GNU General Public License<br />
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
</div>
<?php
require_once 'footerpage.php';
?>
