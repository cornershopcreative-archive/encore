-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: demo_wordpress
-- ------------------------------------------------------
-- Server version	5.5.47-0+deb8u1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wpdb_bad_behavior`
--

DROP TABLE IF EXISTS `wpdb_bad_behavior`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_bad_behavior` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `request_method` text NOT NULL,
  `request_uri` text NOT NULL,
  `server_protocol` text NOT NULL,
  `http_headers` text NOT NULL,
  `user_agent` text NOT NULL,
  `request_entity` text NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`(15)),
  KEY `user_agent` (`user_agent`(10))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_bad_behavior`
--

LOCK TABLES `wpdb_bad_behavior` WRITE;
/*!40000 ALTER TABLE `wpdb_bad_behavior` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_bad_behavior` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_commentmeta`
--

DROP TABLE IF EXISTS `wpdb_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_commentmeta`
--

LOCK TABLES `wpdb_commentmeta` WRITE;
/*!40000 ALTER TABLE `wpdb_commentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_commentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_comments`
--

DROP TABLE IF EXISTS `wpdb_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_comments`
--

LOCK TABLES `wpdb_comments` WRITE;
/*!40000 ALTER TABLE `wpdb_comments` DISABLE KEYS */;
INSERT INTO `wpdb_comments` VALUES (1,1,'Mr WordPress','','http://wordpress.org/','','2012-08-28 15:52:15','2012-08-28 15:52:15','Hi, this is a comment.<br />To delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.',0,'1','','',0,0);
/*!40000 ALTER TABLE `wpdb_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_iwp_backup_status`
--

DROP TABLE IF EXISTS `wpdb_iwp_backup_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_iwp_backup_status` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `historyID` int(11) NOT NULL,
  `taskName` varchar(255) NOT NULL,
  `action` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `finalStatus` varchar(50) DEFAULT NULL,
  `statusMsg` varchar(255) NOT NULL,
  `requestParams` text NOT NULL,
  `responseParams` longtext,
  `taskResults` text,
  `startTime` int(11) DEFAULT NULL,
  `endTime` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_iwp_backup_status`
--

LOCK TABLES `wpdb_iwp_backup_status` WRITE;
/*!40000 ALTER TABLE `wpdb_iwp_backup_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_iwp_backup_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_links`
--

DROP TABLE IF EXISTS `wpdb_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_links`
--

LOCK TABLES `wpdb_links` WRITE;
/*!40000 ALTER TABLE `wpdb_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_mk_components`
--

DROP TABLE IF EXISTS `wpdb_mk_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_mk_components` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `wpdb_mk_components_type` (`type`),
  KEY `wpdb_mk_components_status` (`status`),
  KEY `wpdb_mk_components_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_mk_components`
--

LOCK TABLES `wpdb_mk_components` WRITE;
/*!40000 ALTER TABLE `wpdb_mk_components` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_mk_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_options`
--

DROP TABLE IF EXISTS `wpdb_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=12727 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_options`
--

LOCK TABLES `wpdb_options` WRITE;
/*!40000 ALTER TABLE `wpdb_options` DISABLE KEYS */;
INSERT INTO `wpdb_options` VALUES (1,'siteurl','http://wordpress.bbyrne.cshp.co/cms','yes'),(2,'blogname','WordPress Dev Site','yes'),(3,'blogdescription','','yes'),(4,'users_can_register','0','yes'),(5,'admin_email','bbyrne@cornershopcreative.com','yes'),(6,'start_of_week','1','yes'),(7,'use_balanceTags','0','yes'),(8,'use_smilies','1','yes'),(9,'require_name_email','','yes'),(10,'comments_notify','1','yes'),(11,'posts_per_rss','10','yes'),(12,'rss_use_excerpt','0','yes'),(13,'mailserver_url','mail.example.com','yes'),(14,'mailserver_login','login@example.com','yes'),(15,'mailserver_pass','password','yes'),(16,'mailserver_port','110','yes'),(17,'default_category','1','yes'),(18,'default_comment_status','closed','yes'),(19,'default_ping_status','open','yes'),(20,'default_pingback_flag','','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(29,'comment_moderation','','yes'),(30,'moderation_notify','1','yes'),(31,'permalink_structure','/%postname%/','yes'),(33,'hack_file','0','yes'),(34,'blog_charset','UTF-8','yes'),(35,'moderation_keys','','no'),(36,'active_plugins','a:5:{i:0;s:34:\"advanced-custom-fields-pro/acf.php\";i:1;s:23:\"debug-bar/debug-bar.php\";i:2;s:28:\"kint-debugger/Kint.class.php\";i:3;s:47:\"show-current-template/show-current-template.php\";i:4;s:49:\"toolbar-publish-button/toolbar-publish-button.php\";}','yes'),(37,'home','http://wordpress.bbyrne.cshp.co/','yes'),(38,'category_base','','yes'),(39,'ping_sites','http://rpc.pingomatic.com/','yes'),(41,'comment_max_links','2','yes'),(42,'gmt_offset','','yes'),(43,'default_email_category','1','yes'),(44,'recently_edited','a:2:{i:0;s:93:\"/home/bbyrne/sites/wordpress/cms/assets/plugins/ithemes-security-pro/ithemes-security-pro.php\";i:1;s:0:\"\";}','no'),(45,'template','crate','yes'),(46,'stylesheet','crate-child','yes'),(47,'comment_whitelist','1','yes'),(48,'blacklist_keys','','no'),(49,'comment_registration','1','yes'),(50,'html_type','text/html','yes'),(51,'use_trackback','0','yes'),(52,'default_role','subscriber','yes'),(53,'db_version','36686','yes'),(54,'uploads_use_yearmonth_folders','1','yes'),(55,'upload_path','','yes'),(56,'blog_public','0','yes'),(57,'default_link_category','2','yes'),(58,'show_on_front','page','yes'),(59,'tag_base','','yes'),(60,'show_avatars','1','yes'),(61,'avatar_rating','G','yes'),(62,'upload_url_path','','yes'),(63,'thumbnail_size_w','140','yes'),(64,'thumbnail_size_h','140','yes'),(65,'thumbnail_crop','1','yes'),(66,'medium_size_w','336','yes'),(67,'medium_size_h','9999','yes'),(68,'avatar_default','mystery','yes'),(71,'large_size_w','771','yes'),(72,'large_size_h','9999','yes'),(73,'image_default_link_type','file','yes'),(74,'image_default_size','','yes'),(75,'image_default_align','','yes'),(76,'close_comments_for_old_posts','','yes'),(77,'close_comments_days_old','14','yes'),(78,'thread_comments','1','yes'),(79,'thread_comments_depth','5','yes'),(80,'page_comments','','yes'),(81,'comments_per_page','50','yes'),(82,'default_comments_page','newest','yes'),(83,'comment_order','asc','yes'),(84,'sticky_posts','a:0:{}','yes'),(85,'widget_categories','a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(86,'widget_text','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:4:\"text\";s:0:\"\";s:6:\"filter\";b:0;}s:12:\"_multiwidget\";i:1;}','yes'),(87,'widget_rss','a:2:{i:2;a:8:{s:5:\"title\";s:8:\"My Title\";s:3:\"url\";s:21:\"http://100r.org/feed/\";s:4:\"link\";s:16:\"http://100r.org/\";s:5:\"items\";i:20;s:5:\"error\";b:0;s:12:\"show_summary\";i:0;s:11:\"show_author\";i:1;s:9:\"show_date\";i:1;}s:12:\"_multiwidget\";i:1;}','yes'),(88,'uninstall_plugins','a:3:{s:41:\"better-wp-security/better-wp-security.php\";a:2:{i:0;s:11:\"ITSEC_Setup\";i:1;s:12:\"on_uninstall\";}s:45:\"ithemes-security-pro/ithemes-security-pro.php\";a:2:{i:0;s:10:\"ITSEC_Core\";i:1;s:12:\"on_uninstall\";}s:27:\"wp-super-cache/wp-cache.php\";s:23:\"wpsupercache_deactivate\";}','no'),(89,'timezone_string','America/Detroit','yes'),(91,'embed_size_w','771','yes'),(92,'embed_size_h','9999','yes'),(93,'page_for_posts','47','yes'),(94,'page_on_front','45','yes'),(95,'default_post_format','0','yes'),(96,'initial_db_version','21115','yes'),(97,'wpdb_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:67:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:16:\"set_posts_status\";b:1;s:19:\"manage_capabilities\";b:1;s:16:\"ef_view_calendar\";b:1;s:23:\"edit_post_subscriptions\";b:1;s:20:\"ef_view_story_budget\";b:1;s:15:\"edit_usergroups\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:39:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:16:\"set_posts_status\";b:1;s:16:\"ef_view_calendar\";b:1;s:23:\"edit_post_subscriptions\";b:1;s:20:\"ef_view_story_budget\";b:1;s:10:\"rate_video\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:19:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:16:\"set_posts_status\";b:1;s:18:\"edit_theme_options\";b:1;s:11:\"edit_themes\";b:1;s:14:\"install_themes\";b:1;s:13:\"switch_themes\";b:1;s:13:\"update_themes\";b:1;s:16:\"ef_view_calendar\";b:1;s:23:\"edit_post_subscriptions\";b:1;s:20:\"ef_view_story_budget\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:7:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:16:\"ef_view_calendar\";b:1;s:20:\"ef_view_story_budget\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),(98,'widget_search','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(99,'widget_recent-posts','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(100,'widget_recent-comments','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(101,'widget_archives','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(102,'widget_meta','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(103,'sidebars_widgets','a:6:{s:19:\"wp_inactive_widgets\";a:2:{i:0;s:6:\"text-2\";i:1;s:10:\"nav_menu-2\";}s:19:\"primary-widget-area\";a:7:{i:0;s:5:\"rss-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";i:6;s:6:\"meta-2\";}s:21:\"secondary-widget-area\";a:1:{i:0;s:11:\"tag_cloud-2\";}s:24:\"first-footer-widget-area\";a:0:{}s:25:\"second-footer-widget-area\";a:0:{}s:13:\"array_version\";i:3;}','yes'),(104,'cron','a:6:{i:1467042746;a:1:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1467047072;a:1:{s:16:\"itsec_purge_logs\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1467050286;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1467061740;a:1:{s:16:\"backupbuddy_cron\";a:1:{s:32:\"b2d6f4df5dfc2b203ff6fc9b49103fdc\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:2:{i:0;s:12:\"housekeeping\";i:1;a:0:{}}s:8:\"interval\";i:86400;}}}i:1467069287;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}s:7:\"version\";i:2;}','yes'),(1053,'link_manager_enabled','0','yes'),(111,'dashboard_widget_options','a:4:{s:25:\"dashboard_recent_comments\";a:1:{s:5:\"items\";i:5;}s:24:\"dashboard_incoming_links\";a:5:{s:4:\"home\";s:31:\"http://wordpress.bbyrne.cshp.co\";s:4:\"link\";s:107:\"http://blogsearch.google.com/blogsearch?scoring=d&partner=wordpress&q=link:http://wordpress.bbyrne.cshp.co/\";s:3:\"url\";s:144:\"http://blogsearch.google.com/blogsearch_feeds?scoring=d&ie=utf-8&num=10&output=rss&partner=wordpress&q=link:http://wordpress.bbyrne.cshp.co/cms/\";s:5:\"items\";i:10;s:9:\"show_date\";b:0;}s:17:\"dashboard_primary\";a:7:{s:4:\"link\";s:26:\"http://wordpress.org/news/\";s:3:\"url\";s:31:\"http://wordpress.org/news/feed/\";s:5:\"title\";s:14:\"WordPress Blog\";s:5:\"items\";i:2;s:12:\"show_summary\";i:1;s:11:\"show_author\";i:0;s:9:\"show_date\";i:1;}s:19:\"dashboard_secondary\";a:7:{s:4:\"link\";s:28:\"http://planet.wordpress.org/\";s:3:\"url\";s:33:\"http://planet.wordpress.org/feed/\";s:5:\"title\";s:20:\"Other WordPress News\";s:5:\"items\";i:5;s:12:\"show_summary\";i:0;s:11:\"show_author\";i:0;s:9:\"show_date\";i:0;}}','yes'),(1501,'category_children','a:0:{}','yes'),(2304,'key_ga_show_ad','1','yes'),(1447,'prominence_children','a:0:{}','yes'),(373,'db_upgraded','','yes'),(148,'recently_activated','a:0:{}','yes'),(3221,'ithemes-updater-cache','a:11:{s:18:\"timeout-multiplier\";i:1;s:10:\"expiration\";i:1460557303;s:9:\"timestamp\";i:1460553703;s:8:\"packages\";a:0:{}s:14:\"update_plugins\";a:0:{}s:13:\"update_themes\";a:0:{}s:12:\"use_ca_patch\";b:0;s:7:\"use_ssl\";b:1;s:14:\"quick_releases\";b:0;s:12:\"server-cache\";i:30;s:18:\"timeout-mulitplier\";i:1;}','yes'),(747,'nav_menu_options','a:2:{i:0;b:0;s:8:\"auto_add\";a:0:{}}','yes'),(416,'current_theme','Crate Child','yes'),(417,'theme_mods_crate','a:9:{i:0;b:0;s:16:\"header_textcolor\";s:6:\"000000\";s:16:\"background_color\";s:6:\"ffffff\";s:16:\"background_image\";s:0:\"\";s:17:\"background_repeat\";s:6:\"repeat\";s:21:\"background_position_x\";s:4:\"left\";s:21:\"background_attachment\";s:5:\"fixed\";s:18:\"nav_menu_locations\";a:2:{s:7:\"primary\";i:3;s:6:\"footer\";i:4;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1405521308;s:4:\"data\";a:5:{s:19:\"wp_inactive_widgets\";a:2:{i:0;s:6:\"text-2\";i:1;s:10:\"nav_menu-2\";}s:19:\"primary-widget-area\";a:7:{i:0;s:5:\"rss-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";i:6;s:6:\"meta-2\";}s:21:\"secondary-widget-area\";a:0:{}s:24:\"first-footer-widget-area\";a:0:{}s:25:\"second-footer-widget-area\";a:0:{}}}}','yes'),(418,'theme_switched','','yes'),(746,'theme_mods_twentyeleven','a:2:{s:18:\"nav_menu_locations\";a:1:{s:7:\"primary\";i:3;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1359420601;s:4:\"data\";a:6:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}s:9:\"sidebar-4\";a:0:{}s:9:\"sidebar-5\";a:0:{}}}}','yes'),(473,'wpsupercache_start','1351265596','yes'),(170,'bad_behavior_settings','a:13:{s:9:\"log_table\";s:17:\"wpdb_bad_behavior\";s:13:\"display_stats\";b:0;s:6:\"strict\";b:0;s:7:\"verbose\";b:0;s:7:\"logging\";b:1;s:10:\"httpbl_key\";s:12:\"aoohbrjiqmhi\";s:13:\"httpbl_threat\";i:25;s:13:\"httpbl_maxage\";i:30;s:13:\"offsite_forms\";b:0;s:9:\"eu_cookie\";b:0;s:13:\"reverse_proxy\";b:0;s:20:\"reverse_proxy_header\";s:15:\"X-Forwarded-For\";s:23:\"reverse_proxy_addresses\";a:0:{}}','yes'),(172,'ossdl_off_cdn_url','http://wordpress.bbyrne.cshp.co/cms','yes'),(173,'ossdl_off_include_dirs','wp-content,wp-includes','yes'),(174,'ossdl_off_exclude','.php','yes'),(175,'ossdl_cname','','yes'),(186,'ga_status','disabled','yes'),(187,'ga_uid','XX-XXXXX-X','yes'),(188,'ga_admin_status','enabled','yes'),(189,'ga_admin_disable','remove','yes'),(190,'ga_admin_role','a:1:{i:0;s:13:\"administrator\";}','yes'),(191,'ga_dashboard_role','a:1:{i:0;s:13:\"administrator\";}','yes'),(192,'ga_adsense','','yes'),(193,'ga_extra','','yes'),(194,'ga_extra_after','','yes'),(195,'ga_event','enabled','yes'),(196,'ga_outbound','enabled','yes'),(197,'ga_outbound_prefix','outgoing','yes'),(198,'ga_downloads','','yes'),(199,'ga_downloads_prefix','download','yes'),(200,'ga_profileid','','yes'),(201,'ga_widgets','enabled','yes'),(202,'ga_google_token','','yes'),(203,'ga_compatibility','off','yes'),(204,'ga_sitespeed','enabled','yes'),(474,'wpsupercache_count','0','yes'),(357,'ga_defaults','yes','yes'),(2816,'theme_mods_twentythirteen','a:2:{i:0;b:0;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1390485359;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:9:{i:0;s:5:\"rss-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";i:6;s:6:\"meta-2\";i:7;s:10:\"nav_menu-2\";i:8;s:6:\"text-2\";}s:9:\"sidebar-2\";a:0:{}}}}','yes'),(2821,'widget_nav_menu','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:8:\"nav_menu\";i:8;}s:12:\"_multiwidget\";i:1;}','yes'),(911,'ga_version','6.4','yes'),(912,'ga_annon','','yes'),(1061,'a8c_developer','a:1:{s:12:\"project_type\";s:11:\"wporg-theme\";}','yes'),(2931,'theme_mods_twentyfourteen','a:2:{i:0;b:0;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1390485893;s:4:\"data\";a:4:{s:19:\"wp_inactive_widgets\";a:2:{i:0;s:6:\"text-2\";i:1;s:10:\"nav_menu-2\";}s:9:\"sidebar-1\";a:7:{i:0;s:5:\"rss-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";i:6;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}}}}','yes'),(2104,'acf_version','5.3.7','yes'),(2317,'gform_enable_noconflict','0','yes'),(5409,'WPLANG','','yes'),(5958,'wordfence_version','5.3.8','yes'),(5959,'wordfenceActivated','0','yes'),(5960,'wf_plugin_act_error','','yes'),(6087,'pp_support_key','a:3:{i:0;s:1:\"1\";i:1;s:32:\"79a577bfa9991bac1ffa0af1c3426c07\";i:2;s:3:\"dev\";}','no'),(3143,'auto_core_update_notified','a:4:{s:4:\"type\";s:6:\"manual\";s:5:\"email\";s:29:\"bbyrne@cornershopcreative.com\";s:7:\"version\";s:5:\"4.5.3\";s:9:\"timestamp\";i:1466604037;}','yes'),(6543,'acf_pro_license','YToyOntzOjM6ImtleSI7czo3MjoiYjNKa1pYSmZhV1E5TXpReE1qSjhkSGx3WlQxa1pYWmxiRzl3WlhKOFpHRjBaVDB5TURFMExUQTNMVEE1SURFM09qUTVPakV3IjtzOjM6InVybCI7czoyODoiaHR0cDovL3dvcmRwcmVzcy5iZW4uY3NocC5jbyI7fQ==','yes'),(5745,'manage-multiple-blogs','a:2:{s:5:\"blogs\";a:0:{}s:12:\"current_blog\";a:1:{s:4:\"type\";N;}}','yes'),(5843,'widget_tag_cloud','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:8:\"taxonomy\";s:8:\"post_tag\";}s:12:\"_multiwidget\";i:1;}','yes'),(4514,'wpseo_permalinks','a:10:{s:15:\"cleanpermalinks\";b:0;s:24:\"cleanpermalink-extravars\";s:0:\"\";s:29:\"cleanpermalink-googlecampaign\";b:0;s:31:\"cleanpermalink-googlesitesearch\";b:0;s:15:\"cleanreplytocom\";b:0;s:10:\"cleanslugs\";b:1;s:15:\"force_transport\";s:7:\"default\";s:18:\"redirectattachment\";b:0;s:17:\"stripcategorybase\";b:0;s:13:\"trailingslash\";b:0;}','yes'),(4515,'wpseo_social','a:14:{s:9:\"fb_admins\";a:0:{}s:6:\"fbapps\";a:0:{}s:12:\"fbconnectkey\";s:32:\"89c8652dbf89252fa704e18b34cf86a9\";s:13:\"facebook_site\";s:0:\"\";s:16:\"og_default_image\";s:0:\"\";s:17:\"og_frontpage_desc\";s:0:\"\";s:18:\"og_frontpage_image\";s:0:\"\";s:9:\"opengraph\";b:1;s:10:\"googleplus\";b:0;s:14:\"plus-publisher\";s:0:\"\";s:7:\"twitter\";b:0;s:12:\"twitter_site\";s:0:\"\";s:17:\"twitter_card_type\";s:7:\"summary\";s:10:\"fbadminapp\";i:0;}','yes'),(4516,'wpseo_rss','a:2:{s:9:\"rssbefore\";s:0:\"\";s:8:\"rssafter\";s:53:\"The post %%POSTLINK%% appeared first on %%BLOGLINK%%.\";}','yes'),(4517,'wpseo_internallinks','a:10:{s:20:\"breadcrumbs-404crumb\";s:25:\"Error 404: Page not found\";s:23:\"breadcrumbs-blog-remove\";b:0;s:20:\"breadcrumbs-boldlast\";b:0;s:25:\"breadcrumbs-archiveprefix\";s:12:\"Archives for\";s:18:\"breadcrumbs-enable\";b:0;s:16:\"breadcrumbs-home\";s:4:\"Home\";s:18:\"breadcrumbs-prefix\";s:0:\"\";s:24:\"breadcrumbs-searchprefix\";s:16:\"You searched for\";s:15:\"breadcrumbs-sep\";s:7:\"&raquo;\";s:23:\"post_types-post-maintax\";i:0;}','yes'),(4518,'wpseo_xml','a:11:{s:22:\"disable_author_sitemap\";b:1;s:16:\"enablexmlsitemap\";b:1;s:16:\"entries-per-page\";i:1000;s:14:\"xml_ping_yahoo\";b:0;s:12:\"xml_ping_ask\";b:0;s:30:\"post_types-post-not_in_sitemap\";b:0;s:30:\"post_types-page-not_in_sitemap\";b:0;s:36:\"post_types-attachment-not_in_sitemap\";b:1;s:34:\"taxonomies-category-not_in_sitemap\";b:0;s:34:\"taxonomies-post_tag-not_in_sitemap\";b:0;s:37:\"taxonomies-post_format-not_in_sitemap\";b:0;}','yes'),(4509,'theme_mods_crate-child','a:2:{i:0;b:0;s:18:\"nav_menu_locations\";a:2:{s:7:\"primary\";i:28;s:6:\"footer\";i:29;}}','yes'),(3222,'wpseo_titles','a:60:{s:10:\"title_test\";i:0;s:17:\"forcerewritetitle\";b:0;s:14:\"hide-feedlinks\";b:0;s:12:\"hide-rsdlink\";b:0;s:14:\"hide-shortlink\";b:0;s:16:\"hide-wlwmanifest\";b:0;s:5:\"noodp\";b:0;s:6:\"noydir\";b:0;s:15:\"usemetakeywords\";b:0;s:16:\"title-home-wpseo\";s:42:\"%%sitename%% %%page%% %%sep%% %%sitedesc%%\";s:18:\"title-author-wpseo\";s:0:\"\";s:19:\"title-archive-wpseo\";s:38:\"%%date%% %%page%% %%sep%% %%sitename%%\";s:18:\"title-search-wpseo\";s:0:\"\";s:15:\"title-404-wpseo\";s:0:\"\";s:19:\"metadesc-home-wpseo\";s:0:\"\";s:21:\"metadesc-author-wpseo\";s:0:\"\";s:22:\"metadesc-archive-wpseo\";s:0:\"\";s:18:\"metakey-home-wpseo\";s:0:\"\";s:20:\"metakey-author-wpseo\";s:0:\"\";s:22:\"noindex-subpages-wpseo\";b:0;s:20:\"noindex-author-wpseo\";b:0;s:21:\"noindex-archive-wpseo\";b:1;s:14:\"disable-author\";b:0;s:12:\"disable-date\";b:0;s:10:\"title-post\";s:39:\"%%title%% %%page%% %%sep%% %%sitename%%\";s:13:\"metadesc-post\";s:0:\"\";s:12:\"metakey-post\";s:0:\"\";s:12:\"noindex-post\";b:0;s:17:\"noauthorship-post\";b:0;s:13:\"showdate-post\";b:0;s:16:\"hideeditbox-post\";b:0;s:10:\"title-page\";s:39:\"%%title%% %%page%% %%sep%% %%sitename%%\";s:13:\"metadesc-page\";s:0:\"\";s:12:\"metakey-page\";s:0:\"\";s:12:\"noindex-page\";b:0;s:17:\"noauthorship-page\";b:1;s:13:\"showdate-page\";b:0;s:16:\"hideeditbox-page\";b:0;s:16:\"title-attachment\";s:39:\"%%title%% %%page%% %%sep%% %%sitename%%\";s:19:\"metadesc-attachment\";s:0:\"\";s:18:\"metakey-attachment\";s:0:\"\";s:18:\"noindex-attachment\";b:0;s:23:\"noauthorship-attachment\";b:1;s:19:\"showdate-attachment\";b:0;s:22:\"hideeditbox-attachment\";b:0;s:18:\"title-tax-category\";s:53:\"%%term_title%% Archives %%page%% %%sep%% %%sitename%%\";s:21:\"metadesc-tax-category\";s:0:\"\";s:20:\"metakey-tax-category\";s:0:\"\";s:24:\"hideeditbox-tax-category\";b:0;s:20:\"noindex-tax-category\";b:0;s:18:\"title-tax-post_tag\";s:53:\"%%term_title%% Archives %%page%% %%sep%% %%sitename%%\";s:21:\"metadesc-tax-post_tag\";s:0:\"\";s:20:\"metakey-tax-post_tag\";s:0:\"\";s:24:\"hideeditbox-tax-post_tag\";b:0;s:20:\"noindex-tax-post_tag\";b:0;s:21:\"title-tax-post_format\";s:53:\"%%term_title%% Archives %%page%% %%sep%% %%sitename%%\";s:24:\"metadesc-tax-post_format\";s:0:\"\";s:23:\"metakey-tax-post_format\";s:0:\"\";s:27:\"hideeditbox-tax-post_format\";b:0;s:23:\"noindex-tax-post_format\";b:1;}','yes'),(3227,'ga_disable_gasites','disabled','yes'),(3228,'ga_analytic_snippet','enabled','yes'),(3229,'ga_admin_disable_DimentionIndex','','yes'),(7547,'iwp_client_replaced_old_hash_backup_files','1','yes'),(6091,'pp_ppcom_update_info','1','no'),(6092,'pp_post_blockage_priority','1','no'),(6093,'pp_define_create_posts_cap','','no'),(6094,'pp_strip_private_caption','1','no'),(6095,'pp_admin_hide_uneditable_posts','1','no'),(6096,'pp_display_user_profile_groups','','no'),(6097,'pp_display_user_profile_roles','','no'),(6098,'pp_advanced_options','','no'),(6099,'pp_enabled_post_types','a:3:{s:4:\"post\";s:1:\"1\";s:4:\"page\";s:1:\"1\";s:10:\"attachment\";s:1:\"0\";}','no'),(6100,'pp_enabled_taxonomies','a:3:{s:8:\"category\";s:1:\"1\";s:8:\"post_tag\";s:1:\"1\";s:11:\"post_format\";s:1:\"0\";}','no'),(5747,'iwp_backup_table_version','1.1','yes'),(3223,'wpseo','a:18:{s:14:\"blocking_files\";a:0:{}s:26:\"ignore_blog_public_warning\";b:0;s:31:\"ignore_meta_description_warning\";b:0;s:20:\"ignore_page_comments\";b:0;s:16:\"ignore_permalink\";b:0;s:11:\"ignore_tour\";b:1;s:15:\"ms_defaults_set\";b:0;s:23:\"theme_description_found\";s:0:\"\";s:21:\"theme_has_description\";b:0;s:19:\"tracking_popup_done\";b:1;s:7:\"version\";s:5:\"1.7.4\";s:11:\"alexaverify\";s:0:\"\";s:20:\"disableadvanced_meta\";b:1;s:12:\"googleverify\";s:0:\"\";s:8:\"msverify\";s:0:\"\";s:15:\"pinterestverify\";s:0:\"\";s:12:\"yandexverify\";s:0:\"\";s:14:\"yoast_tracking\";b:0;}','yes'),(3230,'ga_enhanced_link_attr','disabled','yes'),(3271,'ithemes-updater-keys','a:3:{s:11:\"backupbuddy\";s:16:\"f8l5oj47nd62xp10\";s:20:\"ithemes-security-pro\";s:16:\"r2to006p2058dtn9\";s:12:\"ithemes-sync\";s:16:\"ef1u61pm659i2pe5\";}','yes'),(6082,'pp_wp_role_sync','1','no'),(6084,'pp_buffer_metagroup_id_16eebba473f88da8bdfe24f6be24342f','a:3:{s:21:\"wp_all:wpdb_pp_groups\";O:8:\"stdClass\":7:{s:2:\"ID\";s:1:\"9\";s:10:\"group_name\";s:5:\"{All}\";s:17:\"group_description\";s:31:\"All users (including anonymous)\";s:12:\"metagroup_id\";s:6:\"wp_all\";s:14:\"metagroup_type\";s:7:\"wp_role\";s:8:\"group_id\";s:1:\"9\";s:6:\"status\";s:6:\"active\";}s:22:\"wp_auth:wpdb_pp_groups\";O:8:\"stdClass\":7:{s:2:\"ID\";s:1:\"8\";s:10:\"group_name\";s:15:\"{Authenticated}\";s:17:\"group_description\";s:55:\"All users who are logged in and have a role on the site\";s:12:\"metagroup_id\";s:7:\"wp_auth\";s:14:\"metagroup_type\";s:7:\"wp_role\";s:8:\"group_id\";s:1:\"8\";s:6:\"status\";s:6:\"active\";}s:22:\"wp_anon:wpdb_pp_groups\";O:8:\"stdClass\":7:{s:2:\"ID\";s:1:\"7\";s:10:\"group_name\";s:11:\"{Anonymous}\";s:17:\"group_description\";s:31:\"Anonymous users (not logged in)\";s:12:\"metagroup_id\";s:7:\"wp_anon\";s:14:\"metagroup_type\";s:7:\"wp_role\";s:8:\"group_id\";s:1:\"7\";s:6:\"status\";s:6:\"active\";}}','no'),(6085,'pp_c_version','a:2:{s:7:\"version\";s:6:\"2.1.49\";s:10:\"db_version\";s:5:\"2.0.1\";}','yes'),(6086,'ppperm_added_role_caps_10beta','1','no'),(8407,'finished_splitting_shared_terms','1','yes'),(6658,'theme_mods_jupiter','a:3:{i:0;b:0;s:18:\"nav_menu_locations\";a:2:{s:7:\"primary\";i:28;s:6:\"footer\";i:29;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1453928784;s:4:\"data\";a:18:{s:19:\"wp_inactive_widgets\";a:2:{i:0;s:6:\"text-2\";i:1;s:10:\"nav_menu-2\";}s:9:\"sidebar-1\";a:7:{i:0;s:5:\"rss-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";i:6;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:1:{i:0;s:11:\"tag_cloud-2\";}s:9:\"sidebar-3\";a:0:{}s:9:\"sidebar-4\";a:0:{}s:9:\"sidebar-5\";N;s:9:\"sidebar-6\";N;s:9:\"sidebar-7\";N;s:9:\"sidebar-8\";N;s:9:\"sidebar-9\";N;s:10:\"sidebar-10\";N;s:10:\"sidebar-11\";N;s:10:\"sidebar-12\";N;s:10:\"sidebar-13\";N;s:10:\"sidebar-14\";N;s:10:\"sidebar-15\";N;s:10:\"sidebar-16\";N;s:10:\"sidebar-17\";N;}}}','yes'),(6659,'woocommerce_enable_lightbox','no','yes'),(6660,'Jupiter_options','a:337:{s:10:\"grid_width\";s:4:\"1140\";s:13:\"content_width\";s:2:\"73\";s:18:\"content_responsive\";s:3:\"960\";s:20:\"responsive_nav_width\";s:4:\"1140\";s:13:\"loggedin_menu\";s:12:\"primary-menu\";s:9:\"analytics\";s:0:\"\";s:10:\"typekit_id\";s:0:\"\";s:15:\"notfound_layout\";s:4:\"full\";s:18:\"disable_breadcrumb\";s:4:\"true\";s:20:\"disable_smoothscroll\";s:4:\"true\";s:20:\"image_resize_quality\";s:3:\"100\";s:14:\"pages_comments\";s:5:\"false\";s:14:\"custom_favicon\";s:0:\"\";s:4:\"logo\";s:0:\"\";s:17:\"light_header_logo\";s:0:\"\";s:18:\"sticky_header_logo\";s:0:\"\";s:15:\"responsive_logo\";s:0:\"\";s:11:\"footer_logo\";s:0:\"\";s:11:\"iphone_icon\";s:0:\"\";s:18:\"iphone_icon_retina\";s:0:\"\";s:9:\"ipad_icon\";s:0:\"\";s:16:\"ipad_icon_retina\";s:0:\"\";s:18:\"enable_header_date\";s:5:\"false\";s:22:\"header_toolbar_tagline\";s:0:\"\";s:20:\"header_toolbar_phone\";s:0:\"\";s:20:\"header_toolbar_email\";s:0:\"\";s:20:\"header_toolbar_login\";s:4:\"true\";s:24:\"header_toolbar_subscribe\";s:5:\"false\";s:20:\"mailchimp_action_url\";s:0:\"\";s:18:\"theme_header_style\";s:1:\"1\";s:18:\"theme_header_align\";s:4:\"left\";s:20:\"theme_toolbar_toggle\";s:4:\"true\";s:11:\"header_grid\";s:4:\"true\";s:19:\"header_sticky_style\";s:5:\"fixed\";s:20:\"sticky_header_offset\";s:6:\"header\";s:13:\"header_height\";s:2:\"90\";s:20:\"header_scroll_height\";s:2:\"55\";s:22:\"header_search_location\";s:17:\"fullscreen_search\";s:18:\"header_burger_size\";s:5:\"small\";s:23:\"header_style3_structure\";s:22:\"header_dashboard_style\";s:18:\"vertical_menu_anim\";s:1:\"1\";s:26:\"vertical_header_logo_align\";s:6:\"center\";s:28:\"vertical_header_logo_padding\";s:2:\"10\";s:21:\"vertical_header_align\";s:4:\"left\";s:23:\"vertical_menu_copyright\";s:41:\"Copyright All Rights Reserved &copy; 2015\";s:22:\"header_start_tour_text\";s:0:\"\";s:22:\"header_social_location\";s:7:\"toolbar\";s:28:\"header_social_networks_style\";s:6:\"circle\";s:16:\"header_icon_size\";s:5:\"small\";s:27:\"header_social_networks_site\";s:0:\"\";s:26:\"header_social_networks_url\";s:0:\"\";s:9:\"preloader\";s:5:\"false\";s:14:\"preloader_logo\";s:0:\"\";s:19:\"preloader_bar_color\";s:0:\"\";s:19:\"preloader_txt_color\";s:4:\"#444\";s:18:\"preloader_bg_color\";s:4:\"#fff\";s:8:\"sidebars\";s:0:\"\";s:14:\"disable_footer\";s:4:\"true\";s:12:\"boxed_footer\";s:4:\"true\";s:13:\"footer_gutter\";s:1:\"2\";s:22:\"footer_wrapper_padding\";s:2:\"30\";s:27:\"footer_widget_margin_bottom\";s:2:\"40\";s:11:\"footer_type\";s:1:\"1\";s:14:\"footer_columns\";s:1:\"4\";s:18:\"disable_sub_footer\";s:4:\"true\";s:17:\"enable_footer_nav\";s:4:\"true\";s:9:\"copyright\";s:41:\"Copyright All Rights Reserved &copy; 2015\";s:21:\"disable_quick_contact\";s:4:\"true\";s:19:\"quick_contact_title\";s:10:\"Contact Us\";s:19:\"quick_contact_email\";s:29:\"bbyrne@cornershopcreative.com\";s:18:\"quick_contact_desc\";s:89:\"We\'re not around right now. But you can send us an email and we\'ll get back to you, asap.\";s:21:\"captcha_quick_contact\";s:4:\"true\";s:10:\"skin_color\";s:7:\"#f97352\";s:15:\"body_text_color\";s:7:\"#777777\";s:7:\"p_color\";s:7:\"#777777\";s:7:\"a_color\";s:7:\"#2e2e2e\";s:13:\"a_color_hover\";s:7:\"#f97352\";s:12:\"strong_color\";s:7:\"#f97352\";s:8:\"h1_color\";s:7:\"#404040\";s:8:\"h2_color\";s:7:\"#404040\";s:8:\"h3_color\";s:7:\"#404040\";s:8:\"h4_color\";s:7:\"#404040\";s:8:\"h5_color\";s:7:\"#404040\";s:8:\"h6_color\";s:7:\"#404040\";s:31:\"background_selector_orientation\";s:17:\"full_width_layout\";s:24:\"boxed_layout_shadow_size\";s:1:\"0\";s:29:\"boxed_layout_shadow_intensity\";s:1:\"0\";s:10:\"body_color\";s:4:\"#fff\";s:10:\"body_image\";s:0:\"\";s:9:\"body_size\";s:5:\"false\";s:13:\"body_position\";s:0:\"\";s:15:\"body_attachment\";s:0:\"\";s:11:\"body_repeat\";s:0:\"\";s:11:\"body_source\";s:8:\"no-image\";s:10:\"page_color\";s:4:\"#fff\";s:10:\"page_image\";s:0:\"\";s:9:\"page_size\";s:5:\"false\";s:13:\"page_position\";s:0:\"\";s:15:\"page_attachment\";s:0:\"\";s:11:\"page_repeat\";s:0:\"\";s:11:\"page_source\";s:8:\"no-image\";s:12:\"header_color\";s:4:\"#fff\";s:12:\"header_image\";s:0:\"\";s:11:\"header_size\";s:5:\"false\";s:15:\"header_position\";s:0:\"\";s:17:\"header_attachment\";s:0:\"\";s:13:\"header_repeat\";s:0:\"\";s:13:\"header_source\";s:8:\"no-image\";s:12:\"banner_color\";s:7:\"#f7f7f7\";s:12:\"banner_image\";s:0:\"\";s:11:\"banner_size\";s:4:\"true\";s:15:\"banner_position\";s:0:\"\";s:17:\"banner_attachment\";s:0:\"\";s:13:\"banner_repeat\";s:0:\"\";s:13:\"banner_source\";s:8:\"no-image\";s:12:\"footer_color\";s:7:\"#3d4045\";s:12:\"footer_image\";s:0:\"\";s:11:\"footer_size\";s:5:\"false\";s:15:\"footer_position\";s:0:\"\";s:17:\"footer_attachment\";s:0:\"\";s:13:\"footer_repeat\";s:0:\"\";s:13:\"footer_source\";s:8:\"no-image\";s:14:\"header_opacity\";s:1:\"1\";s:21:\"header_sticky_opacity\";s:1:\"1\";s:27:\"header_btn_border_thickness\";s:1:\"1\";s:19:\"header_border_color\";s:7:\"#ededed\";s:26:\"sticky_header_border_color\";s:0:\"\";s:19:\"header_social_color\";s:7:\"#999999\";s:25:\"header_social_hover_color\";s:4:\"#ccc\";s:26:\"header_social_border_color\";s:7:\"#999999\";s:27:\"header_social_bg_main_color\";s:7:\"#232323\";s:22:\"header_social_bg_color\";s:7:\"#232323\";s:16:\"start_tour_color\";s:4:\"#333\";s:14:\"main_nav_hover\";s:1:\"5\";s:17:\"main_nav_bg_color\";s:0:\"\";s:23:\"main_nav_top_text_color\";s:7:\"#444444\";s:23:\"main_nav_top_hover_skin\";s:7:\"#f97352\";s:28:\"main_nav_top_hover_txt_color\";s:4:\"#fff\";s:18:\"main_nav_sub_width\";s:3:\"230\";s:29:\"main_nav_sub_border_top_color\";s:7:\"#f97352\";s:21:\"main_nav_sub_bg_color\";s:7:\"#333333\";s:23:\"main_nav_sub_text_color\";s:7:\"#b3b3b3\";s:29:\"main_nav_sub_text_color_hover\";s:7:\"#ffffff\";s:23:\"main_nav_sub_icon_color\";s:7:\"#e0e0e0\";s:27:\"main_nav_sub_hover_bg_color\";s:0:\"\";s:25:\"main_nav_mega_title_color\";s:7:\"#ffffff\";s:14:\"nav_sub_shadow\";s:5:\"false\";s:26:\"sub_level_box_border_color\";s:0:\"\";s:23:\"mega_menu_divider_color\";s:0:\"\";s:26:\"responsive_icon_text_color\";s:0:\"\";s:20:\"responsive_nav_color\";s:4:\"#fff\";s:24:\"responsive_nav_txt_color\";s:7:\"#444444\";s:17:\"header_toolbar_bg\";s:7:\"#ffffff\";s:27:\"header_toolbar_border_color\";s:0:\"\";s:24:\"header_toolbar_txt_color\";s:7:\"#999999\";s:25:\"header_toolbar_link_color\";s:7:\"#999999\";s:35:\"header_toolbar_social_network_color\";s:7:\"#999999\";s:30:\"header_toolbar_search_input_bg\";s:0:\"\";s:31:\"header_toolbar_search_input_txt\";s:7:\"#c7c7c7\";s:16:\"page_title_color\";s:7:\"#4d4d4d\";s:17:\"page_title_shadow\";s:5:\"false\";s:19:\"page_subtitle_color\";s:7:\"#a3a3a3\";s:15:\"breadcrumb_skin\";s:4:\"dark\";s:19:\"banner_border_color\";s:7:\"#ededed\";s:13:\"dash_bg_color\";s:4:\"#444\";s:16:\"dash_title_color\";s:4:\"#fff\";s:15:\"dash_text_color\";s:4:\"#eee\";s:16:\"dash_links_color\";s:7:\"#fafafa\";s:22:\"dash_links_hover_color\";s:0:\"\";s:19:\"dash_nav_link_color\";s:4:\"#fff\";s:25:\"dash_nav_link_hover_color\";s:4:\"#fff\";s:23:\"dash_nav_bg_hover_color\";s:0:\"\";s:19:\"fullscreen_nav_logo\";s:4:\"dark\";s:23:\"fullscreen_nav_bg_color\";s:4:\"#444\";s:25:\"fullscreen_nav_link_color\";s:4:\"#fff\";s:29:\"fullscreen_nav_link_hov_color\";s:4:\"#444\";s:32:\"fullscreen_nav_link_hov_bg_color\";s:4:\"#fff\";s:19:\"sidebar_title_color\";s:7:\"#333333\";s:18:\"sidebar_text_color\";s:7:\"#999999\";s:19:\"sidebar_links_color\";s:7:\"#999999\";s:25:\"sidebar_links_hover_color\";s:0:\"\";s:20:\"footer_top_thickness\";s:1:\"0\";s:23:\"footer_top_border_color\";s:0:\"\";s:18:\"footer_title_color\";s:4:\"#fff\";s:17:\"footer_text_color\";s:7:\"#808080\";s:18:\"footer_links_color\";s:7:\"#999999\";s:24:\"footer_links_hover_color\";s:0:\"\";s:19:\"sub_footer_bg_color\";s:7:\"#43474d\";s:25:\"sub_footer_nav_copy_color\";s:7:\"#8c8e91\";s:11:\"font_family\";s:0:\"\";s:20:\"special_fonts_list_1\";s:9:\"Open+Sans\";s:20:\"special_fonts_type_1\";s:6:\"google\";s:18:\"special_elements_1\";a:1:{i:0;s:4:\"body\";}s:20:\"special_fonts_list_2\";s:0:\"\";s:20:\"special_fonts_type_2\";s:6:\"google\";s:18:\"special_elements_2\";a:0:{}s:21:\"typekit_font_family_1\";s:0:\"\";s:18:\"typekit_elements_1\";a:0:{}s:21:\"typekit_font_family_2\";s:0:\"\";s:18:\"typekit_elements_2\";a:0:{}s:14:\"body_font_size\";s:2:\"14\";s:16:\"body_line_height\";s:4:\"1.66\";s:11:\"body_weight\";s:6:\"normal\";s:6:\"p_size\";s:2:\"16\";s:13:\"p_line_height\";s:4:\"1.66\";s:7:\"h1_size\";s:2:\"36\";s:9:\"h1_weight\";s:4:\"bold\";s:12:\"h1_transform\";s:9:\"uppercase\";s:7:\"h2_size\";s:2:\"30\";s:9:\"h2_weight\";s:4:\"bold\";s:12:\"h2_transform\";s:9:\"uppercase\";s:7:\"h3_size\";s:2:\"24\";s:9:\"h3_weight\";s:4:\"bold\";s:12:\"h3_transform\";s:9:\"uppercase\";s:7:\"h4_size\";s:2:\"18\";s:9:\"h4_weight\";s:4:\"bold\";s:12:\"h4_transform\";s:9:\"uppercase\";s:7:\"h5_size\";s:2:\"16\";s:9:\"h5_weight\";s:4:\"bold\";s:12:\"h5_transform\";s:9:\"uppercase\";s:7:\"h6_size\";s:2:\"14\";s:9:\"h6_weight\";s:6:\"normal\";s:12:\"h6_transform\";s:9:\"uppercase\";s:15:\"start_tour_size\";s:2:\"14\";s:19:\"main_nav_item_space\";s:2:\"20\";s:17:\"main_nav_top_size\";s:2:\"13\";s:19:\"main_menu_transform\";s:9:\"uppercase\";s:19:\"main_nav_top_weight\";s:4:\"bold\";s:17:\"main_nav_sub_size\";s:2:\"12\";s:22:\"main_nav_sub_transform\";s:9:\"uppercase\";s:19:\"main_nav_sub_weight\";s:6:\"normal\";s:27:\"main_nav_top_letter_spacing\";s:1:\"0\";s:27:\"main_nav_sub_letter_spacing\";s:1:\"1\";s:25:\"page_introduce_title_size\";s:2:\"20\";s:35:\"page_introduce_title_letter_spacing\";s:1:\"2\";s:21:\"page_introduce_weight\";s:6:\"normal\";s:20:\"page_title_transform\";s:9:\"uppercase\";s:28:\"page_introduce_subtitle_size\";s:2:\"14\";s:33:\"page_introduce_subtitle_transform\";s:4:\"none\";s:15:\"dash_title_size\";s:2:\"14\";s:17:\"dash_title_weight\";s:3:\"800\";s:20:\"dash_title_transform\";s:9:\"uppercase\";s:14:\"dash_text_size\";s:2:\"12\";s:16:\"dash_text_weight\";s:6:\"normal\";s:26:\"fullscreen_nav_logo_margin\";s:3:\"125\";s:26:\"fullscreen_nav_menu_gutter\";s:2:\"25\";s:29:\"fullscreen_nav_menu_font_size\";s:2:\"16\";s:31:\"fullscreen_nav_menu_font_weight\";s:3:\"800\";s:34:\"fullscreen_nav_menu_text_transform\";s:9:\"uppercase\";s:34:\"fullscreen_nav_menu_letter_spacing\";s:1:\"0\";s:18:\"sidebar_title_size\";s:2:\"14\";s:20:\"sidebar_title_weight\";s:6:\"bolder\";s:23:\"sidebar_title_transform\";s:9:\"uppercase\";s:17:\"sidebar_text_size\";s:2:\"14\";s:19:\"sidebar_text_weight\";s:6:\"normal\";s:17:\"footer_title_size\";s:2:\"14\";s:19:\"footer_title_weight\";s:3:\"800\";s:22:\"footer_title_transform\";s:9:\"uppercase\";s:16:\"footer_text_size\";s:2:\"14\";s:18:\"footer_text_weight\";s:6:\"normal\";s:14:\"copyright_size\";s:2:\"11\";s:24:\"copyright_letter_spacing\";s:1:\"1\";s:14:\"portfolio_slug\";s:15:\"portfolio-posts\";s:23:\"portfolio_single_layout\";s:4:\"full\";s:29:\"Portfolio_single_image_height\";s:3:\"500\";s:21:\"single_portfolio_cats\";s:5:\"false\";s:23:\"single_portfolio_social\";s:4:\"true\";s:30:\"enable_portfolio_similar_posts\";s:4:\"true\";s:19:\"portfolio_next_prev\";s:4:\"true\";s:24:\"enable_portfolio_comment\";s:5:\"false\";s:24:\"archive_portfolio_layout\";s:5:\"right\";s:23:\"archive_portfolio_style\";s:7:\"classic\";s:24:\"archive_portfolio_column\";s:1:\"3\";s:30:\"archive_portfolio_image_height\";s:3:\"400\";s:34:\"archive_portfolio_pagination_style\";s:1:\"1\";s:13:\"single_layout\";s:4:\"full\";s:28:\"single_featured_image_height\";s:3:\"300\";s:29:\"single_disable_featured_image\";s:4:\"true\";s:20:\"blog_single_img_crop\";s:4:\"true\";s:17:\"blog_single_title\";s:4:\"true\";s:19:\"single_meta_section\";s:4:\"true\";s:18:\"single_blog_social\";s:4:\"true\";s:14:\"blog_prev_next\";s:4:\"true\";s:18:\"enable_blog_author\";s:4:\"true\";s:18:\"diable_single_tags\";s:4:\"true\";s:27:\"enable_single_related_posts\";s:4:\"true\";s:27:\"enable_blog_single_comments\";s:4:\"true\";s:19:\"archive_page_layout\";s:5:\"right\";s:18:\"archive_loop_style\";s:6:\"modern\";s:18:\"archive_page_title\";s:8:\"Archives\";s:24:\"archive_disable_subtitle\";s:4:\"true\";s:25:\"archive_blog_image_height\";s:3:\"350\";s:17:\"archive_blog_meta\";s:4:\"true\";s:24:\"archive_pagination_style\";s:1:\"1\";s:18:\"search_page_layout\";s:5:\"right\";s:17:\"search_page_title\";s:6:\"Search\";s:23:\"search_disable_subtitle\";s:4:\"true\";s:9:\"news_slug\";s:10:\"news-posts\";s:9:\"news_page\";s:0:\"\";s:11:\"news_layout\";s:4:\"full\";s:26:\"news_featured_image_height\";s:3:\"340\";s:19:\"woocommerce_catalog\";s:5:\"false\";s:19:\"woo_loop_img_height\";s:3:\"300\";s:17:\"woo_image_quality\";s:4:\"crop\";s:19:\"woo_loop_image_size\";s:1:\"1\";s:25:\"woocommerce_single_layout\";s:4:\"full\";s:31:\"woocommerce_category_page_title\";s:4:\"Shop\";s:32:\"woocommerce_single_product_title\";s:4:\"true\";s:33:\"woocommerce_single_social_network\";s:4:\"true\";s:27:\"header_checkout_woocommerce\";s:4:\"true\";s:13:\"shopping_cart\";s:4:\"true\";s:26:\"woocommerce_loop_show_desc\";s:5:\"false\";s:9:\"minify-js\";s:5:\"false\";s:10:\"minify-css\";s:4:\"true\";s:17:\"remove-js-css-ver\";s:4:\"true\";s:19:\"portfolio-post-type\";s:4:\"true\";s:14:\"news-post-type\";s:4:\"true\";s:13:\"faq-post-type\";s:4:\"true\";s:17:\"pricing-post-type\";s:4:\"true\";s:17:\"clients-post-type\";s:4:\"true\";s:19:\"employees-post-type\";s:4:\"true\";s:22:\"testimonials-post-type\";s:4:\"true\";s:20:\"flexslider-post-type\";s:4:\"true\";s:14:\"edge-post-type\";s:4:\"true\";s:19:\"iCarousel-post-type\";s:4:\"true\";s:16:\"banner-post-type\";s:4:\"true\";s:20:\"tab-slider-post-type\";s:4:\"true\";s:26:\"animated-columns-post-type\";s:4:\"true\";s:20:\"twitter_consumer_key\";s:0:\"\";s:23:\"twitter_consumer_secret\";s:0:\"\";s:20:\"twitter_access_token\";s:0:\"\";s:27:\"twitter_access_token_secret\";s:0:\"\";s:9:\"custom_js\";s:0:\"\";s:10:\"custom_css\";s:0:\"\";s:20:\"theme_export_options\";s:0:\"\";s:20:\"theme_import_options\";s:0:\"\";i:0;b:0;}','yes'),(6661,'jupiter_theme_version','5.0.7','yes'),(8657,'gravityformsaddon_gravityformswebapi_version','1.0','yes'),(8658,'rg_form_version','1.9.15','yes'),(8663,'rg_gforms_key','645453753d10f59d4576781a1523e377','yes'),(8664,'rg_gforms_disable_css','0','yes'),(8665,'rg_gforms_enable_html5','0','yes'),(8666,'rg_gforms_enable_akismet','','yes'),(8667,'rg_gforms_captcha_public_key','','yes'),(8668,'rg_gforms_captcha_private_key','','yes'),(8669,'rg_gforms_currency','USD','yes'),(8670,'rg_gforms_message','<!--GFM--><div style=\"background: #fff; border-left: 4px solid #fff; border-color: #dd3d36; -webkit-box-shadow: 0 1px 1px 0 rgba( 0, 0, 0, 0.1 );box-shadow: 0 1px 1px 0 rgba( 0, 0, 0, 0.1 );margin:20px 0 10px 0; padding: 1px 12px;margin-bottom:15px;\"><h3>Critical Update Available.</h3><p style=\"text-align:left\">Your version of Gravity Forms is extremely outdated and contains known security vulnerabilities that may get exploited. It is very important that you update Gravity Forms immediately. <a href=\"admin.php?page=gf_update\"><b>Update Now!</b></a><p></div>','yes'),(10149,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10150,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10151,'widget_gform_widget','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(9727,'pb_backupbuddy','a:101:{s:12:\"data_version\";s:2:\"14\";s:21:\"importbuddy_pass_hash\";s:0:\"\";s:23:\"importbuddy_pass_length\";i:0;s:16:\"backup_reminders\";i:1;s:16:\"edits_since_last\";i:2;s:17:\"last_backup_start\";i:0;s:18:\"last_backup_finish\";i:0;s:18:\"last_backup_serial\";s:0:\"\";s:17:\"last_backup_stats\";a:0:{}s:21:\"last_error_email_time\";i:0;s:19:\"force_compatibility\";i:0;s:29:\"force_mysqldump_compatibility\";i:0;s:9:\"schedules\";a:0:{}s:9:\"log_level\";s:1:\"1\";s:13:\"high_security\";i:0;s:19:\"next_schedule_index\";i:100;s:13:\"archive_limit\";i:0;s:18:\"archive_limit_full\";i:0;s:16:\"archive_limit_db\";i:0;s:19:\"archive_limit_files\";i:0;s:18:\"archive_limit_size\";i:0;s:22:\"archive_limit_size_big\";i:50000;s:17:\"archive_limit_age\";i:0;s:26:\"delete_archives_pre_backup\";i:0;s:23:\"lock_archives_directory\";i:0;s:25:\"set_greedy_execution_time\";i:0;s:28:\"email_notify_scheduled_start\";s:0:\"\";s:36:\"email_notify_scheduled_start_subject\";s:49:\"BackupBuddy Scheduled Backup Started - {site_url}\";s:33:\"email_notify_scheduled_start_body\";s:140:\"A scheduled backup has started with BackupBuddy v{backupbuddy_version} on {current_datetime} for the site {site_url}.\n\nDetails:\r\n\r\n{message}\";s:31:\"email_notify_scheduled_complete\";s:0:\"\";s:39:\"email_notify_scheduled_complete_subject\";s:50:\"BackupBuddy Scheduled Backup Complete - {site_url}\";s:36:\"email_notify_scheduled_complete_body\";s:142:\"A scheduled backup has completed with BackupBuddy v{backupbuddy_version} on {current_datetime} for the site {site_url}.\n\nDetails:\r\n\r\n{message}\";s:24:\"email_notify_send_finish\";s:0:\"\";s:32:\"email_notify_send_finish_subject\";s:43:\"BackupBuddy File Send Finished - {site_url}\";s:29:\"email_notify_send_finish_body\";s:146:\"A destination file send has finished with BackupBuddy v{backupbuddy_version} on {current_datetime} for the site {site_url}.\n\nDetails:\r\n\r\n{message}\";s:18:\"email_notify_error\";s:29:\"bbyrne@cornershopcreative.com\";s:26:\"email_notify_error_subject\";s:30:\"BackupBuddy Error - {site_url}\";s:23:\"email_notify_error_body\";s:132:\"An error occurred with BackupBuddy v{backupbuddy_version} on {current_datetime} for the site {site_url}. Error details:\r\n\r\n{message}\";s:12:\"email_return\";s:0:\"\";s:19:\"remote_destinations\";a:0:{}s:27:\"remote_send_timeout_retries\";s:1:\"1\";s:11:\"role_access\";s:16:\"activate_plugins\";s:16:\"dropboxtemptoken\";s:0:\"\";s:11:\"backup_mode\";s:1:\"2\";s:16:\"multisite_export\";s:1:\"0\";s:16:\"backup_directory\";s:0:\"\";s:14:\"temp_directory\";s:0:\"\";s:13:\"log_directory\";s:0:\"\";s:10:\"log_serial\";s:15:\"2wcfgm1djupdbvs\";s:13:\"notifications\";a:0:{}s:19:\"zip_method_strategy\";s:1:\"1\";s:24:\"database_method_strategy\";s:3:\"php\";s:17:\"alternative_zip_2\";s:1:\"0\";s:19:\"ignore_zip_warnings\";s:1:\"0\";s:19:\"ignore_zip_symlinks\";s:1:\"1\";s:18:\"zip_build_strategy\";s:1:\"3\";s:15:\"zip_step_period\";s:2:\"30\";s:13:\"zip_burst_gap\";s:1:\"2\";s:21:\"zip_min_burst_content\";s:2:\"10\";s:21:\"zip_max_burst_content\";s:3:\"100\";s:25:\"disable_zipmethod_caching\";s:1:\"0\";s:19:\"archive_name_format\";s:8:\"datetime\";s:20:\"archive_name_profile\";s:1:\"0\";s:30:\"disable_https_local_ssl_verify\";s:1:\"0\";s:17:\"save_comment_meta\";s:1:\"1\";s:27:\"ignore_command_length_check\";s:1:\"0\";s:18:\"default_backup_tab\";s:1:\"0\";s:18:\"deployment_allowed\";s:1:\"0\";s:9:\"hide_live\";s:1:\"0\";s:10:\"remote_api\";a:2:{s:4:\"keys\";a:0:{}s:3:\"ips\";a:0:{}}s:20:\"skip_spawn_cron_call\";s:1:\"0\";s:5:\"stats\";a:6:{s:9:\"site_size\";i:0;s:18:\"site_size_excluded\";i:0;s:17:\"site_size_updated\";i:0;s:7:\"db_size\";i:0;s:16:\"db_size_excluded\";i:0;s:15:\"db_size_updated\";i:0;}s:9:\"disalerts\";a:1:{s:25:\"backupbuddy_version_seven\";i:1455891634;}s:15:\"breakout_tables\";s:1:\"1\";s:19:\"include_importbuddy\";s:1:\"1\";s:17:\"max_site_log_size\";s:1:\"3\";s:11:\"compression\";s:1:\"1\";s:25:\"no_new_backups_error_days\";s:2:\"45\";s:15:\"skip_quicksetup\";s:1:\"0\";s:13:\"prevent_flush\";s:1:\"0\";s:17:\"rollback_cleanups\";a:0:{}s:20:\"phpmysqldump_maxrows\";s:0:\"\";s:20:\"disable_localization\";s:1:\"0\";s:18:\"max_execution_time\";s:0:\"\";s:24:\"backup_cron_rescheduling\";s:1:\"0\";s:29:\"backup_cron_passed_force_time\";s:0:\"\";s:20:\"force_single_db_file\";s:1:\"0\";s:11:\"deployments\";a:0:{}s:19:\"max_send_stats_days\";s:1:\"7\";s:20:\"max_send_stats_count\";s:1:\"6\";s:26:\"max_notifications_age_days\";s:2:\"21\";s:19:\"save_backup_sum_log\";s:1:\"1\";s:26:\"limit_single_cron_per_pass\";s:1:\"1\";s:18:\"tested_php_runtime\";i:0;s:17:\"tested_php_memory\";i:0;s:23:\"last_tested_php_runtime\";i:0;s:22:\"last_tested_php_memory\";i:0;s:17:\"use_internal_cron\";s:1:\"0\";s:33:\"php_runtime_test_minimum_interval\";s:6:\"604800\";s:32:\"php_memory_test_minimum_interval\";s:6:\"604800\";s:8:\"profiles\";a:3:{i:0;a:8:{s:4:\"type\";s:8:\"defaults\";s:5:\"title\";s:15:\"Global Defaults\";s:18:\"skip_database_dump\";s:1:\"0\";s:19:\"backup_nonwp_tables\";s:1:\"0\";s:15:\"integrity_check\";s:1:\"1\";s:29:\"mysqldump_additional_includes\";s:0:\"\";s:29:\"mysqldump_additional_excludes\";s:0:\"\";s:8:\"excludes\";s:0:\"\";}i:1;a:3:{s:4:\"type\";s:2:\"db\";s:5:\"title\";s:13:\"Database Only\";s:3:\"tip\";s:49:\"Just your database. I like your minimalist style.\";}i:2;a:2:{s:4:\"type\";s:4:\"full\";s:5:\"title\";s:15:\"Complete Backup\";}}}','yes'),(10152,'site_icon','0','yes'),(10153,'medium_large_size_w','768','yes'),(10154,'medium_large_size_h','0','yes'),(10824,'theme_options','','yes'),(10825,'global_assets_filename','','yes'),(10826,'Jupiter_options_build','56a9313102607','yes'),(10827,'jupiter_db_version','5.0.7','yes'),(10828,'widget_contact_form','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10829,'widget_contact_info','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10830,'widget_gmap','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10831,'widget_social','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10832,'widget_subnav','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10833,'widget_testimonial_widget','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10834,'widget_twitter','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10835,'widget_video','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10836,'widget_flickr','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10837,'widget_instagram','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10838,'widget_news_feed_widget','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10839,'widget_mini_slidshow_widget','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(10840,'Jupiter_options_imported','false','yes'),(11693,'rewrite_rules','a:86:{s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:47:\"category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:42:\"category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:23:\"category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:35:\"category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:17:\"category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:44:\"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:39:\"tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:20:\"tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:32:\"tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:14:\"tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:45:\"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:40:\"type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:21:\"type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:33:\"type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:15:\"type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:12:\"robots\\.txt$\";s:18:\"index.php?robots=1\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:27:\"comment-page-([0-9]{1,})/?$\";s:39:\"index.php?&page_id=45&cpage=$matches[1]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:47:\"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:42:\"author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:23:\"author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:35:\"author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:17:\"author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:69:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:45:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:39:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:56:\"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:51:\"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:32:\"([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:44:\"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:26:\"([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:43:\"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:38:\"([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:19:\"([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:31:\"([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:13:\"([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";s:27:\"[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\"[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\"[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\"[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"([^/]+)/embed/?$\";s:37:\"index.php?name=$matches[1]&embed=true\";s:20:\"([^/]+)/trackback/?$\";s:31:\"index.php?name=$matches[1]&tb=1\";s:40:\"([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:35:\"([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:28:\"([^/]+)/page/?([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&paged=$matches[2]\";s:35:\"([^/]+)/comment-page-([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&cpage=$matches[2]\";s:24:\"([^/]+)(?:/([0-9]+))?/?$\";s:43:\"index.php?name=$matches[1]&page=$matches[2]\";s:16:\"[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:26:\"[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:46:\"[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:22:\"[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";}','yes'),(11755,'can_compress_scripts','0','yes');
/*!40000 ALTER TABLE `wpdb_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_postmeta`
--

DROP TABLE IF EXISTS `wpdb_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=MyISAM AUTO_INCREMENT=195 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_postmeta`
--

LOCK TABLES `wpdb_postmeta` WRITE;
/*!40000 ALTER TABLE `wpdb_postmeta` DISABLE KEYS */;
INSERT INTO `wpdb_postmeta` VALUES (1,2,'_wp_page_template','default'),(2,4,'_edit_last','2'),(3,4,'_edit_lock','1359749539:2'),(4,6,'_edit_last','2'),(5,6,'_edit_lock','1389324298:2'),(6,6,'_wp_page_template','default'),(7,9,'_menu_item_type','post_type'),(8,9,'_menu_item_menu_item_parent','21'),(9,9,'_menu_item_object_id','6'),(10,9,'_menu_item_object','page'),(11,9,'_menu_item_target',''),(12,9,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(13,9,'_menu_item_xfn',''),(14,9,'_menu_item_url',''),(16,10,'_menu_item_type','post_type'),(17,10,'_menu_item_menu_item_parent','0'),(18,10,'_menu_item_object_id','2'),(19,10,'_menu_item_object','page'),(20,10,'_menu_item_target',''),(21,10,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(22,10,'_menu_item_xfn',''),(23,10,'_menu_item_url',''),(25,11,'_menu_item_type','custom'),(26,11,'_menu_item_menu_item_parent','0'),(27,11,'_menu_item_object_id','11'),(28,11,'_menu_item_object','custom'),(29,11,'_menu_item_target',''),(30,11,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(31,11,'_menu_item_xfn',''),(32,11,'_menu_item_url','http://www.google.com'),(36,4,'_wp_page_template','onecolumn-page.php'),(37,14,'_menu_item_type','post_type'),(38,14,'_menu_item_menu_item_parent','23'),(39,14,'_menu_item_object_id','4'),(40,14,'_menu_item_object','page'),(41,14,'_menu_item_target',''),(42,14,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(43,14,'_menu_item_xfn',''),(44,14,'_menu_item_url',''),(46,2,'_edit_lock','1426860100:2'),(47,2,'_edit_last','2'),(48,21,'_menu_item_type','custom'),(49,21,'_menu_item_menu_item_parent','0'),(50,21,'_menu_item_object_id','21'),(51,21,'_menu_item_object','custom'),(52,21,'_menu_item_target',''),(53,21,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(54,21,'_menu_item_xfn',''),(55,21,'_menu_item_url','http://wordpress.bbyrne.cshp.co//'),(85,4,'_pp_custom','1'),(57,22,'_menu_item_type','post_type'),(58,22,'_menu_item_menu_item_parent','0'),(59,22,'_menu_item_object_id','2'),(60,22,'_menu_item_object','page'),(61,22,'_menu_item_target',''),(62,22,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(63,22,'_menu_item_xfn',''),(64,22,'_menu_item_url',''),(66,23,'_menu_item_type','post_type'),(67,23,'_menu_item_menu_item_parent','21'),(68,23,'_menu_item_object_id','6'),(69,23,'_menu_item_object','page'),(70,23,'_menu_item_target',''),(71,23,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(72,23,'_menu_item_xfn',''),(73,23,'_menu_item_url',''),(75,24,'_menu_item_type','post_type'),(76,24,'_menu_item_menu_item_parent','0'),(77,24,'_menu_item_object_id','4'),(78,24,'_menu_item_object','page'),(79,24,'_menu_item_target',''),(80,24,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(81,24,'_menu_item_xfn',''),(82,24,'_menu_item_url',''),(84,1,'_edit_lock','1390485880:2'),(105,49,'rule','a:5:{s:5:\"param\";s:9:\"post_type\";s:8:\"operator\";s:2:\"==\";s:5:\"value\";s:4:\"page\";s:8:\"order_no\";i:0;s:8:\"group_no\";i:0;}'),(106,49,'position','side'),(107,49,'layout','default'),(108,49,'hide_on_screen',''),(109,49,'_edit_lock','1372108025:2'),(97,45,'_edit_last','2'),(98,45,'_edit_lock','1404755645:2'),(99,45,'_wp_page_template','default'),(100,47,'_edit_last','2'),(101,47,'_edit_lock','1371847169:2'),(102,47,'_wp_page_template','default'),(103,49,'_edit_last','2'),(104,49,'field_51c8b4cae5fbc','a:10:{s:3:\"key\";s:19:\"field_51c8b4cae5fbc\";s:5:\"label\";s:26:\"Display Resources Sidebar?\";s:4:\"name\";s:17:\"resources_sidebar\";s:4:\"type\";s:10:\"true_false\";s:12:\"instructions\";s:19:\"Yes, show resources\";s:8:\"required\";s:1:\"1\";s:7:\"message\";s:0:\"\";s:13:\"default_value\";s:1:\"1\";s:17:\"conditional_logic\";a:3:{s:6:\"status\";s:1:\"0\";s:5:\"rules\";a:1:{i:0;a:2:{s:5:\"field\";s:4:\"null\";s:8:\"operator\";s:2:\"==\";}}s:8:\"allorany\";s:3:\"all\";}s:8:\"order_no\";i:0;}'),(94,39,'_edit_last','2'),(95,39,'nivo_settings','a:23:{s:4:\"type\";s:6:\"manual\";s:12:\"type_gallery\";s:1:\"1\";s:13:\"type_category\";s:1:\"1\";s:15:\"enable_captions\";s:2:\"on\";s:6:\"sizing\";s:10:\"responsive\";s:5:\"dim_x\";s:3:\"400\";s:5:\"dim_y\";s:3:\"150\";s:5:\"theme\";s:0:\"\";s:6:\"effect\";s:6:\"random\";s:6:\"slices\";s:2:\"15\";s:7:\"boxCols\";s:1:\"8\";s:7:\"boxRows\";s:1:\"4\";s:9:\"animSpeed\";s:3:\"500\";s:16:\"controlNavThumbs\";s:3:\"off\";s:14:\"thumbSizeWidth\";s:2:\"70\";s:15:\"thumbSizeHeight\";s:2:\"50\";s:9:\"pauseTime\";s:4:\"3000\";s:10:\"startSlide\";s:1:\"0\";s:12:\"directionNav\";s:2:\"on\";s:10:\"controlNav\";s:2:\"on\";s:12:\"pauseOnHover\";s:2:\"on\";s:13:\"manualAdvance\";s:3:\"off\";s:11:\"randomStart\";s:3:\"off\";}'),(96,39,'_edit_lock','1370377865:2'),(122,45,'_resources_sidebar','field_51c8b4cae5fbc'),(121,45,'resources_sidebar','1'),(120,75,'_resources_sidebar','field_51c8b4cae5fbc'),(119,75,'resources_sidebar','1'),(123,89,'_menu_item_type','post_type'),(124,89,'_menu_item_menu_item_parent','0'),(125,89,'_menu_item_object_id','45'),(126,89,'_menu_item_object','page'),(127,89,'_menu_item_target',''),(128,89,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(129,89,'_menu_item_xfn',''),(130,89,'_menu_item_url',''),(132,90,'_menu_item_type','post_type'),(133,90,'_menu_item_menu_item_parent','0'),(134,90,'_menu_item_object_id','2'),(135,90,'_menu_item_object','page'),(136,90,'_menu_item_target',''),(137,90,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(138,90,'_menu_item_xfn',''),(139,90,'_menu_item_url',''),(141,91,'_menu_item_type','post_type'),(142,91,'_menu_item_menu_item_parent','0'),(143,91,'_menu_item_object_id','6'),(144,91,'_menu_item_object','page'),(145,91,'_menu_item_target',''),(146,91,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(147,91,'_menu_item_xfn',''),(148,91,'_menu_item_url',''),(193,99,'_menu_item_url',''),(192,99,'_menu_item_xfn',''),(191,99,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(190,99,'_menu_item_target',''),(189,99,'_menu_item_object','page'),(188,99,'_menu_item_object_id','45'),(187,99,'_menu_item_menu_item_parent','0'),(186,99,'_menu_item_type','post_type'),(168,94,'_menu_item_type','post_type'),(169,94,'_menu_item_menu_item_parent','0'),(170,94,'_menu_item_object_id','2'),(171,94,'_menu_item_object','page'),(172,94,'_menu_item_target',''),(173,94,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(174,94,'_menu_item_xfn',''),(175,94,'_menu_item_url',''),(177,95,'_menu_item_type','post_type'),(178,95,'_menu_item_menu_item_parent','0'),(179,95,'_menu_item_object_id','6'),(180,95,'_menu_item_object','page'),(181,95,'_menu_item_target',''),(182,95,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(183,95,'_menu_item_xfn',''),(184,95,'_menu_item_url',''),(194,86,'_edit_lock','1455037915:2');
/*!40000 ALTER TABLE `wpdb_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_posts`
--

DROP TABLE IF EXISTS `wpdb_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `post_name` (`post_name`(191))
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_posts`
--

LOCK TABLES `wpdb_posts` WRITE;
/*!40000 ALTER TABLE `wpdb_posts` DISABLE KEYS */;
INSERT INTO `wpdb_posts` VALUES (1,2,'2012-08-28 15:52:15','2012-08-28 15:52:15','Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!','Hello world!','','publish','open','open','','hello-world','','','2012-08-28 15:52:15','2012-08-28 15:52:15','',0,'http://wordpress.bbyrne.cshp.co//cms/?p=1',0,'post','',1),(2,2,'2012-08-28 15:52:15','2012-08-28 19:52:15','This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with a<strong>n About page that introduces them to potential site visitors. It might say so</strong>mething like this:\r\n<blockquote>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my blog. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</blockquote>\r\n...or something like this:\r\n<blockquote>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickies to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</blockquote>\r\nAs a new WordPress user, you should go to <a href=\"http://wordpress.bbyrne.cshp.co//cms/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!','Sample Page','','publish','open','closed','','sample-page','','','2015-03-20 10:01:40','2015-03-20 14:01:40','',0,'http://wordpress.bbyrne.cshp.co//cms/?page_id=2',0,'page','',0),(6,2,'2012-11-14 15:19:41','2012-11-14 20:19:41','<p><strong>Stuff here.</strong></p>\r\n','A Second Page','','publish','open','closed','','a-second-page','','','2014-01-09 22:24:58','2014-01-10 03:24:58','',0,'http://wordpress.bbyrne.cshp.co//?page_id=6',0,'page','',0),(9,2,'2012-11-14 15:21:08','2012-11-14 22:21:08',' ','','','publish','open','open','','9','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',2,'http://wordpress.bbyrne.cshp.co//?p=9',5,'nav_menu_item','',0),(10,2,'2012-11-14 15:21:08','2012-11-14 22:21:08',' ','','','publish','open','open','','10','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',0,'http://wordpress.bbyrne.cshp.co//?p=10',7,'nav_menu_item','',0),(11,2,'2012-11-14 15:21:08','2012-11-14 22:21:08','','Menu Item','','publish','open','open','','menu-item','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',0,'http://wordpress.bbyrne.cshp.co//?p=11',8,'nav_menu_item','',0),(86,2,'2015-01-20 09:26:37','2015-01-20 14:26:37','a:6:{s:8:\"location\";a:1:{i:0;a:1:{i:0;a:3:{s:5:\"param\";s:9:\"post_type\";s:8:\"operator\";s:2:\"==\";s:5:\"value\";s:4:\"page\";}}}s:8:\"position\";s:4:\"side\";s:5:\"style\";s:7:\"default\";s:15:\"label_placement\";s:3:\"top\";s:21:\"instruction_placement\";s:5:\"label\";s:14:\"hide_on_screen\";a:0:{}}','Page Options','page-options','publish','closed','open','','group_54be659dbce70','','','2015-01-20 09:26:37','2015-01-20 14:26:37','',0,'http://wordpress.bbyrne.cshp.co/?post_type=acf-field-group&p=86',0,'acf-field-group','',0),(14,2,'2012-11-15 09:44:53','2012-11-15 16:44:53','','New Page!','','publish','open','open','','new-page','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',0,'http://wordpress.bbyrne.cshp.co//?p=14',4,'nav_menu_item','',0),(21,2,'2012-12-03 18:36:34','2012-12-04 01:36:34','','Home','','publish','open','open','','home','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',0,'http://wordpress.bbyrne.cshp.co//?p=21',2,'nav_menu_item','',0),(22,2,'2012-12-03 18:36:34','2012-12-04 01:36:34',' ','','','publish','open','open','','22','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',0,'http://wordpress.bbyrne.cshp.co//?p=22',1,'nav_menu_item','',0),(23,2,'2012-12-03 18:36:34','2012-12-04 01:36:34',' ','','','publish','open','open','','23','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',2,'http://wordpress.bbyrne.cshp.co//?p=23',3,'nav_menu_item','',0),(24,2,'2012-12-03 18:36:34','2012-12-04 01:36:34',' ','','','publish','open','open','','24','','','2012-12-03 18:36:34','2012-12-04 01:36:34','',0,'http://wordpress.bbyrne.cshp.co//?p=24',6,'nav_menu_item','',0),(47,2,'2013-06-21 16:41:19','2013-06-21 20:41:19','The page that lists blog posts. This text will not be visible.','Posts','','publish','open','open','','posts','','','2013-06-21 16:41:19','2013-06-21 20:41:19','',0,'http://wordpress.bbyrne.cshp.co/?page_id=47',0,'page','',0),(45,2,'2013-06-21 16:41:00','2013-06-21 20:41:00','The site front page. Ero hendip aliqui turpis coreet, quatums vivamus fames elesto ver dolenim eleifend euisi ultricies. Patin delendipis corperc modignis faciduisit cubilia pulvinar, loborper hendigna vulputate iustrud exerostin iliquat. Essequis delendipis mincidunt etuerci dolobore alit. Lorem acipsus imperdiet feui lobore nullandrem nullaor. Cor adion molestie varius dignissequis sendio erciduis. Lobore hac exeriure commolum laoreet pratisl luptat. Aliquis psusto adiat volenisis ligula. Hent cilit elit utpationse iustrud sollicitudin cursus rutrum esequis, utetumm sit velessim.\r\n\r\n<a href=\"http://google.com\">Porta</a> od venenatis tio commolum, esse vulluptatum duisi niamcon loborper augiat eliquatum ullan odio. Hendigna vent eget congue dolobor quamconsequi nonsequ morbi; irilla adignit veliqui feugue. Tellus dolorer hac corperc velesse verostin modipsu, nam te deliquatue, torquent inim posuere leo. Dipiscipit sum loreetue nec quisi, sollicitudin quatet lum coreetue dignissimisl nonum modignis commodolor andrero. Loborper ullamcorper ilit erostin facilisis xer. Aciliqu tatumsan atueros inceptos velenis facing aciliquis duismolobore metus feum facipsum. Dignissim dolobore conubia quatuer vitae, placerat vercilisit molore.','Home','','publish','open','open','','home','','','2014-07-07 13:56:22','2014-07-07 17:56:22','',0,'http://wordpress.bbyrne.cshp.co/?page_id=45',0,'page','',0),(49,2,'2013-06-24 17:07:05','2013-06-24 21:07:05','','Page Options','','publish','closed','closed','','acf_page-options','','','2013-06-24 17:07:05','2013-06-24 21:07:05','',0,'http://wordpress.bbyrne.cshp.co/?post_type=acf&#038;p=49',0,'acf','',0),(89,2,'2015-03-20 09:57:16','2015-03-20 13:57:16',' ','','','publish','closed','open','','89','','','2015-03-20 09:57:16','2015-03-20 13:57:16','',0,'http://wordpress.bbyrne.cshp.co/?p=89',1,'nav_menu_item','',0),(90,2,'2015-03-20 09:57:16','2015-03-20 13:57:16',' ','','','publish','closed','open','','90','','','2015-03-20 09:57:16','2015-03-20 13:57:16','',0,'http://wordpress.bbyrne.cshp.co/?p=90',2,'nav_menu_item','',0),(91,2,'2015-03-20 09:57:16','2015-03-20 13:57:16',' ','','','publish','closed','open','','91','','','2015-03-20 09:57:16','2015-03-20 13:57:16','',0,'http://wordpress.bbyrne.cshp.co/?p=91',3,'nav_menu_item','',0),(94,2,'2015-03-20 09:57:42','2015-03-20 13:57:42',' ','','','publish','closed','open','','94','','','2015-04-30 15:38:28','2015-04-30 19:38:28','',0,'http://wordpress.bbyrne.cshp.co/?p=94',2,'nav_menu_item','',0),(95,2,'2015-03-20 09:57:42','2015-03-20 13:57:42',' ','','','publish','closed','open','','95','','','2015-04-30 15:38:28','2015-04-30 19:38:28','',0,'http://wordpress.bbyrne.cshp.co/?p=95',1,'nav_menu_item','',0),(99,2,'2015-04-30 15:38:28','2015-04-30 19:38:28',' ','','','publish','closed','open','','99','','','2015-04-30 15:38:28','2015-04-30 19:38:28','',0,'http://wordpress.bbyrne.cshp.co/?p=99',3,'nav_menu_item','',0),(118,2,'2016-06-22 15:42:47','0000-00-00 00:00:00','','Auto Draft','','auto-draft','closed','open','','','','','2016-06-22 15:42:47','0000-00-00 00:00:00','',0,'http://taube2.danny.cshp.co/?p=118',0,'post','',0);
/*!40000 ALTER TABLE `wpdb_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_form`
--

DROP TABLE IF EXISTS `wpdb_rg_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_form` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `date_created` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_form`
--

LOCK TABLES `wpdb_rg_form` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_form_meta`
--

DROP TABLE IF EXISTS `wpdb_rg_form_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_form_meta` (
  `form_id` mediumint(8) unsigned NOT NULL,
  `display_meta` longtext,
  `entries_grid_meta` longtext,
  `confirmations` longtext,
  `notifications` longtext,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_form_meta`
--

LOCK TABLES `wpdb_rg_form_meta` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_form_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_form_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_form_view`
--

DROP TABLE IF EXISTS `wpdb_rg_form_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_form_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` mediumint(8) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `ip` char(15) DEFAULT NULL,
  `count` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_form_view`
--

LOCK TABLES `wpdb_rg_form_view` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_form_view` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_form_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_incomplete_submissions`
--

DROP TABLE IF EXISTS `wpdb_rg_incomplete_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_incomplete_submissions` (
  `uuid` char(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `form_id` mediumint(8) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `ip` varchar(39) NOT NULL,
  `source_url` longtext NOT NULL,
  `submission` longtext NOT NULL,
  PRIMARY KEY (`uuid`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_incomplete_submissions`
--

LOCK TABLES `wpdb_rg_incomplete_submissions` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_incomplete_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_incomplete_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_lead`
--

DROP TABLE IF EXISTS `wpdb_rg_lead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_lead` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` mediumint(8) unsigned NOT NULL,
  `post_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `is_starred` tinyint(1) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(39) NOT NULL,
  `source_url` varchar(200) NOT NULL DEFAULT '',
  `user_agent` varchar(250) NOT NULL DEFAULT '',
  `currency` varchar(5) DEFAULT NULL,
  `payment_status` varchar(15) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_amount` decimal(19,2) DEFAULT NULL,
  `payment_method` varchar(30) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `is_fulfilled` tinyint(1) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `transaction_type` tinyint(1) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_lead`
--

LOCK TABLES `wpdb_rg_lead` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_lead` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_lead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_lead_detail`
--

DROP TABLE IF EXISTS `wpdb_rg_lead_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_lead_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `form_id` mediumint(8) unsigned NOT NULL,
  `field_number` float NOT NULL,
  `value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `lead_id` (`lead_id`),
  KEY `lead_field_number` (`lead_id`,`field_number`),
  KEY `lead_field_value` (`value`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_lead_detail`
--

LOCK TABLES `wpdb_rg_lead_detail` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_lead_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_lead_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_lead_detail_long`
--

DROP TABLE IF EXISTS `wpdb_rg_lead_detail_long`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_lead_detail_long` (
  `lead_detail_id` bigint(20) unsigned NOT NULL,
  `value` longtext,
  PRIMARY KEY (`lead_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_lead_detail_long`
--

LOCK TABLES `wpdb_rg_lead_detail_long` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_lead_detail_long` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_lead_detail_long` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_lead_meta`
--

DROP TABLE IF EXISTS `wpdb_rg_lead_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_lead_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lead_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `meta_key` (`meta_key`(191)),
  KEY `form_id_meta_key` (`form_id`,`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_lead_meta`
--

LOCK TABLES `wpdb_rg_lead_meta` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_lead_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_lead_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_rg_lead_notes`
--

DROP TABLE IF EXISTS `wpdb_rg_lead_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_rg_lead_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `value` longtext,
  `note_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `lead_user_key` (`lead_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_rg_lead_notes`
--

LOCK TABLES `wpdb_rg_lead_notes` WRITE;
/*!40000 ALTER TABLE `wpdb_rg_lead_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_rg_lead_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_term_relationships`
--

DROP TABLE IF EXISTS `wpdb_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_term_relationships`
--

LOCK TABLES `wpdb_term_relationships` WRITE;
/*!40000 ALTER TABLE `wpdb_term_relationships` DISABLE KEYS */;
INSERT INTO `wpdb_term_relationships` VALUES (1,2,0),(2,2,0),(3,2,0),(4,2,0),(5,2,0),(6,2,0),(7,2,0),(1,1,0),(9,3,0),(10,3,0),(11,3,0),(14,3,0),(22,3,0),(21,3,0),(23,3,0),(24,3,0),(89,28,0),(90,28,0),(91,28,0),(99,29,0),(94,29,0),(95,29,0);
/*!40000 ALTER TABLE `wpdb_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_term_taxonomy`
--

DROP TABLE IF EXISTS `wpdb_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_term_taxonomy`
--

LOCK TABLES `wpdb_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wpdb_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wpdb_term_taxonomy` VALUES (1,1,'category','',0,1),(2,2,'link_category','',0,7),(28,28,'nav_menu','',0,3),(29,29,'nav_menu','',0,3);
/*!40000 ALTER TABLE `wpdb_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_termmeta`
--

DROP TABLE IF EXISTS `wpdb_termmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_termmeta`
--

LOCK TABLES `wpdb_termmeta` WRITE;
/*!40000 ALTER TABLE `wpdb_termmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdb_termmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_terms`
--

DROP TABLE IF EXISTS `wpdb_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_terms`
--

LOCK TABLES `wpdb_terms` WRITE;
/*!40000 ALTER TABLE `wpdb_terms` DISABLE KEYS */;
INSERT INTO `wpdb_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'Blogroll','blogroll',0),(28,'Main Menu','main-menu',0),(29,'Bottom Menu','bottom-menu',0);
/*!40000 ALTER TABLE `wpdb_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_usermeta`
--

DROP TABLE IF EXISTS `wpdb_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_usermeta`
--

LOCK TABLES `wpdb_usermeta` WRITE;
/*!40000 ALTER TABLE `wpdb_usermeta` DISABLE KEYS */;
INSERT INTO `wpdb_usermeta` VALUES (1,2,'first_name','Cornershop'),(2,2,'last_name','Creative'),(3,2,'nickname','Cornershop'),(4,2,'description',''),(5,2,'rich_editing','true'),(6,2,'comment_shortcuts','false'),(7,2,'admin_color','fresh'),(8,2,'use_ssl','0'),(9,2,'show_admin_bar_front','true'),(10,2,'wpdb_capabilities','a:1:{s:13:\"administrator\";s:1:\"1\";}'),(11,2,'wpdb_user_level','10'),(12,2,'dismissed_wp_pointers','wp330_toolbar,wp330_media_uploader,wp330_saving_widgets,wp340_choose_image_from_library,wp340_customize_current_theme_link,theme-options-layouts,suf-section-tabs-visual-effects,suf-section-tabs-sidebar-setup,wp350_media,disqus_settings_pointer,wp390_widgets'),(13,2,'show_welcome_panel','0'),(14,2,'wpdb_dashboard_quick_press_last_post_id','118'),(15,2,'closedpostboxes_dashboard','a:0:{}'),(16,2,'metaboxhidden_dashboard','a:4:{i:0;s:18:\"dashboard_activity\";i:1;s:20:\"pb_backupbuddy_stats\";i:2;s:21:\"dashboard_quick_press\";i:3;s:17:\"dashboard_primary\";}'),(17,2,'wpdb_user-settings','libraryContent=browse&hidetb=1&align=none&imgsize=full&urlbutton=none&editor=tinymce&mfold=o&wplink=1'),(18,2,'wpdb_user-settings-time','1427206382'),(19,2,'managenav-menuscolumnshidden','a:4:{i:0;s:11:\"link-target\";i:1;s:11:\"css-classes\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";}'),(20,2,'metaboxhidden_nav-menus','a:3:{i:0;s:8:\"add-post\";i:1;s:12:\"add-post_tag\";i:2;s:15:\"add-post_format\";}'),(21,2,'nav_menu_recently_edited','29'),(52,2,'_yoast_wpseo_profile_updated','1426859758'),(54,2,'session_tokens','a:1:{s:64:\"5c7f3e5e075cc8a408d68e6f7a75fb3bfe8084f7838d419a019018b54b4369cf\";a:4:{s:10:\"expiration\";i:1466797366;s:2:\"ip\";s:14:\"108.35.133.183\";s:2:\"ua\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36\";s:5:\"login\";i:1466624566;}}'),(55,2,'aim',''),(56,2,'yim',''),(57,2,'jabber','');
/*!40000 ALTER TABLE `wpdb_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpdb_users`
--

DROP TABLE IF EXISTS `wpdb_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpdb_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdb_users`
--

LOCK TABLES `wpdb_users` WRITE;
/*!40000 ALTER TABLE `wpdb_users` DISABLE KEYS */;
INSERT INTO `wpdb_users` VALUES (2,'cornershop','$P$BLs5FNdPXuvuNq3v/7MxsxgdDE.Ywa0','cornershop','devs@cornershopcreative.com','https://cornershopcreative.com','2012-08-28 15:52:15','',0,'Cornershop Creative');
/*!40000 ALTER TABLE `wpdb_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-27 15:26:34
