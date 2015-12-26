<?php

use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

/**
 * phpMyFAQ Elasticsearch information.
 *
 * PHP Version 5.5
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 *
 * @category  phpMyFAQ
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2015 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      http://www.phpmyfaq.de
 * @since     2015-12-25
 */
if (!defined('IS_VALID_PHPMYFAQ')) {
    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && strtoupper($_SERVER['HTTPS']) === 'ON') {
        $protocol = 'https';
    }
    header('Location: '.$protocol.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

if ($user->perm->checkRight($user->getUserId(), 'editconfig')) {

    $esSearch = new PMF_Search_Elasticsearch($faqConfig);

    try {
        $esConfig = $faqConfig->getElasticsearchConfig();
        $esInformation = $faqConfig->getElasticsearch()->cat()->master([$esConfig['index']]);
    } catch (NoNodesAvailableException $e) {
        $esInformation = $e->getMessage();
    }

    ?>
    <header class="row">
        <div class="col-lg-12">
            <h2 class="page-header">
                <i class="fa fa-wrench fa-fw"></i> <?php echo $PMF_LANG['ad_menu_elasticsearch'] ?>
            </h2>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-12">

            <pre><?php var_dump($esSearch->getMapping()); ?></pre>
            
        </div>
    </div>
    <?php

} else {
    echo $PMF_LANG['err_NotAuth'];
}
