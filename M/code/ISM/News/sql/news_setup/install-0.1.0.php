<?php

$installer = $this;

$installer->startSetup();
$installer->run("
-- DROP TABLE IF NOT EXISTS {$this->getTable('ism_news')};
CREATE TABLE {$this->getTable('ism_news')} (
`news_id` int(11) unsigned NOT NULL auto_increment,
`title` varchar(255) NOT NULL default '',
`content` text NOT NULL default '',
`announce` text NOT NULL default '',
`publish_date` datetime NULL,
`published` bool NOT NULL default 0,
PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();