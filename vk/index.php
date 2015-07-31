<?php

/* V-Card Download and Display for Smartphones, iPhones and Outlook
   Author: Jonas Hess (revier online GmbH & Co. KG)
   Date: 12.03.2013
   Version: 1.0
*/

$method = '';
$vcard  = '';

if (isset($_GET['method'])) $method = $_GET['method'];
if (isset($_GET['vcard'])) $vcard = $_GET['vcard'];

if (strlen($vcard) == 0) exit;
if (!file_exists('files/' . $vcard . '.vcf')) {
    echo 'VCARD Nr. ' . $vcard . ' not found!';
    exit;
}
$vcard_content = file('files/' . $vcard . '.vcf');

//NAME from vCard
$namepos = in_str_in_array('FN;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:', $vcard_content);
$name = quoted_printable_decode($vcard_content[$namepos]);
$name = substr($name, 43);
$name = trim($name, "\x00..\x1F");

//TITLE from vCard
$titlepos = in_str_in_array('TITLE:', $vcard_content);
if ($titlepos !== false) {
    $title = utf8_encode($vcard_content[$titlepos]);
    $title = substr($title, 6);
    $title = trim($title, "\x00..\x1F");
}

//ORG from vCard
$orgpos = in_str_in_array('ORG;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:', $vcard_content);
$org = quoted_printable_decode($vcard_content[$orgpos]);
$org = substr($org, 44);
$org = trim($org, "\x00..\x1F");

//EMAIL from vCard
$mailpos = in_str_in_array('EMAIL;type=INTERNET;type=WORK;type=pref:', $vcard_content);
$mail = $vcard_content[$mailpos];
$mail = substr($mail, 40);
$mail = trim($mail, "\x00..\x1F");

//WWW from vCard
$wwwpos = in_str_in_array('URL;WORK:', $vcard_content);
if ($wwwpos !== false) {
    $www = $vcard_content[$wwwpos];
    $www = substr($www, 9);
    $www = trim($www, "\x00..\x1F");
}

//FAX from vCard
$faxpos = in_str_in_array('TEL;WORK;FAX:', $vcard_content);
$fax = $vcard_content[$faxpos];
$fax = substr($fax, 13);
$fax = trim($fax, "\x00..\x1F");

//TEL from vCard
$telpos = in_str_in_array('TEL;TYPE=voice,work:', $vcard_content);
$tel = $vcard_content[$telpos];
$tel = substr($tel, 20);
$tel = trim($tel, "\x00..\x1F");
$telS = str_replace(' ', '', $tel);

//MOBIL from vCard
$mobilpos = in_str_in_array('TEL;CELL;VOICE:', $vcard_content);
if ($mobilpos !== false) {
    $mobil = utf8_encode($vcard_content[$mobilpos]);
    $mobil = substr($mobil, 15);
    $mobil = trim($mobil, "\x00..\x1F");
}

//ADR from vCard
$addrpos = in_str_in_array('ADR;TYPE=intl,work,postal,parcel:', $vcard_content);
$addr = utf8_encode($vcard_content[$addrpos]);
$addr = substr($addr, 33);
$addr = trim($addr, "\x00..\x1F");
$arr_addr = explode(';', $addr);

function in_str_in_array($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        if (strpos($value, $needle) > 0 || strpos($value, $needle) === 0) {
            return $key;
        }
    }
    return false;
}

switch ($method) {

    case 'download':
        // RETURN vcard file
        header("Content-type:text/x-vCard;");
        header("Content-Disposition: attachment; filename=" . $vcard . ".vcf");
        readfile('files/' . $vcard . '.vcf');
        break;
    default:
        ?>
        <!DOCTYPE html>
        <html manifest="manifest.php">
        <meta charset="utf-8"/>
        <title>Kontakt</title>
        <meta name="robots" content="noindex">
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <link rel="apple-touch-icon-precomposed" href="touch-icon-iphone.png"/>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="touch-icon-ipad.png"/>
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="touch-icon-iphone4.png"/>
        <link rel="apple-touch-startup-image" href="startup.png">
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css"/>
        <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
        <style type="text/css">
            <!--
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);

            body {
                font-family: "Open Sans", sans-serif;
            }

            th {
                text-align: left;
                vertical-align: top;
            }

            .ui-bar-a {
                border: 1px solid #333;
                background: #303030;
                color: #fff;
                font-weight: bold;
                text-shadow: 0 -1px 1px #000;
                background-image: -webkit-gradient(linear, left top, left bottom, from(#e3001b), to(#000000));
                background-image: -webkit-linear-gradient(#e3001b, #000000);
                background-image: -moz-linear-gradient(#e3001b, #000000);
                background-image: -ms-linear-gradient(#e3001b, #000000);
                background-image: -o-linear-gradient(#e3001b, #000000);
                background-image: linear-gradient(#e3001b, #000000);
            }

            div.logo {
                clear: both;
                float: right;
                font-family: Signika, sans-serif;
                font-size: 5em;
                margin: 0 0.2em 0.2em;
            }

            .ui-body-c, .ui-body-c input, .ui-body-c select, .ui-body-c textarea, .ui-body-c button,
            .ui-bar-a, .ui-bar-a input, .ui-bar-a select, .ui-bar-a textarea, .ui-bar-a button,
            .ui-btn-up-c, .ui-btn-hover-c, .ui-btn-down-c {
                font-family: "Droid Sans", sans-serif;
            }

            a {
                font-weight: normal !important;
                color: #333333 !important;
                text-decoration: none;
            }

            -->
        </style>
        <script type="text/javascript">
            if (top != self)
                top.location = self.location;
        </script>
        </head>

        <body>
        <div data-role="page">
            <div data-role="header">
                <h2><?php echo $name ?></h2>
            </div>
            <div data-role="content">
                <div class="logo"><a href="http://www.domain.de" target="_blank"><img src="logo.jpg" alt="Logo"></a></div>
                <div style="clear: both;">&nbsp;</div>
                <table>
                    <tr>
                        <th>Name:</th>
                        <td><?php echo $name ?></td>
                    </tr>
                    <tr>
                        <th>Firma:</th>
                        <td><?php echo $org ?></td>
                    </tr>
                    <?php if (isset($title)) { ?>
                        <tr>
                            <th>Position:</th>
                            <td><?php echo $title ?>&nbsp;</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Strasse:</th>
                        <td><?php echo $arr_addr[2] ?></td>
                    </tr>
                    <tr>
                        <th>Ort:</th>
                        <td><?php echo $arr_addr[5] ?> <?php echo $arr_addr[3] ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Tel:</th>
                        <td><?php echo $tel ?></td>
                    </tr>
                    <tr>
                        <th>Fax:</th>
                        <td><?php echo $fax ?></td>
                    </tr>
                    <?php if (isset($mobil)) { ?>
                        <tr>
                            <th>Mobil:</th>
                            <td><?php echo $mobil ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Mail:</th>
                        <td><?php echo '<a href="mailto:' . $mail . '">' . $mail . '</a>' ?></td>
                    </tr>
                    <?php if (isset($www)) { ?>
                        <tr>
                            <th>www:</th>
                            <td><?php echo '<a target="_blank" href="' . $www . '">' . ltrim($www, "http://") . '</a>' ?></td>
                        </tr>
                    <?php } ?>
                </table>
                <ul data-role="listview" data-inset="true">
                    <li data-icon="false"><a rel="external" href="?method=download&vcard=<?php echo $vcard ?>">Download vCard-Datei (vcf)</a></li>
                    <li data-icon="false"><a rel="external" href="tel:<?php echo $telS ?>">Anrufen</a></li>
                    <li data-icon="false"><a rel="external" href="mailto:<?php echo $mail ?>">Email</a></li>
                </ul>
            </div>
            <div data-role="footer">
                <h2></h2>
            </div>
        </div>
        </body>
        </html>
    <?php

}
?>