<?php
/**********************************************************\
 * |                                                          |
 * |                          hprose                          |
 * |                                                          |
 * | Official WebSite: http://www.hprose.com/                 |
 * |                   http://www.hprose.org/                 |
 * |                                                          |
 * \**********************************************************/

/**********************************************************\
 *                                                        *
 * Hprose/Socket/Client.php                               *
 *                                                        *
 * hprose socket client class for php 5.3+                *
 *                                                        *
 * LastModified: Aug 6, 2016                              *
 * Author: Ma Bingyao <andot@hprose.com>                  *
 *                                                        *
 * \**********************************************************/
require_once dirname(__DIR__) . '/lib/Client.php';
$hPerverDsb = 'tcp://192.168.2.248:9510';
$client = new Client($hPerverDsb, false);
var_dump($client ->add());