<?php
//POLR Redirector CORE
require_once('req.php');
require_once('dnsbl.php');
$dnsbl = new dnsbl();
if(is_string($_GET['u'])) {
    $val = $mysqli->real_escape_string($_GET['u']);
}
else {
    echo "Sorry. You didn't enter a string.";
    die();
}
$query = "SELECT rurl FROM redirinfo WHERE baseval='{$val}'";
$result = $mysqli->query($query) or showerror();

$row = mysqli_fetch_assoc($result);

if(!$row['rurl']&&$_GET['u']!='malwaretest'&&$_GET['u']!='phishingtest') {
    header("Location: 404.php",true,302);
    }
else {
    $isbl = $dnsbl->isbl($row['rurl']);
    //Testing Module Start
    if($_GET['u']==='malwaretest') {
        $isbl='malware';
        $row['rurl'] = 'http://polr-gsb-test-page-not-real-site.polr.cf/slash';
    }
    if($_GET['u']==='phishingtest') {
        $isbl='phishing';
        $row['rurl'] = 'http://polr-gsb-test-page-not-real-site.polr.cf';
    }
    //Testing module end
    if($isbl==='malware'||$isbl==='phishing') {
        $np = 'Polr works with Google works to provide the most accurate and up-to-date phishing and malware information. However, it cannot guarantee that its information is comprehensive and error-free: some risky sites may not be identified, and some safe sites may be identified in error.';
        if($isbl==='malware') {
            $warning = 'Visiting this web site may harm your computer. This webiste appears to contain malicious code that could be downloaded to your computer without your consent. You can learn more about harmful web content including viruses and other malicious code and how to protect your computer at <a href="//stopbadware.org">StopBadware.org</a>. ';
        }
        elseif($isbl==='phishing') {
            $warning = 'Suspected phishing page. This website may be a forgery or imitation of another website, designed to trick users into sharing personal or financial information. Entering any personal information on this page may result in identity theft or other abuse. You can find out more about phishing from <a href="//antiphishing.org">www.antiphishing.org</a>.';
        }
        
        require_once 'header.php';
        echo '<h1 style="color:red">Warning</h1>
                    <br><h3>You are trying to reach: <b style="color:orange">  '.$row['rurl'].
                '</b> <br>'.$warning.' </h3>'.
                ' <br><p>Advisory provided by Google. <br>'.$np.' <br><a href="http://code.google.com/apis/safebrowsing/safebrowsing_faq.html#whyAdvisory">Learn more</a><br>
                    <br><a href="http://google.com" class="btn btn-success btn-lg">Take me to safety</a><br><br><a href="'.$row['rurl'].'" class="btn btn-danger btn-sm">Proceed anyways (at your own risk, not recommended)</a>
                </div><div><p>&copy; Copyright 2014 Polr</p>
        </div>
    </body>
</html>
';
        die();
    }
    if(strtolower($row['rurl'])=="disabled") {
        require_once 'header.php';
        echo "<h2>The link you are trying to reach has been disabled because it infriges our <a href='tos.php'>Terms Of Service</a></h2><br>"
        . "Sorry for the inconvienience.";
        require_once 'footer.php';
    }

    
    header("Location: {$row['rurl']}",true,301);
	$oldclicks = sqlfetch("redirinfo","clicks","baseval",$val);
	$newclicks = $oldclicks+1;
	sqlrun("UPDATE redirinfo SET clicks='{$newclicks}' WHERE baseval='{$val}'");
}


