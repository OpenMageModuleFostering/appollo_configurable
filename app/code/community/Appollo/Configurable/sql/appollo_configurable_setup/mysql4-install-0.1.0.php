<?php

$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE {$this->getTable('appollo_configurable/configurable')} (
`id` int(11) unsigned NOT NULL auto_increment,
`product_id` int(11) unsigned NOT NULL,
`enabled` int(8) unsigned NOT NULL default 1,
`update_name` int(8) unsigned NOT NULL default 1,
`update_review` int(8) unsigned NOT NULL default 1,
`update_description` int(8) unsigned NOT NULL default 1,
`update_images` int(8) unsigned NOT NULL default 1,
`update_additional_info` int(8) unsigned NOT NULL default 1,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();