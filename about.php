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
    <br>Polr is licensed under the MIT License.</p>
<div style="font-size: 50%; padding-top: 40px">
    The MIT License (MIT)
    <br>
    Copyright (c) 2014 Polr Project & <a href='//cydrobolt.com'>Chaoyi Zha</a>
    <br>
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    <br>
    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    <br>
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</div>
<?php
require_once 'footerpage.php';
?>