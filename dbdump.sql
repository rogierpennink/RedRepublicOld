-- Table structure for table `accounts`
DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` bigint(20) NOT NULL auto_increment,
  `username` varchar(20) collate latin1_general_ci NOT NULL default '',
  `password` varchar(255) collate latin1_general_ci NOT NULL default '',
  `person_id` bigint(20) NOT NULL default '0',
  `character_id` bigint(20) NOT NULL default '0',
  `rights` int(11) NOT NULL default '0',
  `last_active` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `accounts`
insert into `accounts` values
(1,'Roger','9ed1f88378d23c61526e482bfde3a3bc',1,2,10,1195688049),
(2,'Tybalt','758ebb61e052f1e07c459b883bd94f34',7,0,0,1171365536),
(3,'kali','5fc8016afd54f3a75c8911ab23546906',8,13,0,1190422427),
(11,'FrankieAngel','a2a48846fec1a72ee7ae64d48cd83aa9',16,23,0,1174999102),
(9,'MichaelErdemi','469098e537b96e7e87e3196b49a9f299',14,9,0,1178743110),
(10,'Freeii','de3176dcf0f6dbcc3ccd7e02bb9d08fd',15,10,0,1191313866),
(8,'133snake','ed1e3c2b967f7dba44306a8942c9e05c',13,0,0,1171373469),
(12,'BillyThaKid','bab26a9580a254aa45669ea76b324834',17,0,0,1171402926),
(13,'Gotti','c25a68128b55eab863ac1bfcfbb4c80a',18,19,0,1173883272),
(14,'Xanther','5f4dcc3b5aa765d61d8327deb882cf99',19,3,5,1191497735),
(17,'Scott','9631bef480feff4153f8ae1d3f315f0a',22,20,0,1188703495),
(16,'Azwraith','bc156d8f3b2fd6d3cce0f101ffb2685c',21,0,0,1173694759),
(18,'MrTinketi','29a58e7e3df1b451bd07997c2293fcce',23,28,0,1191098108),
(19,'GodlyDeivl','a14800dbb3c9467c1f284a1b119193d7',24,0,0,0),
(20,'Maluco','315eb115d98fcbad39ffc5edebd669c9',25,24,0,1190989295),
(21,'KroniK','c43c92a69de61c8a138bc2caa872078e',26,4,0,1188603581),
(22,'Stew','b35f6e5050f1bc7d4aefb08cb3d10247',27,17,0,1180996192),
(35,'ronjones','94e28b748b0be23eea02210d11fd446e',40,8,0,1172556331),
(36,'Euphorikal','67b37f93051794d335878e3e2f362224',41,11,0,1176205929),
(37,'Dorrith','d6fa97682374912366246deb406d42f1',42,0,0,1172768426),
(38,'Spjutulf','29f491121c63af2a883378c50e1f8d9f',43,12,0,1172871672),
(33,'Kaheed','bebda45a566414022f28c7588365379f',38,6,0,1173253821),
(34,'Noberto','83016b775a8f1fc92addc3c1b4ddbd75',39,7,0,1172496230),
(32,'bugsy','60701833c419b8d0adc4e17f7ecea05c',37,5,0,1172074016),
(39,'Gabber','72aad66c14ea6506f7fbc48debfa4432',44,0,0,1173174019),
(40,'MadMan','bfcdd0ee062d2febf8264fbf290aa1c3',45,14,0,1173183017),
(41,'Aaron','7ddf32e17a6ac5ce04a8ecbf782ca509',46,15,10,1196550540),
(42,'PaulVitti','715e769bc94074d3a81acf399b2002a5',47,16,0,1173224468),
(43,'Soprano','7a5696e2a31f7b88a8fd0886b5a69f46',48,22,0,1175457179),
(44,'Noor','be3780a4ad33608e07d60c2977ac715a',49,18,0,1173729428),
(45,'Sander','5f4dcc3b5aa765d61d8327deb882cf99',50,21,0,1174468302),
(46,'Epix','84427c083f0691d53b4179a309e72cea',51,25,0,1178871162),
(47,'heroCaesar','7589b1767a6705cbd419fcc797b29fd4',52,31,0,1191396316),
(48,'Phase','efead51028a03db2d63f0e79ba032a82',53,29,0,1190880927),
(49,'Shade','a267acf656f8fab98e87efa7d9f49605',54,30,0,1189883515),
(50,'impAct','47a7bb2e652b0f6cb28cf7e185660024',55,0,0,0),
(51,'Cryst4l','d91c4118abdfe526a3fe5a75fcfe3bcc',56,32,0,1188598731),
(52,'EchO','6b2be94c103c8b7f494a7fdb5d347fa1',57,33,0,1190881024),
(53,'Tester','28b566f45462f2ea9ed820f90eb40305',58,34,0,1195680007),
(54,'Sneo','c6f1f533ddb13eacbebb1d81f0278ab4',59,35,10,1190838352),
(55,'Sneo2','c6f1f533ddb13eacbebb1d81f0278ab4',60,36,0,1189444636),
(60,'Habaal','75c7cb58a62a4c6a1f24abeaafea1e54',64,46,0,1196465586);

-- Table structure for table `announce`
DROP TABLE IF EXISTS `announce`;

CREATE TABLE `announce` (
  `account_id` bigint(20) NOT NULL default '0',
  `remain_until` datetime NOT NULL default '0000-00-00 00:00:00',
  `announce_type` varchar(225) default '0',
  `announce_text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Table structure for table `auctionhouse`
DROP TABLE IF EXISTS `auctionhouse`;

CREATE TABLE `auctionhouse` (
  `business_id` bigint(20) NOT NULL default '0',
  `auction_fee` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `auctionhouse`
insert into `auctionhouse` values
(17,5),
(31,5),
(40,5);

-- Table structure for table `auctions`
DROP TABLE IF EXISTS `auctions`;

CREATE TABLE `auctions` (
  `auction_id` bigint(20) NOT NULL auto_increment,
  `item_id` bigint(20) NOT NULL default '0',
  `seller` bigint(20) NOT NULL default '0',
  `current_bid` bigint(20) NOT NULL default '0',
  `current_bidder` bigint(20) NOT NULL default '0',
  `buyout` bigint(20) NOT NULL default '0',
  `close_time` bigint(20) NOT NULL default '0',
  `business_id` bigint(20) NOT NULL default '0',
  `location_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `bank_accounts`
DROP TABLE IF EXISTS `bank_accounts`;

CREATE TABLE `bank_accounts` (
  `account_number` bigint(20) NOT NULL default '0',
  `owner_id` bigint(20) NOT NULL default '0',
  `account_type` tinyint(4) NOT NULL default '0',
  `account_status` tinyint(4) NOT NULL default '0',
  `balance` bigint(20) NOT NULL default '0',
  `business_id` bigint(20) NOT NULL default '0',
  `firstname` varchar(255) collate latin1_general_ci NOT NULL default '',
  `lastname` varchar(255) collate latin1_general_ci NOT NULL default '',
  `curr_employee` bigint(20) NOT NULL,
  PRIMARY KEY  (`account_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `bank_accounts`
insert into `bank_accounts` values
(7272855,2,0,2,222471,15,'Roger','Johns',0),
(4583313,15,0,2,5000,15,'Aaron','Amann',0),
(5619127,3,0,2,1043274,15,'Xanther','Vancuion',0),
(3598857,29,0,2,434798,15,'Harry','Thorne',0),
(9610274,0,0,2,473143,0,'','',0),
(7417073,0,0,2,143495,0,'','',0),
(3789056,31,0,2,0,15,'Finn','Pennink',0),
(5883754,30,0,2,8553,15,'Jack','Vilshade',0),
(3717632,2,1,2,587000,15,'Roger','Johns',2),
(3822803,0,0,2,105952,0,'','',0),
(4701413,28,0,2,11932,15,'Mister','Tinketi',0),
(2988667,13,1,2,0,15,'kali','lenuzza',0),
(3169570,3,1,2,7000,15,'Xanther','Chan',0),
(8607796,13,0,2,8701,15,'kali','lenuzza',0),
(9231557,13,2,2,0,15,'kali','lenuzza',0),
(3698625,35,0,2,10000,15,'Sean','McNamara',0),
(6739940,31,2,2,5000,15,'Finn','Pennink',0),
(3621097,0,0,2,110,0,'','',0),
(6405734,3,2,2,8940,15,'Xanther','Chan',0),
(2374805,10,0,2,8213,15,'Eleonore','Vito',0),
(6874403,0,0,2,0,0,'','',0),
(3327819,0,0,2,0,15,'','',0),
(8039153,0,0,2,0,0,'','',0),
(7433882,0,0,2,0,0,'','',0),
(3471217,0,0,2,0,0,'','',0),
(4125453,0,0,2,0,0,'','',0),
(7534600,0,0,2,0,0,'','',0),
(2592967,0,0,2,0,0,'','',0),
(6598235,0,0,2,0,0,'','',0),
(8490366,0,0,2,0,0,'','',0),
(4811127,0,0,2,0,0,'','',0),
(4424413,0,0,2,0,0,'','',0),
(9417452,0,0,2,0,0,'','',0),
(9918786,0,0,2,0,0,'','',0),
(7629871,0,0,2,0,0,'','',0),
(9929518,0,0,2,0,0,'','',0),
(8430140,0,0,2,0,0,'','',0),
(6048292,0,0,2,0,0,'','',0),
(9460431,0,0,2,0,0,'','',0);

-- Table structure for table `bank_loans`
DROP TABLE IF EXISTS `bank_loans`;

CREATE TABLE `bank_loans` (
  `loan_id` bigint(20) NOT NULL auto_increment,
  `char_id` bigint(20) NOT NULL,
  `employee` bigint(20) NOT NULL,
  `loan_status` int(11) NOT NULL,
  `loan_amount` bigint(20) NOT NULL,
  `loan_repaid` bigint(20) NOT NULL,
  `repay_time` bigint(20) NOT NULL,
  `pledge` bigint(20) NOT NULL,
  `rent` int(11) NOT NULL,
  `num_warnings` int(11) NOT NULL,
  `case_notes` text collate latin1_general_ci NOT NULL,
  `business_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`loan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Pledge = item_id';

-- dumping data for table `bank_loans`
insert into `bank_loans` values
(2,2,0,10,21599,23759,1195077144,30,10,1,'13/11/2007 23:22:55 - Loan requested by Roger\r\n13/11/2007 23:34:14 - Loan accepted by Junior Teller Roger.\r\n14/11/2007 00:00:43 - Loan returned by Junior Teller Roger.\r\n14/11/2007 01:30:41 - Loan returned by Junior Teller Roger.\r\n14/11/2007 14:34:17 - An amount of $23000 was repaid by Roger!\r\n14/11/2007 14:34:35 - An amount of $758 was repaid by Roger!\r\n14/11/2007 14:35:16 - An amount of $1 was repaid by Roger!\n20/11/2007 11:14:57 - Loan #2 was archived by Bank President Roger.\n20/11/2007 11:21:50 - Loan returned by Bank President Roger.',15),
(3,2,0,10,10000,11000,88407,0,10,0,'20/11/2007 11:26:04 - Loan requested by Roger\r\n20/11/2007 11:26:23 - An amount of $10000 was repaid by Roger!\r\n20/11/2007 11:27:02 - Loan accepted by Bank President Roger.\r\n20/11/2007 11:27:50 - An amount of $1000 was repaid by Roger!\r\n20/11/2007 11:28:25 - Loan returned by Bank President Roger.\n20/11/2007 11:28:36 - Loan #3 was archived by Bank President Roger.',15),
(4,2,0,10,15000,16500,88407,0,10,0,'20/11/2007 11:30:55 - Loan requested by Roger\r\n21/11/2007 00:35:35 - Loan accepted by Bank President Roger.\r\n21/11/2007 00:38:08 - An amount of $16500 was repaid by Roger!\n21/11/2007 00:38:32 - Loan #4 was archived by Bank President Roger.',15),
(5,2,0,10,15000,16500,88407,0,10,0,'21/11/2007 00:38:54 - Loan requested by Roger\r\n21/11/2007 00:39:16 - Loan accepted by Bank President Roger.\r\n21/11/2007 00:39:37 - An amount of $16500 was repaid by Roger!\n21/11/2007 00:41:35 - Loan #5 was archived by Bank President Roger.',15);

-- Table structure for table `bank_settings`
DROP TABLE IF EXISTS `bank_settings`;

CREATE TABLE `bank_settings` (
  `business_id` bigint(20) NOT NULL default '0',
  `savings_rent` int(11) NOT NULL default '0',
  `loanrate` int(11) NOT NULL default '2500',
  `loan_period` int(11) NOT NULL default '1',
  `deposit_fee` int(11) NOT NULL default '0',
  `withdrawal_fee` int(11) NOT NULL default '0',
  `transfer_fee` int(11) NOT NULL default '0',
  `buffer_size` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `bank_settings`
insert into `bank_settings` values
(13,25,2500,0,3,3,3,0),
(15,50,1000,1,3,3,3,50000),
(39,25,2500,1,3,3,3,0);

-- Table structure for table `bank_transactions`
DROP TABLE IF EXISTS `bank_transactions`;

CREATE TABLE `bank_transactions` (
  `account_number` bigint(20) NOT NULL default '0',
  `transaction_type` int(11) NOT NULL default '0',
  `amount` bigint(20) NOT NULL default '0',
  `fee` int(11) NOT NULL default '0',
  `balance` bigint(20) NOT NULL default '0',
  `to_player` bigint(20) NOT NULL default '0',
  `from_player` bigint(20) NOT NULL default '0',
  `datetime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `bank_transactions`
insert into `bank_transactions` values
(7272855,0,200000,6000,326240,0,0,'2007-08-28 15:19:42'),
(7272855,0,1000,30,327240,0,0,'2007-08-28 15:23:14'),
(7272855,2,10,0,327230,3598857,0,'2007-08-28 15:53:09'),
(3598857,3,10,0,9320,0,7272855,'2007-08-28 15:53:09'),
(3598857,2,10,0,9310,7272855,0,'2007-08-28 15:55:17'),
(7272855,3,10,0,327240,0,3598857,'2007-08-28 15:55:17'),
(3598857,1,9310,279,0,0,0,'2007-08-28 15:56:21'),
(5619127,0,151,5,9879,0,0,'2007-08-28 22:49:02'),
(7272855,2,100,3,327137,5619127,0,'2007-08-29 05:05:09'),
(5619127,3,100,0,9979,0,7272855,'2007-08-29 05:05:09'),
(5619127,2,100,3,9876,7272855,0,'2007-08-29 05:08:52'),
(7272855,3,100,0,327237,0,5619127,'2007-08-29 05:08:52'),
(5619127,0,3538,109,13414,0,0,'2007-08-29 05:30:48'),
(5619127,0,3685,114,17099,0,0,'2007-08-29 07:18:57'),
(3789056,1,3000,90,2000,0,0,'2007-08-29 09:11:32'),
(5619127,2,1,0,17098,7272855,0,'2007-08-29 09:12:25'),
(7272855,3,1,0,327238,0,5619127,'2007-08-29 09:12:25'),
(7272855,2,25000,750,301488,3789056,0,'2007-08-29 09:13:57'),
(3789056,3,25000,0,26910,0,7272855,'2007-08-29 09:13:57'),
(3789056,0,3928,121,30838,0,0,'2007-08-29 09:15:08'),
(7272855,2,20000,600,280888,5883754,0,'2007-08-29 13:20:19'),
(5883754,3,20000,0,25000,0,7272855,'2007-08-29 13:20:19'),
(5883754,1,10000,300,15000,0,0,'2007-08-29 13:21:41'),
(3789056,0,29,1,30867,0,0,'2007-08-30 07:09:33'),
(3789056,1,3900,117,26967,0,0,'2007-08-30 08:15:34'),
(7272855,1,20000,600,260888,0,0,'2007-08-30 10:23:18'),
(5619127,1,17098,513,0,0,0,'2007-08-30 10:39:29'),
(5619127,0,6051,187,6051,0,0,'2007-08-30 10:42:07'),
(5883754,1,10000,300,4700,0,0,'2007-08-30 17:26:38'),
(5883754,0,4153,128,8553,0,0,'2007-08-30 17:31:35'),
(5619127,1,3000,90,3051,0,0,'2007-08-31 05:27:56'),
(5619127,0,1174553,36326,1177514,0,0,'2007-08-31 07:45:16'),
(3717632,0,582000,18000,587000,0,0,'2007-08-31 09:03:31'),
(3598857,0,500000,15000,500000,0,0,'2007-09-01 00:23:28'),
(3789056,1,4999,150,21851,0,0,'2007-09-01 03:32:54'),
(3789056,1,1,0,21700,0,0,'2007-09-01 03:34:23'),
(3789056,1,1,0,21699,0,0,'2007-09-01 03:34:41'),
(3789056,1,9998,300,11701,0,0,'2007-09-01 03:34:59'),
(5619127,1,7000,210,1170514,0,0,'2007-09-01 09:26:51'),
(7272855,1,10000,300,250288,0,0,'2007-09-01 09:27:59'),
(3598857,1,30000,900,470000,0,0,'2007-09-01 15:43:51'),
(3598857,0,14550,450,483650,0,0,'2007-09-01 15:50:26'),
(3598857,1,60000,1800,423650,0,0,'2007-09-01 16:22:03'),
(3598857,0,54000,1620,475850,0,0,'2007-09-01 16:22:10'),
(3789056,1,6373,191,5028,0,0,'2007-09-02 09:04:50'),
(3789056,0,4079,126,8916,0,0,'2007-09-02 09:08:51'),
(7272855,1,5000,150,244988,0,0,'2007-09-02 18:46:18'),
(3598857,0,2840,88,478690,0,0,'2007-09-02 19:41:55'),
(3789056,1,8913,267,3,0,0,'2007-09-03 04:54:22'),
(3789056,1,3,0,0,0,0,'2007-09-03 04:54:40'),
(3598857,1,30000,900,448690,0,0,'2007-09-03 08:16:50'),
(3598857,0,18330,567,466120,0,0,'2007-09-03 08:39:56'),
(5619127,1,10000,300,1160304,0,0,'2007-09-04 04:28:22'),
(2988667,2,4800,0,200,8607796,0,'2007-09-04 07:03:38'),
(8607796,3,4800,0,9800,0,2988667,'2007-09-04 07:03:38'),
(2988667,2,200,0,0,8607796,0,'2007-09-04 07:05:05'),
(8607796,3,200,0,10000,0,2988667,'2007-09-04 07:05:05'),
(9231557,2,5000,0,0,8607796,0,'2007-09-04 09:50:37'),
(8607796,3,5000,0,15000,0,9231557,'2007-09-04 09:50:37'),
(8607796,1,15000,450,0,0,0,'2007-09-04 09:50:55'),
(7272855,1,3000,90,241838,0,0,'2007-09-05 03:18:44'),
(5619127,1,1000,30,1159004,0,0,'2007-09-06 00:49:48'),
(7272855,1,15000,450,226748,0,0,'2007-09-06 18:32:01'),
(7272855,0,10301,319,236599,0,0,'2007-09-06 18:56:34'),
(5619127,1,50000,1500,1108974,0,0,'2007-09-07 02:03:55'),
(7272855,1,13000,390,223599,0,0,'2007-09-07 16:00:16'),
(8607796,0,9010,279,9010,0,0,'2007-09-10 04:49:38'),
(3598857,1,30000,900,436120,0,0,'2007-09-15 04:01:16'),
(3598857,1,424360,12000,35220,0,0,'2007-09-17 14:01:43'),
(3598857,0,421578,13038,444798,0,0,'2007-09-17 14:02:08'),
(5619127,1,600000,18000,507474,0,0,'2007-09-21 02:50:06'),
(5619127,0,600000,18000,1089474,0,0,'2007-09-21 02:51:29'),
(8607796,1,300,9,8710,0,0,'2007-09-21 20:52:11'),
(5619127,1,35000,1050,1054474,0,0,'2007-09-23 21:49:27'),
(5619127,1,5000,150,1048424,0,0,'2007-09-27 05:12:57'),
(2374805,0,197,6,5575,0,0,'2007-09-27 14:14:35'),
(2374805,0,218,7,5793,0,0,'2007-09-27 14:15:31'),
(2374805,0,304,9,6097,0,0,'2007-09-28 16:13:42'),
(4701413,0,1630,50,11932,0,0,'2007-09-29 15:50:16'),
(2374805,0,266,8,6363,0,0,'2007-10-01 03:27:21'),
(2374805,0,1455,45,7818,0,0,'2007-10-02 04:18:11'),
(2374805,0,263,8,8081,0,0,'2007-10-02 04:19:33'),
(2374805,0,182,6,8263,0,0,'2007-10-02 04:26:21'),
(7272855,4,5000,0,213209,0,7272855,'2007-10-10 00:28:34'),
(7272855,4,5000,0,208209,3327819,7272855,'2007-10-10 00:35:27'),
(3598857,5,-10000,0,434798,7272855,0,'0000-00-00 00:00:00'),
(7272855,5,10000,0,7282855,0,3598857,'0000-00-00 00:00:00'),
(5619127,5,-5000,0,1043274,7272855,0,'2007-10-21 23:43:14'),
(7272855,5,5000,0,223259,0,5619127,'2007-10-21 23:43:14'),
(7272855,2,758,0,222501,7417073,0,'2007-11-14 14:34:35'),
(7272855,2,30,0,222471,7417073,0,'2007-11-14 14:42:27'),
(7417073,3,30,0,148495,0,7272855,'2007-11-14 14:42:27');

-- Table structure for table `businesses`
DROP TABLE IF EXISTS `businesses`;

CREATE TABLE `businesses` (
  `id` bigint(20) NOT NULL auto_increment,
  `business_type` int(11) NOT NULL default '0',
  `owner_id` bigint(20) NOT NULL default '0',
  `bank_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `icon` varchar(255) collate latin1_general_ci NOT NULL default '',
  `desc` varchar(255) collate latin1_general_ci NOT NULL default '',
  `url` varchar(255) collate latin1_general_ci NOT NULL default '',
  `for_sale` varchar(5) collate latin1_general_ci NOT NULL default 'true',
  `sale_price` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `businesses`
insert into `businesses` values
(1,0,0,0,'The Amsterdam Tavern','','A place to quietly relax and drink a beer or two...','tavern.php','true',100000),
(2,0,0,0,'The Amsterdam Garage','','Cars, parts, and the smell of gasoline...','garage.php','false',0),
(14,2,0,9610274,'The Amsterdam Clothing Shop','','A shop easily identified by the crowd of women drooling at it...','clothingshop.php','true',120000),
(11,5,0,3327819,'The University of Amsterdam','','Students walk in and out of the impressive architecture that makes up the main hall...','university.php','false',0),
(15,6,0,7417073,'The Bank of Amsterdam','','A huge, stately building distinguishes itself from the rest of the street...','bank.php','false',0),
(16,7,0,3822803,'The Amsterdam Weaponshop','','A small shack with blackened windows... Is this place even open?','weaponshop.php','true',150000),
(17,3,0,3621097,'The Amsterdam Auction House','','A huge crowd is gathered here, looking for bargains with shouting salesmen!','auctionhouse.php','true',150000),
(18,8,0,4396184,'The Amsterdam Race Track','','A coliseum devoted to racing cars with hundreds of gamblers inside screaming at their favorite cars.','racetrack.php','true',120000),
(19,9,0,6874403,'The Amsterdam Real Estate Agency','','The walls are covered with beautiful villas, swimming pools and shiny buildings!','realestate.php','false',0),
(20,10,0,8080710,'Amsterdam City Hall','','The large building with huge white columns at the heart of the town. This is where business trades and politics are normally held.','townhall.php','false',0),
(30,0,0,8039153,'The Bank of Berlin','','A huge, stately building distinguishes itself from the rest of the street...','bank.php','false',0),
(29,5,0,7433882,'The College of Berlin','','Students walk in and out of the impressive architecture that makes up the main hall...','university.php','false',0),
(27,4,0,4125453,'The Berlin Garage','','Cars, parts, and the smell of gasoline...','garage.php','false',0),
(28,2,0,3471217,'The Berlin Clothing Store','','A shop easily identified by the crowd of women drooling at it...','clothingshop.php','true',170000),
(26,0,0,7534600,'The Berlin Tavern','','A place to quietly relax and drink a beer or two...','tavern.php','true',150000),
(31,3,0,2592967,'The Berlin Auction House','','A huge crowd is gathered here, looking for bargains with shouting salesmen!','auctionhouse.php','true',210000),
(32,8,0,6598235,'The Berlin Race Track','','A coliseum devoted to racing cars with hundreds of gamblers inside screaming at their favorite cars.','racetrack.php','true',100000),
(33,0,0,8490366,'The Berlin Real Estate Agency','','The walls are covered with beautiful villas, swimming pools and shiny buildings!','realestate.php','false',0),
(34,10,0,4811127,'Berlin Town Hall','','The large building with huge white columns at the heart of the town. This is where business trades and politics are normally held.','townhall.php','false',0),
(35,0,0,4424413,'The Kingston Pub','','A place to quietly relax and drink a beer or two...','tavern.php','true',90000),
(37,2,0,9417452,'The Kingston Clothing Store','','An open market shop crowded with tourists.','clothingshop.php','true',120099),
(38,5,0,9918786,'The University of Kingston','','Students walk in and out of the impressive architecture that makes up the main hall...','university.php','false',0),
(39,6,0,7629871,'The Bank of Kingston','','A huge, stately building distinguishes itself from the rest of the street...','bank.php','false',0),
(40,3,0,9929518,'Kingston Corner Auction House','','A huge crowd is gathered here, looking for bargains with shouting salesmen!','auctionhouse.php','true',140000),
(41,9,0,8430140,'The Kingston Real Estate Agency','','The walls are covered with beautiful villas, swimming pools and shiny buildings!','realestate.php','false',0),
(42,10,0,6048292,'Kingston City Hall','','The large building with huge white columns at the heart of the city. This is where business trades and politics are normally held.','townhall.php','false',0),
(43,11,0,9460431,'The Amsterdam Hotel','','The broken neon sign out front reads Hote-. Drug dealers hang around the entrance and prostitutes the lounge. This isn\'t a very clean place.','hotel.php','true',190000);

-- Table structure for table `char_characters`
DROP TABLE IF EXISTS `char_characters`;

CREATE TABLE `char_characters` (
  `id` bigint(20) NOT NULL auto_increment,
  `account_id` bigint(20) NOT NULL default '0',
  `nickname` varchar(25) collate latin1_general_ci NOT NULL default '',
  `firstname` varchar(25) collate latin1_general_ci NOT NULL default '',
  `lastname` varchar(25) collate latin1_general_ci NOT NULL default '',
  `gender` char(1) collate latin1_general_ci NOT NULL default '',
  `birthdate` date NOT NULL default '0000-00-00',
  `creationdate` date NOT NULL default '0000-00-00',
  `background` text collate latin1_general_ci NOT NULL,
  `money_clean` bigint(20) NOT NULL default '0',
  `money_dirty` bigint(20) NOT NULL default '0',
  `homecity` int(11) NOT NULL default '0',
  `location` int(11) NOT NULL default '0',
  `stats_id` bigint(20) NOT NULL default '0',
  `timers_id` bigint(20) NOT NULL default '0',
  `rank_id` bigint(20) NOT NULL default '0',
  `health` int(11) NOT NULL default '100',
  `maxhealth` int(11) NOT NULL default '100',
  `quote` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_characters`
insert into `char_characters` values
(2,1,'Roger','Roger','Johns','m','1987-06-08','2007-02-18','Roger was born in Amsterdam on the 8th of June in 1987. Still a youngster, in other words. He comes from a rich family that has always been respected and his youth has been influenced by this greatly. He would not directly speak to those that are lower in society\'s hierarchy as this had always been regarded as unappropriate behaviour. Roger still tends to be somewhat lonely due to this youth and is regarded as an odd person in some cases. He\'s got his heart at the right place though and people who he regards as friends know better...',69873,297097,1,1,2,2,4,80,100,'Guess i have to put something down here..'),
(3,14,'Xanther','Xanther','Chan','v','1989-12-31','2007-02-19','She is one bad ass mofo.',5307,77549,2,1,3,3,1,100,100,'That ain\\\'t boys!'),
(4,21,'TriggerFinger','Sergio','Morello','m','1986-03-30','2007-02-19','Sergio Came from humble roots, the son of a farmer. But one day young Sergio was introduced to the Sicilian mafia and his life was forever changed. He eventually moved to the united states and became a hit man and even setting up his own crime family, and earning the name \\\"triggerfinger\\\"',1569,3011,1,1,4,4,1,100,100,''),
(5,32,'Gun','Tommy','Briggs','m','1970-03-19','2007-02-21','I eat bacon and eggs in the morning',3247,0,1,1,5,5,1,100,100,''),
(6,33,'Kaheed','Nic','Mowday','m','1990-12-31','2007-02-21','i have no background. i am a robot.',3829,0,1,1,6,6,1,100,100,''),
(7,34,'Noberto','lee','hennigan','m','1987-04-13','2007-02-26','Kicked out of my home city through no fault of my own. The corruption in the government and the local authorities made me determined to return and finish what I started',4807,0,1,1,7,7,1,100,100,''),
(8,35,'cap','Andres','Bonifacio','m','1983-09-01','2007-02-27','Philippine hero who fought the Spaniards.',1891,0,1,1,8,8,1,100,100,''),
(9,9,'MichaelErdemi','Ahmet','Erdemi','m','1987-01-01','2007-02-27','Well educated... Saw the inequality and fairlessness of the world.',11847,0,2,2,9,9,1,100,100,''),
(10,10,'Nori','Eleonore','Vito','v','1991-10-23','2007-02-28','The background from Nori (a.k.a Eleonore Vito) is uknown.',1000,34289,1,1,10,10,1,100,100,''),
(11,36,'Euphorikal','Euph','Orikal','m','1986-10-17','2007-02-28','Junglist is a slang term referring to a dedicated listener of jungle known also as drum and bass. Tracks from this genre often contain calls and references to \\\"original junglists\\\", \\\"jungle soldiers\\\" and \\\"junglist krus\\\" (pronounced /kru:z/). The term itself is connected with the origin of the name jungle, see relevant articles on history of drum and bass and jungle.\n\nJunglists also like to refer to themselves as \\\"rude bwoys\\\" a slang term originally used by Jamaicans (as rude boy), meaning \\\"gangsta\\\" or \\\"badbwoy\\\" (\\\"bad boy\\\"). Certain tracks make references to the \\\"original rudebwoys\\\" as denoting particularly respected junglists.\n\nJunglists are often, but not always, associated with heavy marijuana usage (\\\"smokin\\\' da herb\\\", a popular jungle motif), baggy camouflage clothing (cargo pants) and sweatshirts (\\\"hoodies\\\"), somewhat similar to hip hop and gangsta fashions.',6640,5559,1,1,11,11,1,100,100,''),
(12,38,'Spjutulf','Daniel','Lagerman','m','1980-11-06','2007-03-02','Protected By Roger. Married To Roger.\n\nSpjutulf <3 Roger',3473,0,1,1,12,12,1,100,100,''),
(13,3,'lilith','kali','lenuzza','v','1986-01-08','2007-03-05','raised in the bronx of sicili',3045,30482,1,1,13,13,1,100,100,''),
(14,40,'MadMan','Samuel','Ricco','m','1945-04-30','2007-03-06','Violent, Dangerous, Crazy, And Has Been Confirmed By His Head Doctors As A Psycho, The Police Have Him As A Dangerous Person And To Stay Clear Of Him ANd If Seen To REport To The Police So They Can Handle Him...',4807,0,2,2,14,14,1,100,100,''),
(15,41,'Aaron','Aaron','Amann','m','1986-02-05','2007-03-06','Aaron is a strange man, a poor man, and a violent man. He lives in the woods, where visitors rarely venture. He has a wild dog chained in the backyard, and an alley cat, chained in the front.',28551,731,1,1,15,15,6,100,100,''),
(16,42,'MrVitti','Paul','Vitti','m','1989-03-02','2007-03-06','<===Vitti Family===>\r\n\r\n\r\nPaul Vitti',3633,0,1,1,16,16,1,100,100,''),
(17,22,'Godfather','Vito','Corleone','m','1942-08-20','2007-03-09','Born In Corleone Italy, His Father Worked For The Local Crime Syndicate , His Father Was Making Side Deals With Other Dons Just To Make Money To Put Food On The Table,His Father Was Murderd Followed By His Mother And Brother, He Immergrated To Little Italy New York , Where He Got Into Trouble With A Local Street Gang, Where His Fathers Brother Was Capo-Regime Of The Rizzo Famila, Now Un-Empolyed His Working His Way Up To Become Don!',5342,0,1,1,17,17,1,100,100,''),
(18,44,'Katkera','Gabrielle','Crusifixis','v','1989-02-11','2007-03-12','Gabrielle is a girl who embraces the dark side of life...',2180,3726,1,1,21,18,1,100,100,''),
(19,13,'Authentic','Jason','Perrigo','m','1986-10-17','2007-03-13','fahjsasdasjdopasdjpsofjafadgadpfad',16671,0,1,1,22,19,1,100,100,''),
(20,17,'Scott','Timothy','Bertsch','m','1990-01-11','2007-03-13','I eat chicken I like beef smoke with me and youll be pleased',7842,0,1,1,23,20,1,100,100,''),
(21,45,'Sanderscharacter','Sanderscharacterfirstname','Sanderscharacterlastname','m','1942-01-01','2007-03-14','No background. No background. No background. No background. No background. No background. No background. No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.No background. No background. No background. No background. No background. No background. No background.',2069,574,1,1,24,21,1,100,100,''),
(22,43,'Soprano','Tony','Soprano','m','1976-01-15','2007-03-15','Full time gangster, the whole family was',3842,0,1,1,25,22,1,100,100,''),
(23,11,'Kurochan','Kuro','Unknown','m','1986-07-29','2007-03-21','Brought up by his crack momma and his police daddy, this person has been severly scarred for life xD! SIF',10140,0,1,1,26,23,1,100,100,''),
(24,20,'Maluco','Matheus','Leitao','m','1985-05-19','2007-04-06','I\\\'m a fucking badass mother fucker !',5366,0,1,1,27,24,1,100,100,''),
(25,46,'Epix','Mark','Graham','m','1985-12-17','2007-05-10','A background of at least 20 characters huh? Hmm... Lets see...',16000,0,1,1,28,25,1,100,100,''),
(0,0,'ADMINISTRATOR','ADMINISTRATOR','ADMINISTRATOR','m','0000-00-00','0000-00-00','',0,0,0,0,46,43,0,100,100,''),
(28,18,'MrTinketi','Mister','Tinketi','m','1945-12-25','2007-08-27','Im a Lean Mean Killing Machine',1680,30135,1,1,29,26,1,100,100,''),
(29,48,'Phase','Harry','Thorne','m','1991-10-04','2007-08-27','Err, testing teh game.',1482,819686,1,1,30,27,1,100,100,'3 Whacks. \n206 Defense. \nI is a tank. \nOfficial Bug Finder. \nOfficial Image Maker for RR. \nYou just have too love me.'),
(30,49,'Vilshade','Jack','Vilshade','m','1980-01-31','2007-08-27','He came from a family in Amsterdam, his mother died at child-birth and his father got tied up with the local gangsters and owed them a lot of money, he couldn\\\'t pay them back and they dealt with him so he lived on the streets at a young age begging citizens for money so he could survive until he made friends and they helped him, he built up his life, got a job, a new car and managed to gather up a healthy bank balance, he was happy but he knew he should get the gangsters back for what they did, he doesn\\\'t know of any other family members.',849,2634,1,1,31,28,1,0,100,''),
(31,47,'heroCaesar','Finn','Pennink','m','1988-04-27','2007-08-28','mafia hgdskfgkd jsdghflugh sajghlfush sughls',11679,76788,1,1,32,29,1,100,100,''),
(32,51,'Stubbsy','Tom','Stubbs','m','1990-11-14','2007-08-31','I don\\\'t know what to put!\n\nJust my friend recommended this =]',1747,0,1,1,33,30,1,100,100,''),
(38,53,'Testdummy','Test','Dummy','m','1942-01-01','2007-09-23','Testdummy is the first product of artificially designed life. Testdummy was designed to take bullets and other crap, all in the name of testing!',45000,0,1,1,39,36,1,100,100,''),
(35,54,'Sneo','Sean','McNamara','m','1990-07-06','2007-09-01','No backround at current.',1391,0,2,1,36,33,1,100,100,'Coding features one line at a time! '),
(36,55,'Testa','Test','test','m','1942-01-01','2007-09-10','TestTestTestTestTestTestTestTestTestTestTestTestv',4140,0,1,1,37,34,1,100,100,''),
(37,52,'EchOx','Nathan','Healey','m','1955-01-01','2007-09-18','I\\\'m testing the game.',1005,5479,1,1,38,35,1,100,100,''),
(46,60,'Habaal','Habaal','Gregov','m','1960-03-25','2007-11-24','Habaal originally came from Cairo, Egypt were he was employed as a high-level Banker for the Cairo Banking Office.',93900,0,1,1,53,50,6,100,100,'Money plays the largest part in determining the course of history.');

-- Table structure for table `char_deeds`
DROP TABLE IF EXISTS `char_deeds`;

CREATE TABLE `char_deeds` (
  `char_id` bigint(20) NOT NULL default '0',
  `slot_a` bigint(20) NOT NULL default '0',
  `slot_b` bigint(20) NOT NULL default '0',
  `slot_c` bigint(20) NOT NULL default '0',
  `slot_d` bigint(20) NOT NULL default '0',
  `slot_e` bigint(20) NOT NULL default '0',
  `slot_f` bigint(20) NOT NULL default '0',
  `slot_g` bigint(20) NOT NULL default '0',
  `slot_h` bigint(20) NOT NULL default '0',
  `slot_i` bigint(20) NOT NULL default '0',
  `slot_j` bigint(20) NOT NULL default '0',
  `slot_k` bigint(20) NOT NULL default '0',
  `slot_l` bigint(20) NOT NULL default '0',
  `slot_m` bigint(20) NOT NULL default '0',
  `slot_n` bigint(20) NOT NULL default '0',
  `slot_o` bigint(20) NOT NULL default '0',
  `slot_p` bigint(20) NOT NULL default '0',
  KEY `char_id` (`char_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Deed slots, defined in utility file.';

-- dumping data for table `char_deeds`
insert into `char_deeds` values
(15,23,21,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(2,21,21,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(39,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(28,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(38,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(29,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(37,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(31,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(35,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(24,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(45,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(44,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
(46,27,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

-- Table structure for table `char_degrees`
DROP TABLE IF EXISTS `char_degrees`;

CREATE TABLE `char_degrees` (
  `degree_id` bigint(20) NOT NULL auto_increment,
  `character_id` bigint(20) NOT NULL default '0',
  `degree_type` char(3) collate latin1_general_ci NOT NULL default '0',
  `degree_exp` int(11) NOT NULL default '0',
  `degree_status` int(11) NOT NULL default '0',
  `business_id` bigint(20) NOT NULL,
  `fname` varchar(255) collate latin1_general_ci NOT NULL,
  `lname` varchar(255) collate latin1_general_ci NOT NULL,
  `bank_account` bigint(20) NOT NULL,
  `authed` tinyint(1) NOT NULL,
  `auth_fname` varchar(255) collate latin1_general_ci NOT NULL,
  `auth_lname` varchar(255) collate latin1_general_ci NOT NULL,
  `auth_bank_account` bigint(20) NOT NULL,
  PRIMARY KEY  (`degree_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_degrees`
insert into `char_degrees` values
(1,2,'med',274,1,11,'Roger','Johns',7272855,0,'','',0),
(2,2,'eco',567,3,11,'Roger','Johns',7272855,0,'','',0);

-- Table structure for table `char_equip`
DROP TABLE IF EXISTS `char_equip`;

CREATE TABLE `char_equip` (
  `char_id` bigint(20) NOT NULL default '0',
  `armor_head` bigint(20) NOT NULL default '0',
  `armor_chest` bigint(20) NOT NULL default '0',
  `armor_legs` bigint(20) NOT NULL default '0',
  `armor_feet` bigint(20) NOT NULL default '0',
  `main_weapon` bigint(20) NOT NULL default '0',
  `weap2_open` tinyint(1) NOT NULL default '0',
  `second_weapon` bigint(20) NOT NULL default '0',
  `bag` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_equip`
insert into `char_equip` values
(2,33,34,22,38,30,0,0,10),
(3,33,34,35,38,30,0,0,13),
(4,0,0,0,0,0,0,0,19),
(5,0,0,0,0,0,0,0,19),
(6,0,0,0,0,0,0,0,19),
(7,0,0,0,0,0,0,0,19),
(8,0,0,0,0,0,0,0,19),
(9,0,0,0,0,0,0,0,19),
(10,0,0,0,0,0,0,0,19),
(11,0,0,0,0,0,0,0,19),
(12,0,0,0,0,0,0,0,19),
(13,33,34,22,17,0,0,0,13),
(14,0,0,0,0,0,0,0,19),
(15,0,0,0,0,0,0,0,19),
(16,0,0,0,0,0,0,0,19),
(17,0,0,0,0,0,0,0,19),
(18,0,0,0,0,0,0,0,19),
(19,0,0,0,0,0,0,0,19),
(20,0,0,0,0,0,0,0,19),
(21,0,0,0,0,0,0,0,19),
(22,0,0,0,0,0,0,0,19),
(23,0,0,0,0,0,0,0,19),
(24,0,0,0,0,0,0,0,19),
(25,0,0,0,0,0,0,0,19),
(0,0,0,0,0,0,0,0,19),
(28,0,0,0,0,0,0,0,19),
(29,33,34,35,38,30,0,0,19),
(30,0,0,0,0,0,0,0,19),
(31,33,34,22,23,0,0,0,19),
(32,0,0,0,0,0,0,0,19),
(37,0,0,0,0,0,0,0,19),
(38,0,0,0,0,0,0,0,19),
(35,0,0,0,0,0,0,0,19),
(36,0,0,0,0,0,0,0,19),
(45,0,0,0,0,0,0,0,10),
(46,0,0,0,0,0,0,0,10);

-- Table structure for table `char_hotelrooms`
DROP TABLE IF EXISTS `char_hotelrooms`;

CREATE TABLE `char_hotelrooms` (
  `id` bigint(20) NOT NULL auto_increment,
  `char_id` bigint(20) NOT NULL,
  `location_id` int(11) NOT NULL,
  `pay_timer` bigint(20) NOT NULL,
  `nights_stayed` int(11) NOT NULL,
  `hotel_type` int(11) NOT NULL default '0',
  `money_clean` bigint(20) NOT NULL default '0',
  `money_dirty` bigint(20) NOT NULL default '0',
  `closet_slots` int(11) NOT NULL default '6',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_hotelrooms`
insert into `char_hotelrooms` values
(1,15,1,1196613842,0,1,0,0,6);

-- Table structure for table `char_inventory`
DROP TABLE IF EXISTS `char_inventory`;

CREATE TABLE `char_inventory` (
  `char_id` bigint(20) NOT NULL default '0',
  `item_id` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_inventory`
insert into `char_inventory` values
(3,36),
(28,3),
(28,4),
(28,1),
(38,31),
(38,31),
(38,19),
(15,28);

-- Table structure for table `char_mailbox`
DROP TABLE IF EXISTS `char_mailbox`;

CREATE TABLE `char_mailbox` (
  `char_id` bigint(20) NOT NULL default '0',
  `item_id` bigint(20) NOT NULL default '0',
  `money` bigint(20) NOT NULL default '0',
  `has_arrived` tinyint(1) NOT NULL default '0',
  `mail_id` bigint(20) NOT NULL auto_increment,
  `sender` bigint(20) NOT NULL default '0',
  `message` varchar(255) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`mail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_mailbox`
insert into `char_mailbox` values
(38,1,0,1,25,-17,'We hereby include the item you have bought from our Auction House. Enjoy it, and remember to always come back to us for sales and bargains!'),
(2,30,0,0,35,-15,'Hereby we return the pledge that was used in loan #2'),
(2,0,10000,0,36,-15,'Hereby we grant you a loan of $10000 as specified by loan #3'),
(13,22,0,1,20,0,''),
(2,0,15000,0,37,-15,'Hereby we grant you a loan of $15000 as specified by loan #4'),
(2,0,10000,0,32,-15,'Hereby we grant you a loan of $10000 as specified by loan #1'),
(2,29,0,0,33,-15,'Hereby we return the pledge that was used in loan #1'),
(15,0,21599,1,34,-15,'Hereby we grant you a loan of $21599 as specified by loan #2');

-- Table structure for table `char_occupations`
DROP TABLE IF EXISTS `char_occupations`;

CREATE TABLE `char_occupations` (
  `id` bigint(20) NOT NULL auto_increment,
  `career_type` tinyint(4) NOT NULL default '0',
  `occ_name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `decay_rate` double NOT NULL default '0',
  `work_url` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_occupations`
insert into `char_occupations` values
(1,0,'Unemployed','0',''),
(2,1,'Criminal','0',''),
(3,2,'Banking','0','bank/index.php');

-- Table structure for table `char_promos`
DROP TABLE IF EXISTS `char_promos`;

CREATE TABLE `char_promos` (
  `id` bigint(20) NOT NULL auto_increment,
  `char_id` bigint(20) NOT NULL,
  `occupation_id` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `next_rank` varchar(30) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_promos`
insert into `char_promos` values
(1,2,3,0,'4'),
(2,2,3,0,'4'),
(3,2,3,0,'4'),
(4,2,3,0,'4'),
(5,2,3,0,'4'),
(6,2,3,0,'4'),
(7,2,3,0,'4'),
(8,2,3,0,'4'),
(9,2,3,0,'4'),
(10,2,3,0,'4'),
(11,15,3,1,'5');

-- Table structure for table `char_ranks`
DROP TABLE IF EXISTS `char_ranks`;

CREATE TABLE `char_ranks` (
  `id` bigint(20) NOT NULL auto_increment,
  `occupation_id` bigint(20) NOT NULL default '0',
  `order_index` int(11) NOT NULL,
  `exp_required` bigint(20) NOT NULL,
  `auto_promo` tinyint(1) NOT NULL default '0',
  `salary` bigint(20) NOT NULL default '0',
  `rank_name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `rank_image` varchar(255) collate latin1_general_ci NOT NULL default '',
  `promo_page` varchar(255) collate latin1_general_ci NOT NULL,
  `taverns_notice_enabled` varchar(5) collate latin1_general_ci NOT NULL default 'false',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_ranks`
insert into `char_ranks` values
(1,1,0,0,0,0,'Unemployed','ranks/unemployed.jpg','','false'),
(2,2,0,0,0,0,'Pickpocket','ranks/pickpocket.png','crime_pickpocket.php','false'),
(3,2,1,0,0,0,'Dealer','ranks/dealer.png','crime_dealer.php','false'),
(4,3,0,500,1,0,'Bank Trainee','','bank_trainee.php','false'),
(5,3,1,2000,0,0,'Junior Teller','','bank_juniorteller.php','false'),
(6,3,2,4000,0,0,'Senior Teller','','bank_seniorteller.php','false'),
(7,3,3,7000,0,0,'Bank Manager','','bank_bankmanager.php','false'),
(8,3,4,12000,0,0,'Bank President','','bank_bankpresident.php','true');

-- Table structure for table `char_stats`
DROP TABLE IF EXISTS `char_stats`;

CREATE TABLE `char_stats` (
  `id` bigint(20) NOT NULL auto_increment,
  `strength` bigint(20) NOT NULL default '0',
  `defense` bigint(20) NOT NULL default '0',
  `intellect` bigint(20) NOT NULL default '0',
  `cunning` bigint(20) NOT NULL default '0',
  `criminal_exp` bigint(20) NOT NULL default '0',
  `bank_exp` bigint(20) NOT NULL,
  `bank_trust` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_stats`
insert into `char_stats` values
(2,6061,6574,6839,6403,26865,1436,2),
(3,4537,2048,1215,4882,18567,0,0),
(4,601,526,1067,72,50,0,0),
(5,1500,500,0,0,0,0,0),
(6,1512,512,12,12,0,0,0),
(7,500,1500,0,0,0,0,0),
(8,0,500,1500,0,0,0,0),
(9,1,1001,1001,1,0,0,0),
(10,530,1573,3,1023,1811,0,0),
(11,1767,535,30,130,100,0,0),
(12,2,502,1502,2,0,0,0),
(13,2827,640,258,2251,6016,0,0),
(14,1003,1003,3,3,0,0,0),
(15,1196,514,577,190,10089,4006,0),
(16,1500,500,0,0,0,0,0),
(17,506,500,500,509,0,0,0),
(21,1677,508,2,956,450,0,0),
(22,1012,500,500,0,0,0,0),
(23,1512,500,0,0,0,0,0),
(24,1583,3,503,53,50,0,0),
(25,505,1000,0,500,0,0,0),
(26,7,7,1507,507,0,0,0),
(27,1000,505,500,0,0,0,0),
(28,0,1005,0,1000,0,0,0),
(29,1262,1031,102,1857,2399,0,0),
(30,9587,1717,1277,6217,35557,0,0),
(31,2448,559,60,916,2067,0,0),
(32,209,534,531,1540,3564,0,0),
(33,500,1000,500,0,0,0,0),
(38,575,502,503,556,50,0,0),
(39,500,1000,500,0,0,0,0),
(36,0,0,1000,1018,816,0,0),
(37,500,1000,500,0,0,0,0),
(52,3000,3015,7000,6000,0,5000,2),
(51,3000,3000,7000,6000,0,5000,2),
(53,3000,3030,7035,6000,0,5000,2);

-- Table structure for table `char_timers`
DROP TABLE IF EXISTS `char_timers`;

CREATE TABLE `char_timers` (
  `id` bigint(20) NOT NULL auto_increment,
  `earn_timer` bigint(20) NOT NULL default '0',
  `earn_timer2` bigint(20) NOT NULL,
  `study_timer` bigint(20) NOT NULL,
  `agg_timer` bigint(20) NOT NULL default '0',
  `agg_timer2` bigint(20) NOT NULL,
  `murder_timer` bigint(20) NOT NULL default '0',
  `online_timer` bigint(20) NOT NULL default '0',
  `agg_pro` bigint(20) NOT NULL default '0',
  `kill_pro` bigint(20) NOT NULL default '0',
  `bar_timer` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `char_timers`
insert into `char_timers` values
(2,1195686804,1195602275,1191969507,1193004494,1195603156,1191013607,1195688648,1196313211,1190991427,1195000323),
(3,0,0,0,1190983406,0,1190983627,1191498335,1193008994,1190991294,0),
(4,0,0,0,1188605067,0,0,1188604181,0,0,0),
(5,0,0,0,0,0,0,0,0,0,0),
(6,0,0,0,0,0,0,1173254421,0,0,0),
(7,0,0,0,0,0,0,0,0,0,0),
(8,0,0,0,0,0,0,0,0,0,0),
(9,0,0,0,0,0,0,1178743710,0,0,0),
(10,0,0,0,1191011911,0,1189372308,1191314466,1192622146,1191021407,0),
(11,0,0,0,1173773285,0,0,1176206529,0,0,0),
(12,0,0,0,0,0,0,1172872272,0,0,0),
(13,0,0,0,1190423551,0,1190425690,1190423027,1190122195,1190433025,0),
(14,0,0,0,0,0,0,1196218496,0,0,0),
(15,1196496524,0,0,1196329385,0,0,1196551140,0,0,1196496087),
(16,0,0,0,0,0,0,0,0,0,0),
(17,0,0,0,0,0,0,1180996792,0,0,0),
(18,0,0,0,1173730878,0,0,1173730028,0,0,0),
(19,0,0,0,0,0,0,1173883872,0,0,0),
(20,0,0,0,0,0,0,1188704095,0,0,0),
(21,0,0,0,1173879055,0,0,1174468901,0,0,0),
(22,0,0,0,0,0,0,1175457483,0,0,0),
(23,0,0,0,0,0,0,1174999595,0,0,0),
(24,0,0,0,1189212726,0,1189214730,1190989895,0,1189222045,0),
(25,0,0,0,1178872506,0,0,1178871762,0,0,0),
(26,0,0,0,1190130904,0,1190116498,1191098708,1190135380,1190126956,0),
(27,0,0,0,1190569909,0,1190813797,1190881527,1192620117,1190891821,0),
(28,0,0,0,1188836113,0,0,1189884115,1188842723,0,0),
(29,0,0,0,1190038025,0,1190040145,1191396916,1189421583,1189426481,0),
(30,0,0,0,0,0,0,1188599331,0,0,0),
(35,0,0,0,1190569849,0,1190884621,1190881624,1190654698,1190659543,0),
(36,0,0,0,0,0,0,1195507893,0,0,1194696059),
(33,0,0,0,1189313396,0,1189315510,1190838952,1189316488,1190849530,0),
(34,0,0,0,0,0,0,1189445236,0,0,0),
(37,1196216802,1196216802,1196216802,1196216802,1196216802,1196216802,1196216802,1196216802,1196216802,1196216802),
(38,1196216814,1196216814,1196216814,1196216814,1196216814,1196216814,1196216814,1196216814,1196216814,1196216814),
(39,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815),
(40,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815,1196216815),
(41,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916),
(42,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916,1196216916),
(43,1196216917,1196216917,1196216917,1196216917,1196216917,1196216917,1196216917,1196216917,1196216917,1196216917),
(44,1196216954,1196216954,1196216954,1196216954,1196216954,1196216954,1196216954,1196216954,1196216954,1196216954),
(45,1196218616,1196218616,1196218616,1196218616,1196218616,1196218616,1196218616,1196218616,1196218616,1196218616),
(46,1196218820,1196218820,1196218820,1196218820,1196218820,1196218820,1196218820,1196218820,1196218820,1196218820),
(47,1196219694,1196219694,1196219694,1196219694,1196219694,1196219694,1196221628,1196219694,1196219694,1196219694),
(48,1196221258,1196221258,1196221258,1196221258,1196221258,1196221258,1196221860,1196221258,1196221258,1196221258),
(49,1196221334,1196221334,1196221334,1196221334,1196221334,1196221334,1196293737,1196292347,1196221334,1196221334),
(50,1196293372,1196293372,1196293372,1196293372,1196293372,1196293372,1196551140,1196300664,1196293372,1196293372);

-- Table structure for table `char_tuneups`
DROP TABLE IF EXISTS `char_tuneups`;

CREATE TABLE `char_tuneups` (
  `id` bigint(20) NOT NULL,
  `char_id` bigint(20) NOT NULL default '0',
  `tuneup_id` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `char_vehicles`
DROP TABLE IF EXISTS `char_vehicles`;

CREATE TABLE `char_vehicles` (
  `id` bigint(20) NOT NULL default '0',
  `vehicle` bigint(20) NOT NULL default '0',
  `modified` varchar(5) NOT NULL default '0',
  `health` int(11) NOT NULL default '0',
  `trading` varchar(5) NOT NULL default 'false',
  `traveling` varchar(5) NOT NULL default 'false',
  `to` int(11) NOT NULL default '0',
  `from` int(11) NOT NULL default '0',
  `end` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `char_vehicles`
insert into `char_vehicles` values
(15,2,'true',100,'false','false',1,2,0),
(25,1,'false',100,'false','false',0,0,0),
(29,1,'false',100,'false','false',0,0,0),
(30,1,'false',100,'false','false',0,0,0),
(3,1,'false',100,'false','false',0,0,0),
(31,1,'false',100,'false','false',0,0,0),
(13,1,'false',100,'false','false',0,0,0),
(38,1,'false',100,'false','false',0,0,0),
(46,1,'false',100,'false','false',0,0,0),
(46,1,'true',100,'false','false',0,0,0),
(46,1,'false',100,'false','false',0,0,0);

-- Table structure for table `clothingshop`
DROP TABLE IF EXISTS `clothingshop`;

CREATE TABLE `clothingshop` (
  `store_id` bigint(20) NOT NULL default '0',
  `business_id` bigint(20) NOT NULL default '0',
  `items` varchar(255) collate latin1_general_ci NOT NULL default '',
  `stock` varchar(255) collate latin1_general_ci NOT NULL default '',
  `reset_timer` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `clothingshop`
insert into `clothingshop` values
(0,14,'20 12 32 33','6 6 5 2',1196534196),
(0,28,'','',0),
(0,37,'','',0);

-- Table structure for table `comms`
DROP TABLE IF EXISTS `comms`;

CREATE TABLE `comms` (
  `id` bigint(20) NOT NULL auto_increment,
  `to` bigint(20) NOT NULL default '0',
  `from` bigint(20) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `subject` varchar(255) collate latin1_general_ci NOT NULL default '',
  `message` text collate latin1_general_ci NOT NULL,
  `comm_new` int(11) NOT NULL default '0',
  `comm_type` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `comms`

-- Table structure for table `country`
DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `code` char(3) NOT NULL default '',
  `name` char(52) NOT NULL default '',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `country`
insert into `country` values
('AFG','Afghanistan'),
('NLD','Netherlands'),
('ANT','Netherlands Antilles'),
('ALB','Albania'),
('DZA','Algeria'),
('ASM','American Samoa'),
('AND','Andorra'),
('AGO','Angola'),
('AIA','Anguilla'),
('ATG','Antigua and Barbuda'),
('ARE','United Arab Emirates'),
('ARG','Argentina'),
('ARM','Armenia'),
('ABW','Aruba'),
('AUS','Australia'),
('AZE','Azerbaijan'),
('BHS','Bahamas'),
('BHR','Bahrain'),
('BGD','Bangladesh'),
('BRB','Barbados'),
('BEL','Belgium'),
('BLZ','Belize'),
('BEN','Benin'),
('BMU','Bermuda'),
('BTN','Bhutan'),
('BOL','Bolivia'),
('BIH','Bosnia and Herzegovina'),
('BWA','Botswana'),
('BRA','Brazil'),
('GBR','United Kingdom'),
('VGB','Virgin Islands, British'),
('BRN','Brunei'),
('BGR','Bulgaria'),
('BFA','Burkina Faso'),
('BDI','Burundi'),
('CYM','Cayman Islands'),
('CHL','Chile'),
('COK','Cook Islands'),
('CRI','Costa Rica'),
('DJI','Djibouti'),
('DMA','Dominica'),
('DOM','Dominican Republic'),
('ECU','Ecuador'),
('EGY','Egypt'),
('SLV','El Salvador'),
('ERI','Eritrea'),
('ESP','Spain'),
('ZAF','South Africa'),
('ETH','Ethiopia'),
('FLK','Falkland Islands'),
('FJI','Fiji Islands'),
('PHL','Philippines'),
('FRO','Faroe Islands'),
('GAB','Gabon'),
('GMB','Gambia'),
('GEO','Georgia'),
('GHA','Ghana'),
('GIB','Gibraltar'),
('GRD','Grenada'),
('GRL','Greenland'),
('GLP','Guadeloupe'),
('GUM','Guam'),
('GTM','Guatemala'),
('GIN','Guinea'),
('GNB','Guinea-Bissau'),
('GUY','Guyana'),
('HTI','Haiti'),
('HND','Honduras'),
('HKG','Hong Kong'),
('SJM','Svalbard and Jan Mayen'),
('IDN','Indonesia'),
('IND','India'),
('IRQ','Iraq'),
('IRN','Iran'),
('IRL','Ireland'),
('ISL','Iceland'),
('ISR','Israel'),
('ITA','Italy'),
('TMP','East Timor'),
('AUT','Austria'),
('JAM','Jamaica'),
('JPN','Japan'),
('YEM','Yemen'),
('JOR','Jordan'),
('CXR','Christmas Island'),
('YUG','Yugoslavia'),
('KHM','Cambodia'),
('CMR','Cameroon'),
('CAN','Canada'),
('CPV','Cape Verde'),
('KAZ','Kazakstan'),
('KEN','Kenya'),
('CAF','Central African Republic'),
('CHN','China'),
('KGZ','Kyrgyzstan'),
('KIR','Kiribati'),
('COL','Colombia'),
('COM','Comoros'),
('COG','Congo'),
('COD','Congo, The Democratic Republic of the'),
('CCK','Cocos (Keeling) Islands'),
('PRK','North Korea'),
('KOR','South Korea'),
('GRC','Greece'),
('HRV','Croatia'),
('CUB','Cuba'),
('KWT','Kuwait'),
('CYP','Cyprus'),
('LAO','Laos'),
('LVA','Latvia'),
('LSO','Lesotho'),
('LBN','Lebanon'),
('LBR','Liberia'),
('LBY','Libyan Arab Jamahiriya'),
('LIE','Liechtenstein'),
('LTU','Lithuania'),
('LUX','Luxembourg'),
('ESH','Western Sahara'),
('MAC','Macao'),
('MDG','Madagascar'),
('MKD','Macedonia'),
('MWI','Malawi'),
('MDV','Maldives'),
('MYS','Malaysia'),
('MLI','Mali'),
('MLT','Malta'),
('MAR','Morocco'),
('MHL','Marshall Islands'),
('MTQ','Martinique'),
('MRT','Mauritania'),
('MUS','Mauritius'),
('MYT','Mayotte'),
('MEX','Mexico'),
('FSM','Micronesia, Federated States of'),
('MDA','Moldova'),
('MCO','Monaco'),
('MNG','Mongolia'),
('MSR','Montserrat'),
('MOZ','Mozambique'),
('MMR','Myanmar'),
('NAM','Namibia'),
('NRU','Nauru'),
('NPL','Nepal'),
('NIC','Nicaragua'),
('NER','Niger'),
('NGA','Nigeria'),
('NIU','Niue'),
('NFK','Norfolk Island'),
('NOR','Norway'),
('CIV','C?te d?Ivoire'),
('OMN','Oman'),
('PAK','Pakistan'),
('PLW','Palau'),
('PAN','Panama'),
('PNG','Papua New Guinea'),
('PRY','Paraguay'),
('PER','Peru'),
('PCN','Pitcairn'),
('MNP','Northern Mariana Islands'),
('PRT','Portugal'),
('PRI','Puerto Rico'),
('POL','Poland'),
('GNQ','Equatorial Guinea'),
('QAT','Qatar'),
('FRA','France'),
('GUF','French Guiana'),
('PYF','French Polynesia'),
('REU','R?union'),
('ROM','Romania'),
('RWA','Rwanda'),
('SWE','Sweden'),
('SHN','Saint Helena'),
('KNA','Saint Kitts and Nevis'),
('LCA','Saint Lucia'),
('VCT','Saint Vincent and the Grenadines'),
('SPM','Saint Pierre and Miquelon'),
('DEU','Germany'),
('SLB','Solomon Islands'),
('ZMB','Zambia'),
('WSM','Samoa'),
('SMR','San Marino'),
('STP','Sao Tome and Principe'),
('SAU','Saudi Arabia'),
('SEN','Senegal'),
('SYC','Seychelles'),
('SLE','Sierra Leone'),
('SGP','Singapore'),
('SVK','Slovakia'),
('SVN','Slovenia'),
('SOM','Somalia'),
('LKA','Sri Lanka'),
('SDN','Sudan'),
('FIN','Finland'),
('SUR','Suriname'),
('SWZ','Swaziland'),
('CHE','Switzerland'),
('SYR','Syria'),
('TJK','Tajikistan'),
('TWN','Taiwan'),
('TZA','Tanzania'),
('DNK','Denmark'),
('THA','Thailand'),
('TGO','Togo'),
('TKL','Tokelau'),
('TON','Tonga'),
('TTO','Trinidad and Tobago'),
('TCD','Chad'),
('CZE','Czech Republic'),
('TUN','Tunisia'),
('TUR','Turkey'),
('TKM','Turkmenistan'),
('TCA','Turks and Caicos Islands'),
('TUV','Tuvalu'),
('UGA','Uganda'),
('UKR','Ukraine'),
('HUN','Hungary'),
('URY','Uruguay'),
('NCL','New Caledonia'),
('NZL','New Zealand'),
('UZB','Uzbekistan'),
('BLR','Belarus'),
('WLF','Wallis and Futuna'),
('VUT','Vanuatu'),
('VAT','Holy See (Vatican City State)'),
('VEN','Venezuela'),
('RUS','Russian Federation'),
('VNM','Vietnam'),
('EST','Estonia'),
('USA','United States'),
('VIR','Virgin Islands, U.S.'),
('ZWE','Zimbabwe'),
('PSE','Palestine'),
('ATA','Antarctica'),
('BVT','Bouvet Island'),
('IOT','British Indian Ocean Territory'),
('SGS','South Georgia and the South Sandwich Islands'),
('HMD','Heard Island and McDonald Islands'),
('ATF','French Southern territories'),
('UMI','United States Minor Outlying Islands');

-- Table structure for table `events`
DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL auto_increment,
  `char_id` bigint(20) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `subject` varchar(255) collate latin1_general_ci NOT NULL default '',
  `message` text collate latin1_general_ci NOT NULL,
  `event_new` int(11) NOT NULL default '0',
  `event_type` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `events`
insert into `events` values
(42,4,'2007-03-17 23:27:52','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $658!',0,0),
(3,15,'2007-03-11 15:35:18','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1511!',0,0),
(4,11,'2007-03-11 23:43:14','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $2322!',0,0),
(297,29,'2007-09-01 05:32:11','Attempted Pickpocketing','EchO has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(177,30,'2007-08-30 16:45:50','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(178,29,'2007-08-30 16:46:55','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(6,11,'2007-03-12 00:46:09','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $3163!',0,0),
(41,4,'2007-03-17 17:25:27','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $3439!',0,0),
(113,29,'2007-08-28 07:44:51','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(114,29,'2007-08-28 07:56:35','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(50,25,'2007-05-10 08:31:51','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $46750!',0,0),
(10,15,'2007-03-12 03:49:36','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1571!',0,0),
(11,15,'2007-03-12 04:07:25','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $1059!',0,0),
(46,21,'2007-03-21 05:17:32','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $3633!',1,0),
(13,15,'2007-03-12 04:27:15','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1714!',0,0),
(157,31,'2007-08-29 09:13:57','Transfer from 7272855','You have received money from bank account number 7272855. This bank account belongs to a person informally known as Roger. He transferred an amount of $25000 into bank account 3789056. This is your Checking Account.',0,0),
(158,29,'2007-08-29 11:41:57','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(16,15,'2007-03-12 04:52:34','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $2161!',0,0),
(17,15,'2007-03-12 06:16:17','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $3458!',0,0),
(172,28,'2007-08-30 12:58:37','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $13358!',0,0),
(47,4,'2007-04-18 09:36:41','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $136!',0,0),
(168,3,'2007-08-30 09:56:11','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(32,18,'2007-03-12 15:58:13','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $4599!',1,0),
(226,3,'2007-08-31 09:29:04','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(171,3,'2007-08-30 11:09:22','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(37,21,'2007-03-14 09:05:28','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $2147!',0,0),
(115,30,'2007-08-28 07:57:02','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $316!',0,0),
(175,30,'2007-08-30 16:11:08','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(63,15,'2007-08-27 09:12:39','Checking Account request approval.','After careful investigation of your request to open a new Checking Account with the Bank of Amsterdam no objections could be raised to processing your request further. You are now in the possession of a new Checking Account containing a balance of $5000. The bank account number of your new account is 4583313. You can always find this number in your character statistics screen.',0,0),
(159,30,'2007-08-29 12:05:32','Attempted Mugging','Nori has attempted to mug you in a dark alley! Thanks to your alertness and her clumsiness you were able to escape the dangerous situation!',0,0),
(222,3,'2007-08-31 08:57:12','Attempted Murder','Phase has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(162,30,'2007-08-29 13:19:40','Checking Account request approval.','After careful investigation of your request to open a new Checking Account with the Bank of Amsterdam no objections could be raised to processing your request further. You are now in the possession of a new Checking Account containing a balance of $5000. The bank account number of your new account is 5883754. You can always find this number in your character statistics screen.',0,0),
(118,30,'2007-08-28 08:44:29','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $47612!',0,0),
(215,29,'2007-08-31 08:10:23','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(67,28,'2007-08-27 15:32:06','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $2944!',0,0),
(140,29,'2007-08-28 16:10:38','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(121,29,'2007-08-28 09:38:15','Attempted Mugging','heroCaesar has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(70,28,'2007-08-27 17:11:49','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(71,29,'2007-08-27 17:14:47','Attempted Mugging','MrTinketi has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(87,30,'2007-08-27 19:59:16','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $3032!',0,0),
(73,30,'2007-08-27 17:37:00','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(74,29,'2007-08-27 17:40:50','Attempted Mugging','MrTinketi has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(91,30,'2007-08-27 20:30:43','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $273!',0,0),
(76,30,'2007-08-27 18:12:10','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $2999!',0,0),
(77,30,'2007-08-27 18:12:36','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1457!',0,0),
(78,29,'2007-08-27 18:22:24','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(79,29,'2007-08-27 18:38:58','Attempted Mugging','MrTinketi has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(80,28,'2007-08-27 18:39:18','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1370!',0,0),
(81,28,'2007-08-27 19:06:25','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $768!',0,0),
(82,29,'2007-08-27 19:08:10','Attempted Mugging','MrTinketi has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(83,29,'2007-08-27 19:12:35','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(84,29,'2007-08-27 19:33:23','Attempted Mugging','MrTinketi has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(85,28,'2007-08-27 19:33:58','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $433!',0,0),
(88,30,'2007-08-27 19:59:52','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1015!',0,0),
(89,30,'2007-08-27 20:28:29','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $856!',0,0),
(90,29,'2007-08-27 20:28:30','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(92,30,'2007-08-27 21:19:19','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(93,29,'2007-08-27 21:19:21','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(94,29,'2007-08-27 21:46:11','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(95,30,'2007-08-27 21:52:46','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(96,29,'2007-08-27 22:15:08','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(97,30,'2007-08-27 22:24:08','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(141,30,'2007-08-28 16:11:03','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(142,30,'2007-08-28 16:16:48','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $13482!',0,0),
(227,29,'2007-08-31 09:35:12','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(180,30,'2007-08-30 17:12:43','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(163,30,'2007-08-29 13:20:19','Transfer from 7272855','You have received money from bank account number 7272855. This bank account belongs to a person informally known as Roger. He transferred an amount of $20000 into bank account 5883754. This is your Checking Account.',0,0),
(133,30,'2007-08-28 15:10:22','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $16412!',0,0),
(429,28,'2007-09-03 09:12:41','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront her and before he fled the scene you recognised her to be lilith!',0,0),
(296,31,'2007-09-01 05:31:50','Attempted Murder','EchO has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(155,31,'2007-08-29 09:10:00','Checking Account request approval.','After careful investigation of your request to open a new Checking Account with the Bank of Amsterdam no objections could be raised to processing your request further. You are now in the possession of a new Checking Account containing a balance of $5000. The bank account number of your new account is 3789056. You can always find this number in your character statistics screen.',0,0),
(131,29,'2007-08-28 13:41:52','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(129,30,'2007-08-28 13:18:33','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(127,30,'2007-08-28 13:06:04','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(128,29,'2007-08-28 13:06:07','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(135,30,'2007-08-28 15:38:15','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(136,29,'2007-08-28 15:41:08','Attempted Mugging','MrTinketi has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(169,3,'2007-08-30 10:39:40','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(145,30,'2007-08-28 17:04:21','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(146,29,'2007-08-28 17:56:08','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(148,3,'2007-08-29 05:05:09','Transfer from 7272855','You have received money from bank account number 7272855. This bank account belongs to a person informally known as Roger. He transferred an amount of $100 into bank account 5619127. This is your Checking Account.',0,0),
(228,29,'2007-08-31 09:35:38','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(182,30,'2007-08-30 17:37:50','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(242,30,'2007-08-31 11:16:27','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $229874!',0,0),
(300,29,'2007-09-01 05:51:25','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(184,30,'2007-08-30 18:01:40','Victim of Pickpocketing','You were walking through a busy street in Amsterdam and suddenly noticed your bags feeling a lot lighter! After immediately checking your bag\'s contents you noticed a Rusty Practise Gun had gone missing! Looks like you\'ve been victim of a pickpocket!',0,0),
(185,30,'2007-08-30 20:09:11','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $64217!',0,0),
(186,29,'2007-08-30 20:09:34','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(243,20,'2007-08-31 12:02:22','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $1871!',0,0),
(549,29,'2007-09-18 11:27:55','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(541,29,'2007-09-17 09:41:42','Attempted Pickpocketing','heroCaesar has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(220,29,'2007-08-31 08:49:41','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(218,29,'2007-08-31 08:48:03','Attempted Murder','Xanther has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(191,3,'2007-08-31 04:23:51','Attempted Mugging','lilith has attempted to mug you in a dark alley! Thanks to your alertness and her clumsiness you were able to escape the dangerous situation!',0,0),
(194,3,'2007-08-31 05:11:25','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(230,29,'2007-08-31 09:50:09','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(196,3,'2007-08-31 05:14:35','Attempted Mugging','lilith has attempted to mug you in a dark alley! Thanks to your alertness and her clumsiness you were able to escape the dangerous situation!',0,0),
(197,3,'2007-08-31 05:38:11','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(231,3,'2007-08-31 09:59:03','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(232,29,'2007-08-31 10:00:15','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(374,29,'2007-09-02 12:59:52','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be MrTinketi!',0,0),
(375,29,'2007-09-02 13:00:41','Attempted Murder','MrTinketi has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(240,29,'2007-08-31 11:14:36','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(210,3,'2007-08-31 07:43:19','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(204,3,'2007-08-31 07:11:28','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(346,3,'2007-09-02 05:51:43','Attempted Mugging','lilith has attempted to mug you in a dark alley! Thanks to your alertness and her clumsiness you were able to escape the dangerous situation!',0,0),
(347,3,'2007-09-02 05:52:25','Attempted Murder','lilith has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(211,3,'2007-08-31 07:48:43','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(208,3,'2007-08-31 07:33:50','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(301,29,'2007-09-01 05:51:45','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(245,29,'2007-08-31 16:48:43','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(246,29,'2007-08-31 17:06:55','Attempted Murder','EchO has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(247,29,'2007-08-31 17:07:17','Attempted Mugging','EchO has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(248,29,'2007-08-31 17:13:30','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(250,29,'2007-08-31 17:17:00','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(254,29,'2007-08-31 18:04:47','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(253,29,'2007-08-31 18:02:10','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(325,29,'2007-09-01 15:34:16','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(304,29,'2007-09-01 06:22:05','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(255,29,'2007-08-31 18:10:02','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(256,29,'2007-08-31 18:15:07','Attempted Murder','Stubbsy has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(257,29,'2007-08-31 18:48:29','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(258,29,'2007-08-31 18:48:42','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(259,29,'2007-08-31 19:00:40','Attempted Mugging','EchO has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(260,29,'2007-08-31 19:00:58','Attempted Murder','EchO has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(262,29,'2007-08-31 19:39:04','Attempted Mugging','TriggerFinger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(265,29,'2007-08-31 20:01:29','Attempted Mugging','EchO has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(267,29,'2007-08-31 20:38:33','Attempted Pickpocketing','EchO has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(773,3,'2007-09-21 11:00:39','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(791,3,'2007-09-21 17:00:41','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(775,3,'2007-09-21 12:00:40','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(793,3,'2007-09-21 18:00:38','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(272,3,'2007-08-31 22:10:25','Attempted Pickpocketing','Phase has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(273,29,'2007-08-31 22:19:52','Attempted Murder','EchO has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(274,29,'2007-08-31 22:20:16','Attempted Pickpocketing','EchO has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(275,29,'2007-08-31 22:21:14','Attempted Mugging','Xanther has attempted to mug you in a dark alley! Thanks to your alertness and her clumsiness you were able to escape the dangerous situation!',0,0),
(853,3,'2007-09-27 05:16:43','Stalking Report','While you were stalking Roger you found out that they are very healthy, and are likely not to die anytime soon. You also found the victim to carry large amounts of money, they could have been buying something expensive, or they regularly carry a great deal of it. Roger appeared quite intellectual, they spent a good deal of time at the library.',0,0),
(552,29,'2007-09-18 11:29:41','Attempted Pickpocketing','MrTinketi has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(551,28,'2007-09-18 11:29:17','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $175934!',0,0),
(280,29,'2007-08-31 23:59:17','Attempted Murder','EchO has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(550,33,'2007-09-18 11:28:56','Murder Attempt','You have been brutally attacked on 18/09/2007 - 11:28:56. You are lucky to still find yourself in the land of the living! You have suffered 13 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(283,29,'2007-09-01 01:58:17','Attempted Mugging','heroCaesar has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(284,31,'2007-09-01 01:58:45','Victim of Pickpocketing','You were walking through a busy street in Amsterdam and suddenly noticed your bags feeling a lot lighter! After immediately checking your bag\'s contents you noticed a Filthy Old Sweater had gone missing! Looks like you\'ve been victim of a pickpocket!',0,0),
(285,31,'2007-09-01 03:29:55','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $52294!',0,0),
(286,29,'2007-09-01 03:30:55','Attempted Mugging','heroCaesar has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(287,31,'2007-09-01 03:43:10','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $9809!',0,0),
(566,33,'2007-09-20 10:44:08','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $0!',0,0),
(380,28,'2007-09-02 14:03:10','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(290,3,'2007-09-01 04:47:33','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(291,29,'2007-09-01 04:48:42','Attempted Mugging','Xanther has attempted to mug you in a dark alley! Thanks to your alertness and her clumsiness you were able to escape the dangerous situation!',0,0),
(292,29,'2007-09-01 04:49:01','Attempted Murder','Xanther has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(326,29,'2007-09-01 15:34:28','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(294,29,'2007-09-01 05:27:49','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(327,29,'2007-09-01 16:12:38','Attempted Mugging','Vilshade has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(328,30,'2007-09-01 16:12:39','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $99587!',0,0),
(307,3,'2007-09-01 06:42:35','Attempted Mugging','Phase has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(329,30,'2007-09-01 16:49:26','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $14938!',0,0),
(310,3,'2007-09-01 07:15:20','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(330,29,'2007-09-01 16:49:29','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(331,29,'2007-09-01 16:49:39','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(315,3,'2007-09-01 07:58:27','Attempted Murder','Phase has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(565,33,'2007-09-20 10:43:51','Murder Attempt','You have been brutally attacked on 20/09/2007 - 10:43:51. You are lucky to still find yourself in the land of the living! You have suffered 20 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(851,29,'2007-09-27 04:16:38','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',1,0),
(376,28,'2007-09-02 13:08:12','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(567,29,'2007-09-20 16:15:03','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(321,3,'2007-09-01 10:15:16','Attempted Pickpocketing','Roger has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(378,28,'2007-09-02 13:37:00','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(339,29,'2007-09-01 19:20:37','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(568,29,'2007-09-20 16:15:18','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(382,29,'2007-09-02 14:04:45','Attempted Murder','MrTinketi has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(341,29,'2007-09-02 01:35:49','Attempted Murder','Xanther has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(342,3,'2007-09-02 05:40:17','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(344,3,'2007-09-02 05:40:47','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(345,30,'2007-09-02 05:42:37','Attempted Pickpocketing','Xanther has attempted to pickpocket you in a busy street! Thanks to her stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(384,29,'2007-09-02 14:15:53','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(557,29,'2007-09-19 08:46:37','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(542,29,'2007-09-17 09:42:02','Failed Murder Attempt','heroCaesar has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(468,3,'2007-09-04 04:19:03','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront her and before she fled the scene you recognised her to be lilith!',0,0),
(365,28,'2007-09-02 08:58:02','Attempted Murder','lilith has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(871,13,'2007-10-01 12:00:48','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(872,9,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(873,10,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',0,0),
(558,29,'2007-09-19 08:47:02','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(387,29,'2007-09-02 15:12:29','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(424,31,'2007-09-03 08:29:48','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(389,28,'2007-09-02 15:37:48','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(569,33,'2007-09-20 16:15:49','Murder Attempt','You have been brutally attacked on 20/09/2007 - 16:15:49. You are lucky to still find yourself in the land of the living! You have suffered 20 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(570,33,'2007-09-20 16:16:05','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(564,29,'2007-09-20 10:43:08','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(392,28,'2007-09-02 16:31:25','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(422,28,'2007-09-03 08:13:56','Attempted Pickpocketing','lilith has attempted to pickpocket you in a busy street! Thanks to her stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(394,28,'2007-09-02 16:43:20','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(562,33,'2007-09-19 13:20:01','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $0!',0,0),
(563,29,'2007-09-20 10:42:58','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(398,29,'2007-09-02 17:04:45','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be Vilshade!',0,0),
(397,28,'2007-09-02 17:00:18','Stalking Report','While you were stalking Phase you found out that they had an average health rate, but seemed somewhat vulnerable still. You also found the victim to have very little money, and shop at the cheapest places. Phase appeared extremely intellectual, almost unusually.',0,0),
(555,33,'2007-09-19 08:45:26','Murder Attempt','You have been brutally attacked on 19/09/2007 - 08:45:26. You are lucky to still find yourself in the land of the living! You have suffered 15 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(421,28,'2007-09-03 08:12:14','Stalking Report','While you were stalking lilith you found out that they had an average health rate, but seemed somewhat vulnerable still. You also found the victim to have very little money, and shop at the cheapest places. lilith didn\'t apear very smart, infact they semt downright dumb at times.',0,0),
(400,30,'2007-09-02 17:52:52','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(561,33,'2007-09-19 13:18:42','Murder Attempt','You have been brutally attacked on 19/09/2007 - 13:18:42. You are lucky to still find yourself in the land of the living! You have suffered 15 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(402,30,'2007-09-02 17:58:50','Victim of Pickpocketing','You were walking through a busy street in Amsterdam and suddenly noticed your bags feeling a lot lighter! After immediately checking your bag\'s contents you noticed a Rusty Practise Gun had gone missing! Looks like you\'ve been victim of a pickpocket!',0,0),
(403,30,'2007-09-02 18:12:23','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(556,33,'2007-09-19 08:46:00','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $0!',0,0),
(405,28,'2007-09-02 18:17:17','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be Vilshade!',0,0),
(406,30,'2007-09-02 18:22:16','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(560,29,'2007-09-19 13:17:35','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(409,29,'2007-09-02 18:27:54','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be MrTinketi!',0,0),
(559,29,'2007-09-19 13:17:18','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(412,29,'2007-09-02 18:55:11','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be MrTinketi!',0,0),
(413,29,'2007-09-02 18:58:47','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(414,3,'2007-09-03 02:58:49','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(545,28,'2007-09-18 07:47:25','Victim of Pickpocketing','You were walking through a busy street in Amsterdam and suddenly noticed your bags feeling a lot lighter! After immediately checking your bag\'s contents you noticed a Samsung Cell Phone had gone missing! Looks like you\'ve been victim of a pickpocket!',0,0),
(416,3,'2007-09-03 03:00:07','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(434,30,'2007-09-03 09:38:32','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(433,30,'2007-09-03 09:26:11','Stalking Report','While you were stalking Phase you found out that they had an average health rate, but seemed somewhat vulnerable still. You also found the victim to have very little money, and shop at the cheapest places. Phase appeared extremely intellectual, almost unusually.',0,0),
(436,29,'2007-09-03 10:08:46','Attempted Pickpocketing','lilith has attempted to pickpocket you in a busy street! Thanks to her stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(501,31,'2007-09-06 10:54:28','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(554,29,'2007-09-18 14:03:10','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(442,28,'2007-09-03 11:00:39','Checking Account request approval.','After careful investigation of your request to open a new Checking Account with the Bank of Amsterdam no objections could be raised to processing your request further. You are now in the possession of a new Checking Account containing a balance of $10302. The bank account number of your new account is 4701413. You can always find this number in your character statistics screen.',0,0),
(471,3,'2007-09-04 04:45:11','Attempted Pickpocketing','lilith has attempted to pickpocket you in a busy street! Thanks to her stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(513,3,'2007-09-07 20:25:07','Failed Murder Attempt','Maluco has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(445,29,'2007-09-03 11:47:51','Attempted Murder','Vilshade has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(446,29,'2007-09-03 11:49:50','Attempted Pickpocketing','Vilshade has attempted to pickpocket you in a busy street! Thanks to his stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(874,23,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(875,19,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(514,24,'2007-09-07 20:25:44','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(449,29,'2007-09-03 14:52:31','Attempted Murder','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(515,3,'2007-09-07 20:25:44','Stalking Report','While you were stalking Maluco you found out that they had an average health rate, but seemed somewhat vulnerable still. You also found the victim to have very little money, and shop at the cheapest places. Maluco didn\'t apear very smart, infact they semt downright dumb at times.',0,0),
(451,29,'2007-09-03 15:11:06','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(516,3,'2007-09-07 20:26:43','Attempted Mugging','Maluco has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(754,37,'2007-09-21 03:19:12','Murder Attempt','You have been brutally attacked on 21/09/2007 - 03:19:12. You are lucky to still find yourself in the land of the living! You have suffered 18 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(848,35,'2007-09-26 16:31:47','Murder Attempt','You have been brutally attacked on 26/09/2007 - 16:31:47. You are lucky to still find yourself in the land of the living! You have suffered 15 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',1,0),
(517,24,'2007-09-07 20:27:02','Murder Attempt','You have been brutally attacked on 07/09/2007 - 20:27:02. You are lucky to still find yourself in the land of the living! You have suffered 20 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(752,29,'2007-09-21 03:18:41','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(753,29,'2007-09-21 03:18:49','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(456,29,'2007-09-03 16:56:32','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(769,29,'2007-09-21 08:57:03','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(539,29,'2007-09-16 14:08:36','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be MrTinketi!',0,0),
(748,3,'2007-09-21 02:57:00','Murder Attempt','You have been brutally attacked on 21/09/2007 - 02:57:00. You are lucky to still find yourself in the land of the living! You have suffered 7 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(749,3,'2007-09-21 02:57:16','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $164047!',0,0),
(772,37,'2007-09-21 08:58:36','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $2766!',0,0),
(849,35,'2007-09-26 16:32:01','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',1,0),
(536,31,'2007-09-14 11:00:41','Investment Account request approval.','After careful investigation of your request to open a new Investment Account with the Bank of Amsterdam no objections could be raised to processing your request further. You are now in the possession of a new Investment Account containing a balance of $5000. The bank account number of your new account is 6739940. You can always find this number in your character statistics screen.',0,0),
(534,31,'2007-09-10 05:14:18','Murder Attempt','You have been brutally attacked on 10/09/2007 - 05:14:18. You are lucky to still find yourself in the land of the living! You have suffered 20 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(476,31,'2007-09-04 09:48:54','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(537,28,'2007-09-16 14:06:28','Murder Attempt','You have been brutally attacked on 16/09/2007 - 14:06:28. You are lucky to still find yourself in the land of the living! You have suffered 18 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(498,29,'2007-09-05 15:45:08','Failed Murder Attempt','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(538,29,'2007-09-16 14:07:42','Failed Murder Attempt','MrTinketi has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(884,8,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(885,11,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(770,29,'2007-09-21 08:57:25','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(771,37,'2007-09-21 08:58:15','Murder Attempt','You have been brutally attacked on 21/09/2007 - 08:58:15. You are lucky to still find yourself in the land of the living! You have suffered 15 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(532,3,'2007-09-10 04:21:50','Attempted Pickpocketing','lilith has attempted to pickpocket you in a busy street! Thanks to her stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(526,3,'2007-09-09 06:39:56','Stalking Report','While you were stalking lilith you found out that they are very healthy, and are likely not to die anytime soon. You also found the victim to have very little money, and shop at the cheapest places. lilith didn\'t apear very smart, infact they semt downright dumb at times.',0,0),
(876,3,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',0,0),
(877,20,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(878,28,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(879,24,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(880,4,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(881,17,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(882,5,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(883,12,'2007-10-01 12:00:49','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(524,3,'2007-09-09 06:36:10','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront her and before she fled the scene you recognised her to be lilith!',0,0),
(527,3,'2007-09-09 06:42:16','Murder Attempt','You have been brutally attacked on 09/09/2007 - 06:42:16. You are lucky to still find yourself in the land of the living! You have suffered 1 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(777,3,'2007-09-21 13:00:41','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(531,3,'2007-09-10 04:21:35','Failed Murder Attempt','lilith has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(492,29,'2007-09-05 09:56:13','Attempted Pickpocketing','lilith has attempted to pickpocket you in a busy street! Thanks to her stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!',0,0),
(508,24,'2007-09-07 16:45:56','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(511,24,'2007-09-07 16:51:42','Failed Murder Attempt','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(512,24,'2007-09-07 16:55:41','Murder Attempt','You have been brutally attacked on 07/09/2007 - 16:55:41. You are lucky to still find yourself in the land of the living! You have suffered 18 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(546,28,'2007-09-18 07:48:53','Murder Attempt','You have been brutally attacked on 18/09/2007 - 07:48:53. You are lucky to still find yourself in the land of the living! You have suffered 6 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(797,3,'2007-09-21 20:47:47','Failed Murder Attempt','lilith has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(800,3,'2007-09-21 21:00:39','Investment Account request approval.','After careful investigation of your request to open a new Investment Account with the Bank of Amsterdam no objections could be raised to processing your request further. You are now in the possession of a new Investment Account containing a balance of $8940. The bank account number of your new account is 6405734. You can always find this number in your character statistics screen.',0,0),
(799,3,'2007-09-21 20:52:30','Auction Successful!','This is an automated message from the Amsterdam auction house to inform you that your item, \'Filthy Old Trousers\', has been successfully sold! The auction house took a fee of 0.05% ($100) from your profits. The remaining profits, an amount of $1900 has been sent to you in cash!',0,0),
(801,29,'2007-09-22 11:36:02','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(802,29,'2007-09-22 11:36:14','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(803,37,'2007-09-22 11:37:32','Murder Attempt','You have been brutally attacked on 22/09/2007 - 11:37:32. You are lucky to still find yourself in the land of the living! You have suffered 18 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(863,3,'2007-09-28 07:53:48','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $140819!',0,0),
(780,3,'2007-09-21 14:00:51','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(782,37,'2007-09-21 14:50:34','Murder Attempt','You have been brutally attacked on 21/09/2007 - 14:50:34. You are lucky to still find yourself in the land of the living! You have suffered 15 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(785,29,'2007-09-21 14:52:49','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(784,29,'2007-09-21 14:52:35','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(786,3,'2007-09-21 15:00:39','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(805,31,'2007-09-22 13:00:39','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'XBox 360\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(864,3,'2007-09-28 07:54:31','Murder Attempt','You have been brutally attacked on 28/09/2007 - 07:54:31. You are lucky to still find yourself in the land of the living! You have suffered 6 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(804,31,'2007-09-22 13:00:39','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Samsung Cell Phone\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(789,3,'2007-09-21 16:00:43','Auction Successful!','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Filthy Old Hat\', has been successfully sold! The auction house took a fee of 0% ($0) from your profits. The remaining profits, an amount of $1050 has been sent to you in cash!',0,0),
(806,31,'2007-09-22 13:00:39','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Gameboy Advance\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(807,29,'2007-09-22 17:12:31','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(809,37,'2007-09-22 17:12:59','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $603967!',0,0),
(810,37,'2007-09-22 17:13:11','Murder Attempt','You have been brutally attacked on 22/09/2007 - 17:13:11. You are lucky to still find yourself in the land of the living! You have suffered 8 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(812,29,'2007-09-23 13:25:13','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(813,29,'2007-09-23 13:25:26','Attempted Mugging','EchOx has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(814,37,'2007-09-23 13:26:01','Murder Attempt','You have been brutally attacked on 23/09/2007 - 13:26:01. You are lucky to still find yourself in the land of the living! You have suffered 18 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(815,37,'2007-09-23 13:26:26','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $90595!',0,0),
(818,3,'2007-09-23 21:48:49','Stalking Report','While you were stalking Roger you found out that they are very healthy, and are likely not to die anytime soon. You also found the victim to be carrying extremely large amounts of money. They bought only the most expensive items and went only to the most expensive bars. Roger appeared quite intellectual, they spent a good deal of time at the library.',0,0),
(819,3,'2007-09-24 07:38:32','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. He searched your pockets successfully and ran off with your money before you could get a good look at him! You lost $82259!',0,0),
(821,3,'2007-09-24 07:41:27','Murder Attempt','You have been brutally attacked on 24/09/2007 - 07:41:27. You are lucky to still find yourself in the land of the living! You have suffered 5 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(824,38,'2007-09-24 10:44:27','Auction Won','This is an automated message from the Amsterdam Auction House to inform you that you have won an auction for \'Brushed Metal Gun\'. The item has been sent to your mailbox where it should arrive shortly. For future purchases please remember to visit us again in Amsterdam!',0,0),
(826,38,'2007-09-24 11:00:41','Auction Won','This is an automated message from the Amsterdam Auction House to inform you that you have won an auction for \'Brushed Metal Gun\'. The item has been sent to your mailbox where it should arrive shortly. For future purchases please remember to visit us again in Amsterdam!',0,0),
(827,29,'2007-09-24 11:43:48','Failed Murder Attempt','EchOx has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(828,37,'2007-09-24 11:44:35','Victim of Mugging','While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. She searched your pockets successfully and ran off with your money before you could get a good look at her! You lost $10505!',0,0),
(829,37,'2007-09-24 11:45:20','Murder Attempt','You have been brutally attacked on 24/09/2007 - 11:45:20. You are lucky to still find yourself in the land of the living! You have suffered 13 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0,0),
(831,13,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(832,9,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(834,23,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(835,19,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(836,3,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',0,0),
(837,20,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(838,28,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',0,0),
(839,24,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',0,0),
(840,4,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(841,17,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(842,5,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(843,12,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(844,8,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(845,11,'2007-09-24 12:00:40','Unemployment Check','You drew $1500 on your unemployment check today.',1,0),
(854,3,'2007-09-27 05:28:07','Attempted Mugging','Roger has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(855,3,'2007-09-27 05:28:20','Failed Murder Attempt','Roger has attempted to take your life! If it wasn\'t for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn\'t happen and you\'re ready to take revenge!',0,0),
(857,31,'2007-09-27 10:00:41','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Playstation 3\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(858,31,'2007-09-27 10:00:41','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Small Plastic Bag\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(859,31,'2007-09-27 10:00:41','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'XBox 360\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(860,31,'2007-09-27 11:00:43','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Samsung Cell Phone\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(861,31,'2007-09-27 12:00:39','Auction Unsuccessfull','This is an automated message from the Amsterdam Auction House to inform you that your item, \'Playstation 3\', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the Amsterdam Auction House!',0,0),
(886,38,'2007-10-05 00:14:54','Victim of Mailbox Torching','When you went to check for new mail in your mailbox, you saw black clouds of smoke rising from it! As you got even closer it became obvious someone torched it! It looks like you\'re going to have to pay for a new mailbox soon - but that is the least of your worries. What if there was something valuable in it as well?',0,0),
(887,38,'2007-10-05 00:23:08','Victim of Mailbox Torching','When you went to check for new mail in your mailbox, you saw black clouds of smoke rising from it! As you got even closer it became obvious someone torched it! It looks like you\'re going to have to pay for a new mailbox soon - but that is the least of your worries. What if there was something valuable in it as well?',0,0),
(893,29,'2007-10-17 01:07:27','Attempted Bank Hack','Roger has attempted to transfer funds from your bank account after managing to hack into the Amsterdam Bank\'s mainframe! Thanks to the system administrator\'s alertness and their nifty security programs, you didn\'t loose any money!',1,0),
(894,29,'2007-10-17 11:41:57','Bank Account Hacked','Someone managed to hack into the transaction server of the Amsterdam Bank and managed to transfer funds from bank account number 3598857, which is one of your accounts, to another bank account! System administrators have not been able to restore your original balance. You lost an amount of $10000.',1,0),
(895,10,'2007-10-17 12:15:46','Bank Account Hacked','Someone managed to hack into the transaction server of the Amsterdam Bank and managed to transfer funds from bank account number 2374805, which is one of your accounts, to another bank account! System administrators have not been able to restore your original balance. You lost an amount of $50.',1,0),
(897,3,'2007-10-21 23:43:14','Bank Account Hacked','Someone managed to hack into the transaction server of the Amsterdam Bank and managed to transfer funds from bank account number 5619127, which is one of your accounts, to another bank account! System administrators have not been able to restore your original balance. You lost an amount of $5000.',1,0),
(909,2,'2007-11-21 11:47:48','You have been sacked!','Bank President Roger has given you the sack! In an ever changing organisation like he one you used to be part of, relics like yourself just don\'t fit anymore! You\'re going to have to look for other jobs now!',0,0),
(910,15,'2007-11-22 20:13:04','Trade Request Declined','Aaron has declined your request to trade vehicles.',0,0),
(911,15,'2007-11-22 21:04:55','Vehicle Traded','Aaron has accepted your offer of a vehicle trade.',0,0),
(951,23,'2007-11-27 18:54:26','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(950,21,'2007-11-27 11:45:55','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(952,9,'2007-11-27 18:54:27','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(953,3,'2007-11-27 18:54:32','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(954,13,'2007-11-27 18:54:33','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(955,7,'2007-11-27 18:54:33','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(956,23,'2007-11-27 18:58:31','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(957,18,'2007-11-27 18:58:32','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(958,31,'2007-11-27 18:58:32','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(959,9,'2007-11-27 19:08:37','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(960,14,'2007-11-27 19:08:38','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(961,10,'2007-11-27 19:08:38','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(962,23,'2007-11-27 19:08:52','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(963,21,'2007-11-27 19:08:53','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(964,9,'2007-11-27 19:08:54','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(965,18,'2007-11-27 19:08:54','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(966,20,'2007-11-27 19:08:55','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(967,35,'2007-11-27 19:08:55','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(968,11,'2007-11-27 19:08:56','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(969,9,'2007-11-27 19:08:57','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(970,29,'2007-11-27 19:08:57','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(971,25,'2007-11-27 19:08:57','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(972,9,'2007-11-27 19:08:58','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(973,37,'2007-11-27 19:08:58','Gregov\'s Charity','You have recieved a $1000 sweepstakes check from the Habaal charity.',1,0),
(974,45,'2007-11-28 15:45:47','Attempted Mugging','Aaron has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',1,0),
(975,46,'2007-11-28 18:04:24','Attempted Mugging','Aaron has attempted to mug you in a dark alley! Thanks to your alertness and his clumsiness you were able to escape the dangerous situation!',0,0),
(976,46,'2007-11-28 18:19:21','Are you being followed?','Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don\'t know why, but its obvious someone is keeping a watch on you!',0,0),
(979,2,'2007-11-28 21:33:31','Attempted Bank Hack','Aaron has attempted to transfer funds from your bank account after managing to hack into the Amsterdam Bank\'s mainframe! Thanks to the system administrator\'s alertness and their nifty security programs, you didn\'t loose any money!',1,0),
(980,46,'2007-11-29 03:18:05','Attempted Stalking','You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront him and before he fled the scene you recognised him to be Aaron!',0,0),
(981,15,'2007-11-30 17:08:33','Vehicle Traded','Habaal has accepted your offer of a vehicle trade.',0,0),
(982,46,'2007-11-30 17:17:39','Vehicle Traded','Aaron has accepted your offer of a vehicle trade.',0,0),
(983,15,'2007-11-30 17:20:50','Trade Request Declined','Aaron has declined your request to trade vehicles.',0,0),
(984,15,'2007-11-30 17:22:17','Trade Request Declined','Aaron has declined your request to trade vehicles.',0,0),
(985,15,'2007-11-30 17:22:20','Trade Request Declined','Aaron has declined your request to trade vehicles.',0,0),
(987,15,'2007-12-01 03:12:45','Missed Flight','You missed your flight to Amsterdam, refunds are not possible at this point.',0,0);

-- Table structure for table `events_large`
DROP TABLE IF EXISTS `events_large`;

CREATE TABLE `events_large` (
  `levent_id` bigint(20) NOT NULL auto_increment,
  `char_id` bigint(20) NOT NULL default '0',
  `event_new` tinyint(1) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `subject` varchar(255) collate latin1_general_ci NOT NULL default '',
  `content` text collate latin1_general_ci NOT NULL,
  `death_message` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`levent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `events_large`
insert into `events_large` values
(4,2,0,'2007-08-31 07:32:37','','<h1>You survived a murder attempt!</h1><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><p><strong>You have been brutally attacked on 31/08/2007 - 07:32:37. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(3,3,0,'2007-08-31 07:24:46','','<h1>You survived a murder attempt!</h1><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><p><strong>You have been brutally attacked on 31/08/2007 - 07:24:46. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(5,3,0,'2007-08-31 07:33:46','','<h1>You survived a murder attempt!</h1><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><p><strong>You have been brutally attacked on 31/08/2007 - 07:33:46. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(6,2,0,'2007-08-31 07:37:41','','<h1>You survived a murder attempt!</h1><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><p><strong>You have been brutally attacked on 31/08/2007 - 07:37:41. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(7,3,0,'2007-08-31 07:41:37','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 07:41:37. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(8,3,0,'2007-08-31 07:43:23','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 07:43:23. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(9,2,0,'2007-08-31 07:46:01','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 07:46:01. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(10,29,0,'2007-08-31 09:52:12','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 09:52:12. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(11,3,0,'2007-08-31 10:01:05','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 10:01:05. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(12,29,0,'2007-08-31 10:58:48','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 10:58:48. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(13,3,0,'2007-08-31 11:01:34','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 11:01:34. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(14,20,0,'2007-08-31 12:02:34','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 12:02:34. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(15,29,0,'2007-08-31 16:55:12','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 16:55:12. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(16,2,0,'2007-08-31 16:55:42','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 16:55:42. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(17,2,0,'2007-08-31 17:58:42','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 17:58:42. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(95,3,0,'2007-09-21 02:57:00','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 21/09/2007 - 02:57:00. You are lucky to be alive! Your attacker delivered 7 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(94,33,0,'2007-09-20 16:15:49','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 20/09/2007 - 16:15:49. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(20,29,0,'2007-08-31 20:01:50','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 20:01:50. You are lucky to be alive! Your attacker delivered 1 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(21,29,0,'2007-08-31 21:15:01','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 21:15:01. You are lucky to be alive! Your attacker delivered 1 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(93,33,0,'2007-09-20 10:43:51','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 20/09/2007 - 10:43:51. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(92,33,0,'2007-09-19 13:18:42','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 19/09/2007 - 13:18:42. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(91,33,0,'2007-09-19 08:45:26','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 19/09/2007 - 08:45:26. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(25,29,0,'2007-08-31 22:46:31','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 31/08/2007 - 22:46:31. You are lucky to be alive! Your attacker delivered 6 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(90,2,0,'2007-09-18 13:57:57','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 18/09/2007 - 13:57:57. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(28,31,0,'2007-09-01 01:59:00','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 01:59:00. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(29,31,0,'2007-09-01 03:30:09','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 03:30:09. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(30,31,0,'2007-09-01 03:43:36','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 03:43:36. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(31,3,0,'2007-09-01 04:47:49','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 04:47:49. You are lucky to be alive! Your attacker delivered 11 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(32,31,0,'2007-09-01 05:48:43','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 05:48:43. You are lucky to be alive! Your attacker delivered 25 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(89,15,0,'2007-09-18 11:28:56','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 18/09/2007 - 11:28:56. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(33,3,0,'2007-09-01 06:48:59','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 06:48:59. You are lucky to be alive! Your attacker delivered 6 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(34,29,0,'2007-09-01 06:49:51','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 06:49:51. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(35,2,0,'2007-09-01 07:54:09','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 07:54:09. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(36,3,0,'2007-09-01 07:54:55','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 07:54:55. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(37,3,0,'2007-09-01 09:43:40','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 09:43:40. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(38,3,0,'2007-09-01 10:44:53','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 10:44:53. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(39,2,0,'2007-09-01 10:52:51','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 10:52:51. You are lucky to be alive! Your attacker delivered 2 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(40,30,0,'2007-09-01 15:35:27','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 15:35:27. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(41,30,0,'2007-09-01 16:49:54','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 16:49:54. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(42,29,0,'2007-09-01 17:40:45','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 17:40:45. You are lucky to be alive! Your attacker delivered 7 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(43,2,0,'2007-09-01 18:05:58','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 18:05:58. You are lucky to be alive! Your attacker delivered 2 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(44,2,0,'2007-09-01 18:53:57','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 18:53:57. You are lucky to be alive! Your attacker delivered 11 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(45,35,0,'2007-09-01 18:59:47','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 18:59:47. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(46,29,0,'2007-09-01 19:13:41','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 01/09/2007 - 19:13:41. You are lucky to be alive! Your attacker delivered 2 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(47,3,0,'2007-09-02 01:00:30','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 01:00:30. You are lucky to be alive! Your attacker delivered 6 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(48,30,0,'2007-09-02 05:41:07','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 05:41:07. You are lucky to be alive! Your attacker delivered 14 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(49,30,0,'2007-09-02 05:41:16','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 05:41:16. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(50,13,0,'2007-09-02 05:57:55','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 05:57:55. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(51,2,0,'2007-09-02 06:11:57','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 06:11:57. You are lucky to be alive! Your attacker delivered 2 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(52,13,0,'2007-09-02 07:00:53','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 07:00:53. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(53,28,0,'2007-09-02 08:30:01','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 08:30:01. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(54,13,0,'2007-09-02 08:57:03','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 08:57:03. You are lucky to be alive! Your attacker delivered 6 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(55,13,0,'2007-09-02 09:05:58','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 09:05:58. You are lucky to be alive! Your attacker delivered 3 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(56,10,0,'2007-09-02 10:06:16','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 10:06:16. You are lucky to be alive! Your attacker delivered 25 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(57,29,0,'2007-09-02 10:09:29','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 10:09:29. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(58,29,0,'2007-09-02 10:11:29','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 10:11:29. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(59,28,0,'2007-09-02 13:00:03','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 13:00:03. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(60,28,0,'2007-09-02 13:10:22','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 13:10:22. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(61,28,0,'2007-09-02 14:10:47','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 14:10:47. You are lucky to be alive! Your attacker delivered 23 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(62,28,0,'2007-09-02 14:11:09','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 14:11:09. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(63,30,0,'2007-09-02 17:04:29','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 17:04:29. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(64,28,0,'2007-09-02 17:53:10','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 17:53:10. You are lucky to be alive! Your attacker delivered 14 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(65,29,0,'2007-09-02 18:48:58','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 02/09/2007 - 18:48:58. You are lucky to be alive! Your attacker delivered 2 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(66,2,0,'2007-09-03 03:01:25','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 03:01:25. You are lucky to be alive! Your attacker delivered 2 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(67,13,0,'2007-09-03 07:18:23','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 07:18:23. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(68,28,0,'2007-09-03 08:14:16','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 08:14:16. You are lucky to be alive! Your attacker delivered 3 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(69,29,0,'2007-09-03 08:24:45','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 08:24:45. You are lucky to be alive! Your attacker delivered 0 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(70,31,0,'2007-09-03 08:28:22','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 08:28:22. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(71,30,0,'2007-09-03 09:24:37','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 09:24:37. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(72,13,0,'2007-09-03 10:18:54','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 10:18:54. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(73,30,0,'2007-09-03 12:24:41','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 12:24:41. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(74,2,0,'2007-09-03 14:58:54','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 14:58:54. You are lucky to be alive! Your attacker delivered 9 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(75,2,0,'2007-09-03 15:00:04','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 15:00:04. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(76,29,0,'2007-09-03 15:01:43','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 15:01:43. You are lucky to be alive! Your attacker delivered 3 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(77,29,0,'2007-09-03 15:01:59','','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 03/09/2007 - 15:01:59. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(78,30,0,'2007-09-03 16:07:49','','<h1>You are dead, Vilshade!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally murdered on 03/09/2007 - 16:07:49. Your murderer left a message on your body, saying:</strong><br /><i>\"RIP to the fallen\"</i></p><p><strong>Rest in Pieces</strong></p><p><a href=\'http://www.red-republic.com/account.php\'>Account</a></p>',1),
(79,10,0,'2007-09-04 14:46:23','Murder!','You have been brutally attacked on 04/09/2007 - 14:46:23. You are lucky to still find yourself in the land of the living! You have suffered 15 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!',0),
(80,24,0,'2007-09-07 16:55:41','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 07/09/2007 - 16:55:41. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(81,24,0,'2007-09-07 20:27:02','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 07/09/2007 - 20:27:02. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(82,35,0,'2007-09-09 00:15:24','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 09/09/2007 - 00:15:24. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(83,3,0,'2007-09-09 06:42:16','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 09/09/2007 - 06:42:16. You are lucky to be alive! Your attacker delivered 1 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(84,13,0,'2007-09-09 06:43:01','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 09/09/2007 - 06:43:01. You are lucky to be alive! Your attacker delivered 11 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(85,13,0,'2007-09-10 04:23:11','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 10/09/2007 - 04:23:11. You are lucky to be alive! Your attacker delivered 9 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(86,31,0,'2007-09-10 05:14:18','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 10/09/2007 - 05:14:18. You are lucky to be alive! Your attacker delivered 20 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(87,28,0,'2007-09-16 14:06:28','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 16/09/2007 - 14:06:28. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(88,28,0,'2007-09-18 07:48:53','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 18/09/2007 - 07:48:53. You are lucky to be alive! Your attacker delivered 6 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(96,37,0,'2007-09-21 03:19:12','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 21/09/2007 - 03:19:12. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(97,37,0,'2007-09-21 08:58:15','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 21/09/2007 - 08:58:15. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(98,37,0,'2007-09-21 14:50:34','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 21/09/2007 - 14:50:34. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(99,13,0,'2007-09-21 20:50:02','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 21/09/2007 - 20:50:02. You are lucky to be alive! Your attacker delivered 12 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(100,37,0,'2007-09-22 11:37:32','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 22/09/2007 - 11:37:32. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(101,37,0,'2007-09-22 17:13:11','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 22/09/2007 - 17:13:11. You are lucky to be alive! Your attacker delivered 8 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(102,37,0,'2007-09-23 13:26:01','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 23/09/2007 - 13:26:01. You are lucky to be alive! Your attacker delivered 18 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(103,3,0,'2007-09-24 07:41:27','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 24/09/2007 - 07:41:27. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(104,2,0,'2007-09-24 07:46:04','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 24/09/2007 - 07:46:04. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(105,37,0,'2007-09-24 11:45:20','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 24/09/2007 - 11:45:20. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(106,2,0,'2007-09-26 08:46:14','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 26/09/2007 - 08:46:14. You are lucky to be alive! Your attacker delivered 11 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(107,35,1,'2007-09-26 16:31:47','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 26/09/2007 - 16:31:47. You are lucky to be alive! Your attacker delivered 15 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(108,2,0,'2007-09-27 05:40:25','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 27/09/2007 - 05:40:25. You are lucky to be alive! Your attacker delivered 5 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(109,3,0,'2007-09-28 07:54:31','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 28/09/2007 - 07:54:31. You are lucky to be alive! Your attacker delivered 6 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(110,2,0,'2007-09-28 07:56:44','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 28/09/2007 - 07:56:44. You are lucky to be alive! Your attacker delivered 4 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0),
(111,10,0,'2007-09-28 16:16:24','Murder!','<h1>You survived a murder attempt!</h1><br /><br /><img src=\'http://www.red-republic.com/images/murder_attempt.png\' alt=\'\' /><br /><p><strong>You have been brutally attacked on 28/09/2007 - 16:16:24. You are lucky to be alive! Your attacker delivered 13 damage to you - you had better find yourself a hospital to treat your wounds!</strong></p',0);

-- Table structure for table `events_requests`
DROP TABLE IF EXISTS `events_requests`;

CREATE TABLE `events_requests` (
  `id` bigint(20) NOT NULL auto_increment,
  `char_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(225) collate latin1_general_ci NOT NULL,
  `html_message` text collate latin1_general_ci NOT NULL,
  `handler_filename` varchar(225) collate latin1_general_ci NOT NULL,
  `answered` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `events_requests`
insert into `events_requests` values
(5,35,'2007-11-08 00:00:00','Debug Request','This is a debug event, to view the status of the event request system. If this is working you should be able to select an option, and a file will parse your selection. After this is all complete, this event should not show up in your right-top hand navigation.\r\n<br /><br />\r\n<table style=\"width: 70%; text-align: center;\">\r\n<tr><td>\r\n<input type=\'radio\' value=\'yes\' name=\'myinput\' /> Debug Option #1<br />\r\n<input type=\'radio\' value=\'no\' name=\'myinput\' /> Debug Option #2<br />\r\n</td></tr>\r\n<tr><td><center><input type=\'submit\' class=\'std_input\' value=\'Submit\' /></center></td></tr>\r\n<tr><td><span style=\"font-size: 10px;\">The event request system can add an event in two ways, both of them are done from the Player class. The first way is to create a custom event with addEventRequest(), the other is to create an event from a pre-made event (or template) by calling addEventRequestFromTemplate(). This <i>Debug Request</i> event was created from a template.</span></td></tr>\r\n</table>','debug_event.php',0),
(3,15,'2007-11-08 00:00:00','Debug Request','This is a debug event, to view the status of the event request system. If this is working you should be able to select an option, and a file will parse your selection. After this is all complete, this even should not show up in your right-top hand navigation.\r\n<br /><br />\r\n<table style=\"width: 70%; text-align: center;\">\r\n<tr><td>\r\n<input type=\'radio\' value=\'yes\' name=\'myinput\' /> Debug Option #1<br />\r\n<input type=\'radio\' value=\'no\' name=\'myinput\' /> Debug Option #2<br />\r\n</td></tr>\r\n<tr><td><center><input type=\'submit\' class=\'std_input\' value=\'Submit\' /></center></td></tr>\r\n<tr><td><span style=\"font-size: 10px;\">The event request system can add an event in two ways, both of them are done frome the Player class. The first way is to create a custom event with addEventRequest(), the other is to create an event from a pre-made event (or template) by calling addEventRequestFromTemplate(). This <i>Debug Request</i> event was created from a template.</span></td></tr>\r\n</table>','debug_event.php',1),
(4,2,'2007-11-08 00:00:00','Debug Request','This is a debug event, to view the status of the event request system. If this is working you should be able to select an option, and a file will parse your selection. After this is all complete, this event should not show up in your right-top hand navigation.\r\n<br /><br />\r\n<table style=\"width: 70%; text-align: center;\">\r\n<tr><td>\r\n<input type=\'radio\' value=\'yes\' name=\'myinput\' /> Debug Option #1<br />\r\n<input type=\'radio\' value=\'no\' name=\'myinput\' /> Debug Option #2<br />\r\n</td></tr>\r\n<tr><td><center><input type=\'submit\' class=\'std_input\' value=\'Submit\' /></center></td></tr>\r\n<tr><td><span style=\"font-size: 10px;\">The event request system can add an event in two ways, both of them are done from the Player class. The first way is to create a custom event with addEventRequest(), the other is to create an event from a pre-made event (or template) by calling addEventRequestFromTemplate(). This <i>Debug Request</i> event was created from a template.</span></td></tr>\r\n</table>','debug_event.php',1),
(7,15,'2007-11-22 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(8,15,'2007-11-22 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(9,15,'2007-11-22 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(10,15,'2007-11-22 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(11,15,'2007-11-22 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(12,15,'2007-11-22 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(13,15,'2007-11-23 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(14,15,'2007-11-23 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(15,15,'2007-11-24 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(16,46,'2007-11-30 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a \'83 Station Waggon that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(17,15,'2007-11-30 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'46\' /><a href=\'http://localhost/red-republic/profile.php?id=46\' style=\'text-decoration: none;\'>Habaal</a> is currently interested in trading vehicles with you. Habaal currently has a Lincoln Town Car that is in good shape. You can find out more about Habaal\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(18,15,'2007-11-30 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(19,15,'2007-11-30 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(20,15,'2007-11-30 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1),
(21,46,'2007-11-30 00:00:00','Vehicle Trade Offer','<input type=\'hidden\' name=\'chrid\' value=\'15\' /><a href=\'http://localhost/red-republic/profile.php?id=15\' style=\'text-decoration: none;\'>Aaron</a> is currently interested in trading vehicles with you. Aaron currently has a Lincoln Town Car that is in good shape. You can find out more about Aaron\'s vehicle at any garage, in the <a href=\"http://localhost/red-republic/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />','trade_vehicle.php',1);

-- Table structure for table `events_templates`
DROP TABLE IF EXISTS `events_templates`;

CREATE TABLE `events_templates` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(225) collate latin1_general_ci NOT NULL,
  `html_message` text collate latin1_general_ci NOT NULL,
  `handler_filename` varchar(225) collate latin1_general_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `events_templates`
insert into `events_templates` values
(1,'Debug Request','This is a debug event, to view the status of the event request system. If this is working you should be able to select an option, and a file will parse your selection. After this is all complete, this event should not show up in your right-top hand navigation.\r\n<br /><br />\r\n<table style=\"width: 70%; text-align: center;\">\r\n<tr><td>\r\n<input type=\'radio\' value=\'yes\' name=\'myinput\' /> Debug Option #1<br />\r\n<input type=\'radio\' value=\'no\' name=\'myinput\' /> Debug Option #2<br />\r\n</td></tr>\r\n<tr><td><center><input type=\'submit\' class=\'std_input\' value=\'Submit\' /></center></td></tr>\r\n<tr><td><span style=\"font-size: 10px;\">The event request system can add an event in two ways, both of them are done from the Player class. The first way is to create a custom event with addEventRequest(), the other is to create an event from a pre-made event (or template) by calling addEventRequestFromTemplate(). This <i>Debug Request</i> event was created from a template.</span></td></tr>\r\n</table>','debug_event.php');

-- Table structure for table `faqbook`
DROP TABLE IF EXISTS `faqbook`;

CREATE TABLE `faqbook` (
  `id` bigint(20) NOT NULL auto_increment,
  `question` varchar(225) collate latin1_general_ci NOT NULL default '',
  `answer` text collate latin1_general_ci NOT NULL,
  `credit` varchar(225) collate latin1_general_ci NOT NULL default '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `faqbook`
insert into `faqbook` values
(4,'How often am I able to earn?','You can earn every 3 minutes.',''),
(5,'How often am I able to commit a crime?','You can commit a crime every 25 minutes.',''),
(6,'How often am I able to study or train?','You are able to study and train every 15 minutes.',''),
(7,'Am I able to have more than one account on Red Republic?','No, it is forbidden to have more than one account registered with Red Republic. (This is called duping and is not allowed)','');

-- Table structure for table `forums_categories`
DROP TABLE IF EXISTS `forums_categories`;

CREATE TABLE `forums_categories` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(50) collate latin1_general_ci NOT NULL default '',
  `auth_level` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `forums_categories`
insert into `forums_categories` values
(1,'Red Republic Development Forums',0),
(2,'Red Republic Testing',0);

-- Table structure for table `forums_forums`
DROP TABLE IF EXISTS `forums_forums`;

CREATE TABLE `forums_forums` (
  `id` bigint(20) NOT NULL auto_increment,
  `cat_id` bigint(20) NOT NULL default '0',
  `name` varchar(50) collate latin1_general_ci NOT NULL default '',
  `auth_level` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `forums_forums`
insert into `forums_forums` values
(1,1,'Development Announcements',0),
(2,1,'Game Feature Suggestions',0),
(3,1,'Game Storyline Suggestions',0),
(5,1,'General Chat',0),
(6,2,'Bug Reports',0),
(7,1,'Developer\\\'s Forum',10);

-- Table structure for table `forums_posttracker`
DROP TABLE IF EXISTS `forums_posttracker`;

CREATE TABLE `forums_posttracker` (
  `tracker_id` bigint(20) NOT NULL auto_increment,
  `account_id` bigint(20) NOT NULL default '0',
  `reply_id` bigint(20) NOT NULL default '0',
  `last_seen` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tracker_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `forums_posttracker`
insert into `forums_posttracker` values
(1,1,13,'2007-02-17 16:29:19'),
(3,1,14,'2007-02-16 03:48:47'),
(4,1,16,'2007-09-02 10:29:07'),
(5,1,8,'2007-02-17 16:30:02'),
(6,1,12,'2007-02-18 06:24:21'),
(7,1,1,'2007-09-20 12:30:17'),
(8,1,2,'2007-09-20 12:30:50'),
(9,1,3,'2007-02-16 04:05:51'),
(10,1,6,'2007-09-20 12:31:55'),
(11,18,6,'2007-02-16 04:47:30'),
(12,18,16,'2007-02-16 04:48:30'),
(13,1,17,'2007-02-16 12:12:02'),
(14,1,18,'2007-02-17 10:15:21'),
(15,14,16,'2007-02-16 23:53:48'),
(16,14,18,'2007-02-16 23:54:26'),
(17,20,18,'2007-02-17 16:27:25'),
(18,1,19,'2007-02-17 16:30:07'),
(19,1,20,'2007-02-17 16:33:07'),
(20,1,21,'2007-02-17 16:34:20'),
(21,1,22,'2007-02-17 16:42:06'),
(22,20,23,'2007-02-17 16:42:57'),
(23,1,23,'2007-02-17 17:02:18'),
(24,20,22,'2007-02-17 16:43:36'),
(25,20,20,'2007-02-17 16:46:41'),
(26,1,24,'2007-02-17 16:49:48'),
(27,1,25,'2007-02-17 16:51:40'),
(28,20,26,'2007-02-17 16:53:46'),
(29,1,26,'2007-02-21 08:05:36'),
(30,20,25,'2007-02-17 17:11:17'),
(31,20,19,'2007-02-17 16:55:22'),
(32,20,8,'2007-02-17 16:55:45'),
(33,20,16,'2007-02-18 11:30:08'),
(34,1,27,'2007-02-17 17:07:11'),
(35,20,28,'2007-02-17 17:09:49'),
(36,1,28,'2007-02-17 17:12:14'),
(37,1,29,'2007-02-17 17:12:58'),
(38,20,27,'2007-02-17 17:13:56'),
(39,20,29,'2007-02-17 17:16:38'),
(40,1,30,'2007-02-17 17:16:38'),
(41,21,25,'2007-02-17 17:17:45'),
(42,1,31,'2007-02-17 17:18:09'),
(43,21,3,'2007-02-17 17:20:09'),
(44,1,32,'2007-02-17 17:20:31'),
(45,21,19,'2007-02-17 17:20:34'),
(46,21,22,'2007-02-17 17:28:30'),
(47,20,32,'2007-02-17 17:35:30'),
(48,20,31,'2007-02-17 17:28:20'),
(49,21,32,'2007-02-17 17:28:37'),
(50,21,31,'2007-02-17 17:28:52'),
(51,21,33,'2007-02-17 17:45:43'),
(52,1,33,'2007-02-17 17:45:47'),
(53,20,33,'2007-02-17 17:45:33'),
(54,1,34,'2007-02-17 17:57:29'),
(55,21,34,'2007-02-17 17:56:56'),
(56,21,29,'2007-02-17 17:57:13'),
(57,20,34,'2007-02-17 17:59:19'),
(58,22,34,'2007-02-17 22:25:00'),
(59,22,3,'2007-02-17 22:30:26'),
(60,22,36,'2007-02-17 22:43:28'),
(61,22,8,'2007-02-17 22:36:48'),
(62,22,32,'2007-02-17 22:38:56'),
(63,17,16,'2007-02-18 11:29:26'),
(64,16,36,'2007-02-18 00:15:24'),
(65,16,39,'2007-02-18 00:16:59'),
(66,16,16,'2007-02-18 00:17:22'),
(67,16,22,'2007-02-18 00:18:50'),
(68,16,35,'2007-02-18 00:27:02'),
(69,16,37,'2007-02-18 00:29:38'),
(70,16,2,'2007-02-18 00:30:41'),
(71,14,35,'2007-02-18 02:52:33'),
(72,14,29,'2007-02-18 02:55:47'),
(73,14,39,'2007-02-18 02:57:27'),
(74,14,38,'2007-02-18 02:58:06'),
(75,14,37,'2007-02-18 03:00:42'),
(76,14,26,'2007-02-18 03:01:44'),
(77,14,22,'2007-02-18 03:02:09'),
(78,14,19,'2007-02-18 03:03:00'),
(79,14,13,'2007-02-18 03:06:37'),
(80,14,12,'2007-02-18 03:06:56'),
(81,1,39,'2007-02-18 05:16:47'),
(82,1,43,'2007-02-21 08:03:16'),
(83,1,42,'2007-09-01 19:18:12'),
(84,1,41,'2007-05-07 04:59:05'),
(85,1,40,'2007-08-27 18:00:07'),
(86,1,35,'2007-02-18 06:23:48'),
(87,1,44,'2007-02-18 05:22:12'),
(88,22,44,'2007-02-18 06:24:27'),
(89,22,13,'2007-02-18 06:27:14'),
(90,1,45,'2007-09-20 12:31:24'),
(91,22,6,'2007-02-18 06:27:32'),
(92,22,41,'2007-02-18 06:27:58'),
(93,22,42,'2007-02-18 06:28:34'),
(94,22,35,'2007-02-18 06:28:54'),
(95,1,46,'2007-02-21 08:23:18'),
(96,1,47,'2007-02-21 14:50:39'),
(97,1,48,'2007-02-24 11:42:01'),
(98,20,43,'2007-02-18 11:23:46'),
(99,20,40,'2007-03-05 00:43:34'),
(100,17,22,'2007-02-18 11:29:48'),
(101,1,49,'2007-02-21 08:01:34'),
(102,17,49,'2007-02-18 11:36:49'),
(103,17,43,'2007-02-18 11:37:07'),
(104,17,40,'2007-02-18 11:46:13'),
(105,20,48,'2007-02-18 11:49:56'),
(106,1,50,'2007-02-28 08:38:43'),
(107,17,50,'2007-02-18 11:55:36'),
(108,20,50,'2007-02-18 12:02:46'),
(109,20,41,'2007-02-18 12:12:04'),
(110,20,35,'2007-02-18 12:15:16'),
(111,16,47,'2007-02-18 13:56:57'),
(112,17,35,'2007-02-18 13:57:45'),
(113,17,51,'2007-02-18 14:00:23'),
(114,16,6,'2007-02-18 14:00:30'),
(115,16,48,'2007-02-18 22:14:48'),
(116,16,45,'2007-02-18 22:14:53'),
(117,17,48,'2007-02-18 14:02:40'),
(118,1,52,'2007-02-18 15:05:44'),
(119,17,52,'2007-02-18 14:09:39'),
(120,16,50,'2007-02-18 22:12:30'),
(121,16,12,'2007-02-18 23:58:35'),
(122,14,52,'2007-02-19 01:08:24'),
(123,14,49,'2007-02-19 00:00:10'),
(124,14,48,'2007-02-19 03:12:45'),
(125,14,46,'2007-02-19 00:02:36'),
(126,14,47,'2007-02-19 00:22:19'),
(127,14,45,'2007-02-19 00:08:04'),
(128,14,53,'2007-02-19 01:10:28'),
(129,1,53,'2007-02-19 02:20:41'),
(130,13,12,'2007-02-20 00:33:36'),
(131,13,54,'2007-02-28 23:56:25'),
(132,14,54,'2007-05-06 05:47:02'),
(133,1,54,'2007-08-17 15:27:27'),
(134,1,55,'2007-02-24 11:41:32'),
(135,1,56,'2007-09-07 17:53:47'),
(136,32,41,'2007-02-21 11:03:30'),
(137,16,54,'2007-03-08 03:21:21'),
(138,14,55,'2007-02-26 03:21:33'),
(139,14,56,'2007-08-27 06:45:46'),
(140,16,56,'2007-02-23 04:59:41'),
(141,16,53,'2007-02-23 05:03:47'),
(142,21,47,'2007-02-23 13:06:08'),
(143,21,13,'2007-02-23 13:06:23'),
(144,21,53,'2007-02-23 13:07:04'),
(145,1,57,'2007-02-23 17:53:44'),
(146,1,58,'2007-02-23 18:08:47'),
(147,21,59,'2007-02-23 18:14:04'),
(148,1,59,'2007-02-24 07:33:13'),
(149,1,60,'2007-02-28 08:39:13'),
(150,14,60,'2007-02-24 23:16:45'),
(151,14,50,'2007-02-26 03:21:34'),
(152,34,60,'2007-02-26 08:22:48'),
(153,34,54,'2007-02-26 08:19:07'),
(154,34,56,'2007-02-26 08:20:27'),
(155,34,50,'2007-02-26 08:21:05'),
(156,14,43,'2007-09-07 02:08:46'),
(157,35,50,'2007-02-27 01:03:46'),
(158,9,6,'2007-02-27 12:54:36'),
(159,9,13,'2007-02-27 12:54:53'),
(160,9,41,'2007-02-27 12:55:12'),
(161,9,45,'2007-03-05 12:42:40'),
(162,10,48,'2007-02-28 17:08:14'),
(163,10,45,'2007-02-28 17:09:37'),
(164,36,47,'2007-02-28 23:03:29'),
(165,36,61,'2007-02-28 23:08:59'),
(166,36,48,'2007-02-28 23:09:26'),
(167,36,45,'2007-02-28 23:10:33'),
(168,36,56,'2007-02-28 23:12:04'),
(169,36,55,'2007-02-28 23:12:21'),
(170,36,60,'2007-02-28 23:15:17'),
(171,36,49,'2007-02-28 23:16:34'),
(172,36,43,'2007-02-28 23:18:59'),
(173,36,41,'2007-02-28 23:20:08'),
(174,13,62,'2007-02-28 23:56:33'),
(175,13,56,'2007-02-28 23:56:46'),
(176,13,41,'2007-02-28 23:56:57'),
(177,13,40,'2007-02-28 23:57:52'),
(178,1,62,'2007-03-01 03:04:25'),
(179,3,56,'2007-03-01 03:46:59'),
(180,3,62,'2007-03-01 03:06:29'),
(181,1,63,'2007-03-01 11:55:47'),
(182,3,60,'2007-03-01 03:15:12'),
(183,1,64,'2007-05-07 04:58:05'),
(184,3,48,'2007-03-01 03:36:37'),
(185,3,6,'2007-03-01 03:38:06'),
(186,3,55,'2007-03-01 03:41:05'),
(187,1,65,'2007-03-01 03:41:54'),
(188,1,66,'2007-03-01 03:42:30'),
(189,3,63,'2007-03-01 03:43:04'),
(190,3,64,'2007-03-01 03:43:38'),
(191,14,67,'2007-03-01 03:44:33'),
(192,1,67,'2007-05-07 05:03:06'),
(193,3,50,'2007-03-01 03:56:42'),
(194,3,49,'2007-03-01 04:08:14'),
(195,1,68,'2007-05-07 05:00:20'),
(196,14,64,'2007-03-01 17:57:05'),
(197,14,66,'2007-03-01 21:24:37'),
(198,1,69,'2007-03-02 13:45:54'),
(199,1,70,'2007-03-02 12:24:42'),
(200,10,68,'2007-03-19 11:46:47'),
(201,10,67,'2007-03-02 13:33:51'),
(202,10,70,'2007-03-02 13:34:08'),
(203,36,70,'2007-03-02 20:36:03'),
(204,36,69,'2007-03-02 20:38:03'),
(205,1,71,'2007-03-03 03:41:46'),
(206,1,72,'2007-03-03 09:11:25'),
(207,36,72,'2007-03-03 18:10:38'),
(208,36,73,'2007-03-03 18:12:00'),
(209,14,73,'2007-03-03 22:52:09'),
(210,14,74,'2007-03-03 22:54:01'),
(211,14,63,'2007-03-03 22:54:21'),
(212,14,75,'2007-03-03 22:54:45'),
(213,36,75,'2007-03-04 02:03:55'),
(214,1,75,'2007-03-04 04:46:26'),
(215,1,74,'2007-05-07 05:09:18'),
(216,3,74,'2007-03-05 00:20:11'),
(217,20,75,'2007-03-05 00:41:00'),
(218,20,67,'2007-03-05 00:42:52'),
(219,1,76,'2007-03-06 08:22:25'),
(220,14,76,'2007-03-06 02:48:09'),
(221,41,76,'2007-03-06 08:23:20'),
(222,41,69,'2007-03-06 08:34:14'),
(223,41,1,'2007-09-16 19:41:06'),
(224,41,74,'2007-03-06 10:34:26'),
(225,41,48,'2007-03-06 10:43:24'),
(226,41,68,'2007-03-06 10:36:04'),
(227,41,50,'2007-03-06 10:37:45'),
(228,41,77,'2007-03-08 02:07:59'),
(229,1,77,'2007-05-07 05:11:08'),
(230,14,77,'2007-03-06 13:30:48'),
(231,42,68,'2007-03-06 18:39:46'),
(232,41,6,'2007-03-07 02:42:55'),
(233,41,45,'2007-09-16 19:39:03'),
(234,41,16,'2007-03-07 02:43:23'),
(235,41,40,'2007-03-07 02:43:56'),
(236,41,41,'2007-03-07 02:44:03'),
(237,41,42,'2007-03-07 02:44:27'),
(238,41,43,'2007-03-07 02:45:43'),
(239,41,64,'2007-03-07 02:46:03'),
(240,41,67,'2007-03-07 02:46:24'),
(241,41,2,'2007-09-16 19:42:31'),
(242,41,56,'2007-03-07 02:47:24'),
(243,41,13,'2007-03-07 03:04:15'),
(244,41,54,'2007-03-07 03:04:23'),
(245,16,69,'2007-03-08 03:17:26'),
(246,16,76,'2007-03-08 03:19:01'),
(247,16,74,'2007-03-08 03:19:20'),
(248,16,13,'2007-03-08 03:20:16'),
(249,16,1,'2007-03-08 03:21:35'),
(250,16,68,'2007-03-08 03:21:46'),
(251,36,76,'2007-03-08 22:50:10'),
(252,36,78,'2007-03-09 00:23:54'),
(253,1,78,'2007-03-09 03:04:19'),
(254,1,79,'2007-08-27 16:24:28'),
(255,41,79,'2007-03-09 04:01:43'),
(256,36,79,'2007-03-13 16:04:48'),
(257,36,77,'2007-03-12 23:41:32'),
(258,14,79,'2007-03-09 16:27:57'),
(259,43,74,'2007-04-01 15:51:29'),
(260,14,40,'2007-03-10 15:17:20'),
(261,14,41,'2007-03-10 15:17:26'),
(262,14,42,'2007-03-10 15:17:33'),
(263,14,68,'2007-03-10 15:17:49'),
(264,14,69,'2007-03-10 15:18:39'),
(265,10,69,'2007-03-11 14:42:11'),
(266,10,43,'2007-03-11 14:42:45'),
(267,16,67,'2007-03-12 06:06:48'),
(268,16,64,'2007-03-12 06:08:43'),
(269,16,41,'2007-03-12 06:08:52'),
(270,16,40,'2007-03-12 06:14:31'),
(271,16,77,'2007-03-12 06:18:54'),
(272,36,74,'2007-03-13 16:05:04'),
(273,43,79,'2007-04-01 15:49:21'),
(274,43,77,'2007-04-01 15:51:19'),
(275,43,68,'2007-04-01 15:51:37'),
(276,43,69,'2007-03-15 14:18:25'),
(277,21,79,'2007-03-15 23:35:14'),
(278,21,77,'2007-03-15 23:35:24'),
(279,9,64,'2007-03-17 13:50:54'),
(280,10,64,'2007-03-19 11:47:04'),
(281,11,56,'2007-03-21 05:26:59'),
(282,11,40,'2007-03-21 06:02:18'),
(283,11,69,'2007-03-21 06:02:59'),
(284,11,80,'2007-03-27 08:37:41'),
(285,1,80,'2007-03-21 08:47:54'),
(286,20,79,'2007-03-22 11:52:56'),
(287,10,79,'2007-03-23 14:07:53'),
(288,10,74,'2007-03-23 14:08:02'),
(289,10,13,'2007-03-23 14:08:06'),
(290,10,1,'2007-03-23 14:08:11'),
(291,10,54,'2007-03-23 14:08:15'),
(292,11,79,'2007-03-27 08:36:21'),
(293,43,54,'2007-04-01 15:48:31'),
(294,43,80,'2007-04-01 15:51:07'),
(295,9,68,'2007-04-07 15:45:07'),
(296,22,79,'2007-04-20 13:34:23'),
(297,14,80,'2007-08-27 06:45:06'),
(298,41,80,'2007-08-27 03:39:31'),
(299,1,82,'2007-09-20 12:32:01'),
(300,18,81,'2007-08-27 15:44:07'),
(301,1,81,'2007-08-27 15:44:17'),
(302,18,83,'2007-08-27 15:45:52'),
(303,18,84,'2007-08-27 15:46:57'),
(304,1,84,'2007-08-27 15:47:32'),
(305,1,85,'2007-08-27 16:08:03'),
(306,1,87,'2007-08-27 15:54:21'),
(307,1,88,'2007-09-01 19:12:43'),
(308,1,86,'2007-09-02 10:19:48'),
(309,18,88,'2007-08-27 16:02:12'),
(310,1,89,'2007-08-27 16:03:35'),
(311,18,85,'2007-08-27 16:06:59'),
(312,18,80,'2007-08-27 16:08:38'),
(313,18,79,'2007-08-27 17:40:03'),
(314,18,74,'2007-08-27 16:10:45'),
(315,1,90,'2007-08-27 16:11:32'),
(316,18,13,'2007-08-27 16:11:55'),
(317,48,79,'2007-08-27 21:54:36'),
(318,48,54,'2007-08-27 22:27:57'),
(319,48,90,'2007-08-27 22:22:43'),
(320,1,93,'2007-08-27 16:40:01'),
(321,1,95,'2007-08-27 16:44:21'),
(322,1,96,'2007-08-27 16:46:10'),
(323,1,94,'2007-08-27 16:46:13'),
(324,1,97,'2007-08-27 16:47:29'),
(325,1,92,'2007-08-27 16:48:24'),
(326,1,91,'2007-08-27 16:49:21'),
(327,1,99,'2007-08-27 16:52:26'),
(328,18,97,'2007-08-27 16:55:35'),
(329,1,98,'2007-08-27 16:51:10'),
(330,18,96,'2007-08-27 16:51:40'),
(331,18,99,'2007-08-27 16:59:25'),
(332,18,98,'2007-08-27 16:55:53'),
(333,18,56,'2007-08-27 16:58:20'),
(334,18,93,'2007-08-27 16:59:02'),
(335,1,100,'2007-09-02 10:18:23'),
(336,18,101,'2007-08-27 17:08:28'),
(337,48,101,'2007-08-27 17:12:26'),
(338,48,100,'2007-09-16 17:13:30'),
(339,18,102,'2007-08-27 17:13:43'),
(340,1,103,'2007-08-27 17:22:22'),
(341,18,77,'2007-08-27 17:33:53'),
(342,18,104,'2007-08-27 17:36:39'),
(343,18,45,'2007-08-27 17:37:00'),
(344,1,104,'2007-08-28 06:13:29'),
(345,18,86,'2007-08-27 18:20:15'),
(346,48,104,'2007-09-01 16:24:13'),
(347,48,85,'2007-08-27 22:23:39'),
(348,48,98,'2007-08-27 18:35:08'),
(349,48,13,'2007-08-27 18:37:08'),
(350,48,74,'2007-08-27 22:23:09'),
(351,48,97,'2007-08-27 19:35:32'),
(352,48,105,'2007-08-27 22:31:43'),
(353,48,89,'2007-08-27 22:11:46'),
(354,48,106,'2007-09-01 16:24:36'),
(355,48,96,'2007-08-27 22:12:06'),
(356,48,103,'2007-08-27 22:12:51'),
(357,48,93,'2007-08-27 22:12:59'),
(358,48,88,'2007-08-27 22:13:30'),
(359,48,86,'2007-08-27 22:13:36'),
(360,48,68,'2007-08-27 22:13:44'),
(361,48,109,'2007-08-27 22:17:35'),
(362,48,67,'2007-09-17 14:02:35'),
(363,48,64,'2007-08-27 22:39:19'),
(364,48,56,'2007-08-27 22:20:28'),
(365,48,50,'2007-08-27 22:20:52'),
(366,48,43,'2007-08-27 22:21:06'),
(367,48,42,'2007-08-27 22:21:58'),
(368,48,40,'2007-08-27 22:30:38'),
(369,48,112,'2007-08-27 22:25:57'),
(370,48,113,'2007-08-27 22:27:45'),
(371,48,2,'2007-09-02 19:43:35'),
(372,48,108,'2007-08-27 22:28:53'),
(373,48,107,'2007-08-27 22:28:57'),
(374,48,41,'2007-08-27 22:29:01'),
(375,48,114,'2007-09-01 16:26:56'),
(376,1,114,'2007-09-02 10:20:03'),
(377,1,111,'2007-11-10 13:36:28'),
(378,1,105,'2007-11-10 13:36:54'),
(379,1,113,'2007-08-28 02:37:48'),
(380,1,109,'2007-08-28 07:47:19'),
(381,1,108,'2007-08-28 02:38:30'),
(382,1,107,'2007-08-28 02:38:52'),
(383,1,106,'2007-09-01 18:46:22'),
(384,1,110,'2007-09-02 10:27:57'),
(385,1,112,'2007-08-28 06:05:35'),
(386,14,113,'2007-08-28 05:55:11'),
(387,1,115,'2007-08-28 05:56:01'),
(388,14,109,'2007-08-28 05:56:32'),
(389,14,108,'2007-08-28 05:58:06'),
(390,14,116,'2007-08-28 06:00:19'),
(391,1,116,'2007-08-28 06:00:26'),
(392,14,107,'2007-08-28 06:01:31'),
(393,14,106,'2007-08-28 06:02:20'),
(394,1,117,'2007-08-28 06:03:03'),
(395,14,100,'2007-08-28 06:03:09'),
(396,1,118,'2007-08-28 06:06:11'),
(397,14,110,'2007-08-28 06:07:22'),
(398,14,118,'2007-08-28 06:08:45'),
(399,14,117,'2007-08-28 06:09:04'),
(400,14,114,'2007-08-28 06:11:04'),
(401,14,105,'2007-08-28 06:11:33'),
(402,48,118,'2007-08-28 06:19:41'),
(403,48,115,'2007-08-28 06:20:07'),
(404,48,117,'2007-08-28 06:21:07'),
(405,14,120,'2007-08-28 06:21:33'),
(406,1,119,'2007-08-28 06:21:41'),
(407,1,120,'2007-08-28 06:22:07'),
(408,48,120,'2007-08-28 06:23:07'),
(409,1,121,'2007-08-28 06:30:16'),
(410,1,122,'2007-08-28 06:30:24'),
(411,18,114,'2007-08-28 06:34:04'),
(412,18,119,'2007-08-28 06:34:51'),
(413,18,117,'2007-08-28 06:35:30'),
(414,18,118,'2007-08-28 06:36:51'),
(415,18,122,'2007-08-28 06:37:06'),
(416,1,123,'2007-08-28 06:37:28'),
(417,48,119,'2007-08-28 06:51:11'),
(418,48,123,'2007-08-28 07:08:59'),
(419,48,122,'2007-08-28 15:35:09'),
(420,1,124,'2007-09-13 02:08:23'),
(421,1,125,'2007-08-28 07:28:48'),
(422,1,126,'2007-08-28 07:29:23'),
(423,48,126,'2007-08-28 07:36:16'),
(424,48,127,'2007-08-28 07:36:38'),
(425,48,124,'2007-08-28 07:37:49'),
(426,1,127,'2007-09-02 10:19:23'),
(427,49,124,'2007-08-28 17:01:26'),
(428,14,122,'2007-08-29 05:32:25'),
(429,14,124,'2007-08-29 05:33:22'),
(430,14,127,'2007-08-29 05:34:36'),
(431,14,119,'2007-08-29 05:35:18'),
(432,14,86,'2007-08-29 20:27:55'),
(433,14,88,'2007-08-29 20:27:56'),
(434,14,97,'2007-08-29 20:27:57'),
(435,14,103,'2007-08-29 20:27:57'),
(436,10,124,'2007-09-02 10:13:32'),
(437,10,119,'2007-08-30 12:15:37'),
(438,10,109,'2007-08-30 12:15:52'),
(439,10,107,'2007-08-30 12:16:05'),
(440,10,106,'2007-08-30 12:16:38'),
(441,10,103,'2007-08-30 12:16:48'),
(442,10,97,'2007-08-30 12:18:25'),
(443,10,88,'2007-09-02 10:14:13'),
(444,10,56,'2007-08-30 12:19:07'),
(445,10,86,'2007-08-30 12:19:26'),
(446,10,104,'2007-09-02 10:14:36'),
(447,10,105,'2007-09-02 10:16:02'),
(448,14,128,'2007-08-30 23:13:57'),
(449,17,128,'2007-08-30 23:27:10'),
(450,17,122,'2007-08-30 23:18:21'),
(451,1,128,'2007-08-31 01:57:41'),
(452,1,129,'2007-08-31 02:02:38'),
(453,48,129,'2007-08-31 08:51:00'),
(454,14,129,'2007-08-31 08:30:26'),
(455,17,129,'2007-08-31 11:53:50'),
(456,17,130,'2007-08-31 11:56:38'),
(457,48,130,'2007-09-01 04:25:49'),
(458,1,130,'2007-11-10 13:36:04'),
(459,48,131,'2007-09-01 16:23:54'),
(460,48,132,'2007-09-01 16:23:27'),
(461,48,133,'2007-09-01 02:33:40'),
(462,14,134,'2007-09-01 04:19:04'),
(463,14,133,'2007-09-01 04:20:11'),
(464,14,104,'2007-09-01 04:20:47'),
(465,1,134,'2007-09-01 18:39:32'),
(466,1,133,'2007-09-02 10:18:34'),
(467,1,132,'2007-09-01 09:59:21'),
(468,48,134,'2007-09-01 16:24:21'),
(469,1,135,'2007-09-01 18:31:46'),
(470,1,137,'2007-09-01 18:37:31'),
(471,1,136,'2007-09-02 10:17:33'),
(472,1,138,'2007-09-13 01:57:41'),
(473,54,136,'2007-09-01 19:04:47'),
(474,54,138,'2007-09-01 19:06:39'),
(475,54,124,'2007-09-01 19:07:10'),
(476,54,114,'2007-09-01 19:07:40'),
(477,48,136,'2007-09-02 01:18:34'),
(478,48,137,'2007-09-02 01:18:37'),
(479,48,138,'2007-09-02 01:19:15'),
(480,1,139,'2007-09-02 07:35:43'),
(481,3,138,'2007-09-02 09:01:41'),
(482,10,138,'2007-09-02 10:12:56'),
(483,10,133,'2007-09-02 10:13:06'),
(484,10,127,'2007-09-02 10:13:13'),
(485,10,130,'2007-09-10 03:19:22'),
(486,10,111,'2007-09-02 10:15:14'),
(487,10,110,'2007-09-17 12:57:30'),
(488,10,6,'2007-09-02 10:16:49'),
(489,10,139,'2007-09-02 10:17:36'),
(490,10,136,'2007-09-02 10:18:01'),
(491,1,140,'2007-09-02 10:18:09'),
(492,10,122,'2007-09-02 10:18:14'),
(493,10,118,'2007-09-02 10:18:27'),
(494,10,100,'2007-09-02 10:18:35'),
(495,10,98,'2007-09-02 10:18:53'),
(496,10,85,'2007-09-02 10:29:18'),
(497,1,141,'2007-09-02 10:19:14'),
(498,1,142,'2007-09-02 10:31:24'),
(499,10,142,'2007-09-02 10:31:30'),
(500,1,143,'2007-09-02 10:32:35'),
(501,10,143,'2007-09-02 10:39:09'),
(502,10,140,'2007-09-02 10:38:27'),
(503,18,140,'2007-09-02 13:12:15'),
(504,48,139,'2007-09-02 13:42:16'),
(505,48,143,'2007-09-02 13:42:26'),
(506,1,144,'2007-09-02 13:44:16'),
(507,1,145,'2007-09-02 16:01:02'),
(508,48,144,'2007-09-02 13:44:47'),
(509,48,141,'2007-09-02 13:45:06'),
(510,1,146,'2007-09-02 15:36:36'),
(511,48,146,'2007-09-02 15:41:21'),
(512,1,147,'2007-09-19 16:55:59'),
(513,18,147,'2007-09-02 16:28:36'),
(514,14,147,'2007-09-03 01:38:41'),
(515,10,145,'2007-09-06 09:55:39'),
(516,48,148,'2007-09-04 11:10:36'),
(517,1,148,'2007-09-04 12:04:49'),
(518,1,149,'2007-09-16 18:50:07'),
(519,10,149,'2007-09-04 14:59:52'),
(520,10,144,'2007-09-04 15:00:03'),
(521,10,141,'2007-09-06 09:55:17'),
(522,1,150,'2007-09-06 15:43:53'),
(523,54,150,'2007-09-06 15:48:25'),
(524,54,151,'2007-09-06 15:49:10'),
(525,1,151,'2007-09-06 15:50:18'),
(526,1,152,'2007-09-21 09:07:15'),
(527,54,141,'2007-09-06 18:23:20'),
(528,54,149,'2007-09-06 18:22:17'),
(529,54,152,'2007-09-06 18:22:32'),
(530,54,147,'2007-09-10 12:09:29'),
(531,54,130,'2007-09-06 18:27:12'),
(532,54,110,'2007-09-06 18:27:33'),
(533,1,153,'2007-09-06 18:28:49'),
(534,14,153,'2007-09-07 02:07:31'),
(535,14,130,'2007-09-07 02:08:08'),
(536,1,154,'2007-09-08 18:14:23'),
(537,14,111,'2007-09-07 20:22:19'),
(538,54,154,'2007-09-08 18:08:17'),
(539,54,155,'2007-09-08 18:20:54'),
(540,1,155,'2007-09-10 03:48:40'),
(541,54,153,'2007-09-08 22:57:20'),
(542,54,156,'2007-09-08 22:57:52'),
(543,1,156,'2007-09-09 05:12:37'),
(544,10,156,'2007-09-10 03:17:24'),
(545,10,42,'2007-09-10 03:17:49'),
(546,10,147,'2007-09-10 03:19:32'),
(547,1,157,'2007-09-10 12:45:04'),
(548,54,157,'2007-09-10 12:47:04'),
(549,54,158,'2007-09-10 13:14:36'),
(550,1,158,'2007-09-10 12:52:14'),
(551,41,158,'2007-09-11 04:05:20'),
(552,41,152,'2007-09-11 02:43:19'),
(553,41,159,'2007-09-12 01:02:07'),
(554,1,159,'2007-09-26 13:03:33'),
(555,54,159,'2007-09-11 08:36:12'),
(556,1,160,'2007-09-11 18:22:01'),
(557,41,160,'2007-09-11 21:41:27'),
(558,41,161,'2007-09-12 01:02:03'),
(559,41,162,'2007-09-12 00:52:20'),
(560,1,161,'2007-09-12 00:58:18'),
(561,1,162,'2007-09-12 00:58:58'),
(562,1,163,'2007-09-12 12:04:28'),
(563,41,163,'2007-09-12 01:19:53'),
(564,41,110,'2007-09-12 01:40:12'),
(565,54,163,'2007-09-12 15:27:30'),
(566,54,164,'2007-09-12 15:30:24'),
(567,1,164,'2007-09-26 16:21:57'),
(568,54,161,'2007-09-12 15:30:44'),
(569,1,165,'2007-09-12 15:32:05'),
(570,1,166,'2007-09-12 15:40:41'),
(571,41,164,'2007-09-16 22:58:16'),
(572,41,165,'2007-09-12 21:21:24'),
(573,41,166,'2007-09-12 21:24:56'),
(574,41,167,'2007-09-12 21:36:05'),
(575,1,167,'2007-09-13 01:41:11'),
(576,1,168,'2007-09-13 01:46:34'),
(577,14,156,'2007-09-13 02:00:14'),
(578,41,168,'2007-09-16 22:58:16'),
(579,1,169,'2007-09-14 04:08:40'),
(580,41,169,'2007-09-14 04:25:00'),
(581,41,170,'2007-09-16 12:11:32'),
(582,41,156,'2007-09-14 05:19:56'),
(583,41,138,'2007-09-14 05:20:51'),
(584,41,127,'2007-09-14 05:20:53'),
(585,41,124,'2007-09-14 05:20:55'),
(586,41,97,'2007-09-14 05:20:57'),
(587,41,88,'2007-09-14 05:20:59'),
(588,48,156,'2007-09-15 04:03:21'),
(589,48,149,'2007-09-16 17:23:19'),
(590,52,171,'2007-09-15 18:03:12'),
(591,52,156,'2007-09-15 18:03:18'),
(592,54,170,'2007-09-15 18:59:59'),
(593,54,168,'2007-09-15 19:00:30'),
(594,14,171,'2007-09-15 19:41:38'),
(595,41,171,'2007-09-15 22:43:55'),
(596,41,172,'2007-09-16 01:27:14'),
(597,48,172,'2007-09-16 03:56:50'),
(598,48,173,'2007-09-16 03:57:37'),
(599,41,173,'2007-09-16 12:11:32'),
(600,18,130,'2007-09-16 14:11:04'),
(601,18,111,'2007-09-16 14:11:45'),
(602,1,173,'2007-09-16 16:24:11'),
(603,1,170,'2007-09-16 15:19:08'),
(604,1,174,'2007-09-16 15:20:38'),
(605,1,175,'2007-09-16 18:50:18'),
(606,48,175,'2007-09-16 17:07:25'),
(607,52,149,'2007-09-16 17:09:08'),
(608,48,176,'2007-09-16 17:11:33'),
(609,48,145,'2007-09-16 17:11:39'),
(610,48,180,'2007-09-16 17:18:32'),
(611,1,181,'2007-09-16 17:24:58'),
(612,1,182,'2007-09-16 17:25:11'),
(613,1,180,'2007-09-16 17:25:19'),
(614,1,179,'2007-09-16 17:25:39'),
(615,1,178,'2007-09-16 17:25:53'),
(616,1,177,'2007-09-16 17:26:13'),
(617,1,176,'2007-09-16 18:49:44'),
(618,1,183,'2007-09-16 17:30:57'),
(619,48,183,'2007-09-16 17:32:33'),
(620,1,184,'2007-09-16 17:33:16'),
(621,1,185,'2007-09-16 18:49:57'),
(622,1,186,'2007-09-16 18:51:07'),
(623,41,185,'2007-09-16 19:23:49'),
(624,41,184,'2007-09-16 21:44:11'),
(625,41,182,'2007-09-16 19:23:34'),
(626,41,186,'2007-09-16 22:07:09'),
(627,41,181,'2007-09-16 22:07:07'),
(628,41,180,'2007-09-16 21:44:13'),
(629,41,179,'2007-09-16 19:23:10'),
(630,41,178,'2007-09-16 21:44:14'),
(631,41,177,'2007-09-16 21:44:14'),
(632,41,174,'2007-09-16 19:23:33'),
(633,41,183,'2007-09-16 19:23:49'),
(634,41,187,'2007-09-16 21:37:53'),
(635,41,188,'2007-09-16 21:43:35'),
(636,41,82,'2007-09-21 23:05:30'),
(637,41,145,'2007-09-16 19:38:13'),
(638,41,139,'2007-09-16 19:38:15'),
(639,41,144,'2007-09-16 19:38:17'),
(640,41,149,'2007-09-16 19:38:20'),
(641,41,122,'2007-09-16 19:38:23'),
(642,41,118,'2007-09-16 19:38:28'),
(643,41,100,'2007-09-16 19:38:44'),
(644,41,98,'2007-09-16 19:38:59'),
(645,41,104,'2007-09-16 19:39:31'),
(646,41,119,'2007-09-16 19:39:46'),
(647,41,114,'2007-09-16 19:40:56'),
(648,41,147,'2007-09-16 19:41:15'),
(649,41,130,'2007-09-16 19:41:22'),
(650,41,111,'2007-09-16 19:41:37'),
(651,41,105,'2007-09-16 19:41:52'),
(652,41,109,'2007-09-16 19:42:35'),
(653,41,107,'2007-09-16 19:42:59'),
(654,41,106,'2007-09-16 19:42:59'),
(655,41,103,'2007-09-16 19:43:13'),
(656,41,86,'2007-09-16 19:43:13'),
(657,41,189,'2007-09-16 21:58:52'),
(658,41,190,'2007-09-17 12:57:39'),
(659,41,191,'2007-09-17 12:57:01'),
(660,1,191,'2007-09-26 16:21:51'),
(661,1,190,'2007-09-17 03:05:58'),
(662,1,189,'2007-09-17 03:06:12'),
(663,1,188,'2007-09-17 03:06:18'),
(664,1,187,'2007-09-17 03:06:33'),
(665,10,186,'2007-09-17 12:56:07'),
(666,10,190,'2007-09-24 12:13:16'),
(667,10,189,'2007-09-17 12:57:58'),
(668,10,188,'2007-09-17 12:58:07'),
(669,10,187,'2007-09-17 12:58:35'),
(670,10,185,'2007-09-17 12:58:50'),
(671,10,180,'2007-09-17 12:58:59'),
(672,10,178,'2007-09-17 12:59:10'),
(673,10,177,'2007-09-17 12:59:38'),
(674,48,185,'2007-09-17 13:13:13'),
(675,48,187,'2007-09-17 13:13:18'),
(676,48,188,'2007-09-17 13:13:29'),
(677,48,189,'2007-09-17 13:13:40'),
(678,48,186,'2007-09-17 13:19:51'),
(679,48,190,'2007-09-17 13:14:25'),
(680,48,193,'2007-09-17 13:19:35'),
(681,1,194,'2007-11-10 13:52:05'),
(682,1,193,'2007-09-17 14:04:54'),
(683,1,195,'2007-09-17 14:05:27'),
(684,52,195,'2007-09-17 14:33:17'),
(685,52,104,'2007-09-17 14:35:43'),
(686,48,195,'2007-09-17 15:56:24'),
(687,48,110,'2007-09-17 15:56:47'),
(688,1,196,'2007-09-17 16:07:40'),
(689,1,192,'2007-09-18 03:45:47'),
(690,14,139,'2007-09-17 21:13:01'),
(691,14,144,'2007-09-17 21:13:21'),
(692,14,177,'2007-09-17 21:13:49'),
(693,14,180,'2007-09-17 21:14:01'),
(694,14,178,'2007-09-17 21:14:34'),
(695,14,192,'2007-09-17 21:14:53'),
(696,41,192,'2007-09-17 22:56:07'),
(697,41,196,'2007-09-20 21:23:57'),
(698,41,194,'2007-10-04 04:26:24'),
(699,1,197,'2007-09-18 03:46:29'),
(700,18,105,'2007-09-18 07:08:40'),
(701,18,194,'2007-09-18 07:11:33'),
(702,18,190,'2007-09-18 07:13:13'),
(703,18,183,'2007-09-18 07:14:05'),
(704,18,156,'2007-09-18 07:16:36'),
(705,48,196,'2007-09-18 11:26:31'),
(706,48,197,'2007-09-18 14:01:29'),
(707,1,198,'2007-09-18 15:25:42'),
(708,41,198,'2007-09-18 15:49:37'),
(709,1,199,'2007-09-19 09:39:13'),
(710,1,200,'2007-09-19 10:09:56'),
(711,48,200,'2007-09-20 04:11:08'),
(712,1,201,'2007-09-20 06:01:33'),
(713,14,201,'2007-09-20 06:18:43'),
(714,1,202,'2007-09-20 11:27:30'),
(715,52,202,'2007-09-20 16:17:19'),
(716,48,203,'2007-09-20 16:18:15'),
(717,1,203,'2007-09-20 16:19:43'),
(718,1,204,'2007-09-20 18:16:48'),
(719,41,201,'2007-09-20 21:23:51'),
(720,41,204,'2007-09-20 21:23:51'),
(721,52,204,'2007-09-21 03:20:04'),
(722,1,205,'2007-09-21 08:52:51'),
(723,41,205,'2007-09-21 23:05:16'),
(724,41,206,'2007-09-21 23:05:22'),
(725,48,206,'2007-09-22 11:54:05'),
(726,1,206,'2007-09-22 15:26:48'),
(727,1,207,'2007-10-01 10:44:00'),
(728,48,207,'2007-09-26 08:46:26'),
(729,41,207,'2007-09-23 01:30:15'),
(730,14,207,'2007-09-23 21:38:18'),
(731,10,194,'2007-09-24 12:12:50'),
(732,10,207,'2007-10-02 04:20:42'),
(733,10,205,'2007-09-24 12:14:03'),
(734,1,208,'2007-09-26 16:34:34'),
(735,54,191,'2007-09-26 16:21:30'),
(736,54,208,'2007-09-26 16:24:50'),
(737,54,174,'2007-09-26 16:24:57'),
(738,14,188,'2007-09-27 05:15:10'),
(739,1,209,'2007-10-03 15:07:04'),
(740,10,209,'2007-09-27 14:15:56'),
(741,41,209,'2007-09-28 02:27:46'),
(742,41,208,'2007-09-27 14:55:37'),
(743,41,210,'2007-09-27 15:06:28'),
(744,1,210,'2007-09-27 16:00:01'),
(745,1,211,'2007-10-01 10:43:53'),
(746,41,211,'2007-11-28 17:15:48'),
(747,14,209,'2007-09-29 00:43:21');

-- Table structure for table `forums_ranks`
DROP TABLE IF EXISTS `forums_ranks`;

CREATE TABLE `forums_ranks` (
  `num_posts` int(11) NOT NULL default '0',
  `rank` varchar(255) collate latin1_general_ci NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `forums_ranks`
insert into `forums_ranks` values
(0,'Forum Rookie'),
(50,'Regular Joe'),
(100,'Daily Appearance'),
(200,'Forum Veteran'),
(350,'Professional Spammer'),
(500,'Forum Addict');

-- Table structure for table `forums_replies`
DROP TABLE IF EXISTS `forums_replies`;

CREATE TABLE `forums_replies` (
  `id` bigint(20) NOT NULL auto_increment,
  `f_id` bigint(20) NOT NULL default '0',
  `t_id` bigint(20) NOT NULL default '0',
  `account_id` bigint(20) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `subject` varchar(75) collate latin1_general_ci NOT NULL default '',
  `message` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `forums_replies`
insert into `forums_replies` values
(1,1,1,1,'2007-02-11 17:06:17','','Dear reader,<br />\n<br />\nWelcome to the Development Announcement forum of Red Republic! As this game will always be updated with new content, there will always be new announcements about the state of the game. You can keep track of these here. There are certain rules for this forum, which I shall list below:<br />\n<br />\n- First of all, read the forum rules! For those who don\\\'t/didn\\\'t, a summary can be found next.<br />\n- Please do not double post, rather edit your own post, in order to minimize database-load.<br />\n- Refrain from posting the wrong topics in the wrong forum. This goes for replies as well, please stick to the topic.<br />\n- Furthermore, when creating a topic or reply, please bear in mind that we will not allow content that expresses racism or discrimination. Such post will be deleted without furter doubt!<br />\n- Last, but not least, our admin team has the right to modify or delete any post on this forum, and by posting you agree to this.<br />\n<br />\nWith dear regards,<br />\nRed Republic Admin Team							'),
(2,2,2,1,'2007-02-11 17:09:46','','Dear reader,<br />\n<br />\nWelcome to the Game Features forum of Red Republic! Because we want to thoroughly document Red Republic\\\'s current features and create a list of features that should be added, this forum is very important. Please take your time to think of suggestions that will improve this game for version 2.0 or else help us documenting the old game. There are certain rules for this forum, which I shall list below:<br />\n<br />\n- First of all, read the forum rules! For those who don\\\'t/didn\\\'t, a summary can be found next.<br />\n- Please do not double post, rather edit your own post, in order to minimize database-load.<br />\n- Refrain from posting the wrong topics in the wrong forum. This goes for replies as well, please stick to the topic.<br />\n- Furthermore, when creating a topic or reply, please bear in mind that we will not allow content that expresses racism or discrimination. Such post will be deleted without furter doubt!<br />\n- Last, but not least, our admin team has the right to modify or delete any post on this forum, and by posting you agree to this.<br />\n<br />\nWith dear regards,<br />\nRed Republic Admin Team														'),
(3,3,3,1,'2007-02-11 17:13:01','','Dear reader,<br />\n<br />\nWelcome to the Game Storylines forum of Red Republic! As a new feature of Red Republic we thought it a good idea to offer a bit more immersion for the player\\\'s character. As a result of this we wish to add a \\\'user configurable\\\' storyline or something of that sort. Details about this storyline mechanism haven\\\'t been worked out yet - that\\\'s what this forum is for. If you have an interesting story line to share, or opinions on how to implement such a system, don\\\'t hesitate to post! There are certain rules for this forum, which I shall list below:<br />\n<br />\n- First of all, read the forum rules! For those who don\\\'t/didn\\\'t, a summary can be found next.<br />\n- Please do not double post, rather edit your own post, in order to minimize database-load.<br />\n- Refrain from posting the wrong topics in the wrong forum. This goes for replies as well, please stick to the topic.<br />\n- Furthermore, when creating a topic or reply, please bear in mind that we will not allow content that expresses racism or discrimination. Such post will be deleted without furter doubt!<br />\n- Last, but not least, our admin team has the right to modify or delete any post on this forum, and by posting you agree to this.<br />\n<br />\nWith dear regards,<br />\nRed Republic Admin Team														'),
(4,1,4,1,'2007-02-13 05:07:09','','Welcome to the development forums for Crime Syndicate. Actually, I should say Crime Syndicate 2.0 since the purpose of these forums is to document the old game and propose features that will lead to the creation of a \\\'revamped\\\' game. That is also the reason why I e-mailed everyone who is still interested to come here and talk about Crime Syndicate\\\'s future. Before suggesting new features though, please read about the structure of this development forum that I had in mind.<br />\n<br />\nBasically I want to use these forums to both document the old game and propose features for the new game. Since most of us have played the old game we\\\'ll know what made it successful and what could be improved. Therefore I\\\'m also urging you to think a little before suggesting things. While lazer riflez may undoubtedly sound like a cool new feature (sarcasm) I would rather have people suggest things that are closer to what the game is currently about. These suggestions can be anything from minor balance tweaks to complete reworks of a certain career. As long as it is believable and within my ability to code!<br />\n<br />\nHaving said all this, I realise I can\\\'t \\\'shape\\\' a discussion about Crime Syndicate\\\'s future the way I want it. I think we\\\'ll just have to find out during the development phase what\\\'s possible and what not.<br />\n<br />\nUntil then, I await your suggestions eagerly!							'),
(6,5,5,1,'2007-02-13 06:04:19','','Dear reader,<br />\n<br />\nWelcome to the General Chat forum of Red Republic! This is a somewhat \\\'off-topic\\\' forum, seeing as non-Red Republic related topics may be discussed here as well. Being \\\'off-topic\\\' does not mean you\\\'re free to write down anything though. There are certain rules for this forum, which I shall list below:<br />\n<br />\n- First of all, read the forum rules! For those who don\\\'t/didn\\\'t, a summary can be found next.<br />\n- Please do not double post, rather edit your own post, in order to minimize database-load.<br />\n- Refrain from posting the wrong topics in the wrong forum. This goes for replies as well, please stick to the topic.<br />\n- Furthermore, when creating a topic or reply, please bear in mind that we will not allow content that expresses racism or discrimination. Such post will be deleted without furter doubt!<br />\n- Last, but not least, our admin team has the right to modify or delete any post on this forum, and by posting you agree to this.<br />\n<br />\nWith dear regards,<br />\nRed Republic Admin Team														'),
(7,1,4,2,'2007-02-13 06:17:04','','Like it :D very swish!'),
(8,2,6,1,'2007-02-13 07:07:49','','One of Crime Syndicate\\\'s current strengths is it\\\'s item system. I think the idea of having intentories and many items which you can find/steal/earn will appeal to a large number of players. Items give players another purpose to play this game, namely to gather certain items of value. This, as opposed to just gathering money or aiming for a high rank (both of which are also possible of course).<br />\n<br />\nI\\\'ll make up a short list of current features that are bound to the item system and of which I think they\\\'ll need to return in Crime Syndicate 2.0<br />\n<br />\n[b]Personal purposes for players:[/b][list][*]An inventory. Players need to have an inventory (backpack) in which they can put their items<br />\n[*]Bags; players need to be able to increase the amount of items they can put in their inventories<br />\n[*]Item stashing in houses; once a player owns a house he should be able to put items in it.<br />\n[*]Mailboxes; players need to be able to send and receive items via their mailboxes.<br />\n[*]Players should be able to equip items, making their character stronger.[/list][b]Economic purposes:[/b][list][*]Players need to be able to trade (buy and sell) items in auction houses. This introduces a more player-based economy.<br />\n[*]The dumpstore; the dumpstore is a place where crap items can be bought, but a few good ones as well. Along with getting new players some (bad) gear, this dumpstore also effectively adds one extra business that can be owned.[/list][b]Earns \\\'n aggs:[/b][list][*]Players need to be able to perform aggs, based on item stealing. Think of pickpocketing or breaking into houses and finding items.<br />\n[*]Players need earns that give them items (for example, the nick from store earn)<br />\n[*]Players should have a chance of finding an item after performing an earn.[/list]<br />\nI think this basically summarizes what CS currently does with its items, but if it\\\'s not complete, or if you  have suggestions on how to improve things or even add to them, don\\\'t hesitate to reply - or even make your own thread!<br />\n<br />\n																												'),
(9,1,4,3,'2007-02-13 07:31:36','','woot roger <br />\nbwahaha cs is back woot when i get some decent time ill start me postings :D'),
(10,1,4,11,'2007-02-13 16:22:06','','OMFG.. WHIPE... </3 Roger Hoe kon je dat nou doen! T_T<br />\n<br />\nI lost mah tank :/ BOOO :P<br />\n<br />\nGreat to see you\\\'re going to develop this game further again ;)<br />\n<br />\nGood Luck, i might test this game soon again<br />\n<br />\n~Kuro'),
(11,1,4,1,'2007-02-13 16:39:11','','Wel I\\\'m going to need some input from all of you (including you, Frankieangel! :P) to get the new version the way you like :P'),
(12,1,4,14,'2007-02-14 00:54:37','','Kuro touches little boys....<br />\n<br />\nBut like the rest said, Good work Roger better make it good, I thought you had died or something...'),
(13,1,7,1,'2007-02-15 09:57:28','','I\\\'ll just be short about this, I have updated some of the forum code and added an account page. On logging in via the normal login rather than the quick login you will be redirected to that page. Though there\\\'s not much to see yet you\\\'ll be able to change your forum avatar there. It will also be the place from where you\\\'ll \\\'manage\\\' your character in the future.<br />\n<br />\nPlease note that the quick login has not changed, it is meant to serve as a helper for when you quickly want to login and don\\\'t need to see your account page.<br />\n<br />\nRegards, <br />\n<br />\nRoger'),
(14,2,8,17,'2007-02-15 21:08:23','','ok...<br />\r\nWhat I want in CS is...<br />\r\n1. I want there to be a pimp carrer basically men will be able to rank up in the pimp carrer and the females of CS will be able to be whores and so basically tehy would work for the pimps and we would take a cut or however that will work...<br />\r\n2. Homeless Carrer Roger I already told you about that and if you need me to explan again then ask<br />\r\n3. Suicide Bomber crime- Basically you srap bombs to yourself and run into a CF and blow it up and you basically kill offline crew members of the family in that crew destroy teh CF and kill yourself but make it in such a way where if your weak and didnt do shit you wont kill anybody<br />\r\n4. Businesses that actually mean something ok now what do I mean by this? well for example you know how when your HD I think you should pay people on salery or something like that they work for you they make you money and in return you pay them for their job i dont really know how to explain it'),
(16,2,8,1,'2007-02-16 03:55:14','','Thanks for the input, I moved the topic to Game Features since it fits better there... (it\\\'s hardly an announcement).<br />\r\nAs for your points, I\\\'ll answer them point by point.<br />\r\n<br />\r\n1. A pimp career is a  [u]possibility[/u] but I don\\\'t want Crime Syndicate to be associated with the dozens of pimp games that are out there already. Perhaps it could be a little sidestep from the crime career, but no more than that.<br />\r\n<br />\r\n2. I might well have forgotten, and in any case, it\\\'d be nice if you wrote it out here anyway so other people can also comment on it ;)<br />\r\n<br />\r\n3. This is what I had in mind for the terrorist activities to which there\\\'s already a (not working) link in the acts of war menu in the old version. Your version of it might be a tad imbalanced, but some sort of suicide bombing would indeed be cool.<br />\r\n<br />\r\n4. In a way, this was already in the old CS in the sense that you\\\'d get salary if you worked in a legit career. Making the careers top-manager control salaries adds a new dimension to being a manager (\\\'human resources\\\') and sounds like a good idea to me.'),
(18,2,11,1,'2007-02-16 17:14:53','','As you might have noticed (or not, the activity on this forum is terribly low :(), the account page is taking shape. While it is not actually possible to create a new character yet, the character creation form is already there, and from this you might have deduced some information on how I intend to create characters in the game.<br />\n<br />\n[b]Character stats[/b]<br />\nNow that we\\\'re building the new game I can safely say that character stats didn\\\'t encompass much in v1.0. It basically came down to \\\'good exp\\\' and \\\'bad exp\\\'. I am developing something more sophisticated in version 2.0, but I\\\'ll leave the exact stats up for speculation :P If anything, the choices you have to make in the character creation process may give something away. Of course, next to special stats there will simply be experience, and I think I\\\'ll add a \\\'tier bar\\\' again that serves somewhat as an indication of your experience.<br />\n<br />\n[b]Roleplaying Aspects[/b]<br />\nAs you  might have noticed in the character creation screen I require a lot more background information about your character. While you can of course disregard this aspect and enter some nonsense, I think many real role-players will find the extra attention to the role playing aspect very nice. It is also a way to more clearly make your character stand out from other characters.<br />\n<br />\nAny comments on these \\\'new\\\' aspects of your \\\'soon to be\\\' characters are very much appreciated.'),
(19,2,11,20,'2007-02-17 16:28:33','','cool :D'),
(20,2,12,1,'2007-02-17 16:33:00','','I\\\'ll be short about this. One of the first things I\\\'ll do when character creation is working and when you can enter the world is to create some earns etc. (Simply because they\\\'re easy to make :P). Is the deployment agency still a good idea to group the earns that are not related to a specific career?<br />\n<br />\nAnd if this is a good idea, would it then also be a good idea to \\\'keep\\\' the petty crimes as gangster earns?'),
(21,2,13,20,'2007-02-17 16:33:25','','I would like to see some improvements in the next game such as the Whacking and GBH options and how they would work.<br />\n<br />\nWhacking should be a lot easier than it was, even if it could ruin some peoples in-game experiences.<br />\nI mean, you have to get it better than IJ\\\'s system, but easier than the actual one.<br />\nRight now it takes too long to get a whack done, which could bring chaos in the game if you\\\'re trying to enforce some rules as well as wars, which would take longer or probably forever to come to an end.<br />\n<br />\nGBH: well, it should harm and it doesn\\\'t do much. Decreasing the exp of the harmed one and increasing the exp of the attacker would be good. Another thing: GBH\\\'s should let you screwed up for sometime, slowing you down or something, but I don\\\'t think you need to GBH first to Whack someone.<br />\n<br />\nWell its about it :D'),
(22,2,13,1,'2007-02-17 16:42:04','','[quote]I would like to see some improvements in the next game such as the Whacking and GBH options and how they would work.[/quote]<br />\nI agree, even if it was just about fixing the bugs with it :P<br />\n<br />\n[quote]Whacking should be a lot easier than it was, even if it could ruin some peoples in-game experiences.<br />\nI mean, you have to get it better than IJ\\\'s system, but easier than the actual one.<br />\nRight now it takes too long to get a whack done...[/quote]<br />\nThat is true, but what you have to keep in mind is that in the old version of Crime Syndicate the best weapon was an M-16. This was a tier 2 item of not so good quality if I remember correctly and it would already damage someone who was way below the attacker for around 80 or more percent. Equivalents to \\\'snipers\\\' and katana\\\'s in injustice would be the real killer weapons in Crime Syndicate. Of course a godfather should be able to one-shot nearly every one, and  with the most powerful weapons, he\\\'ll be able to. However, what you see often in Injustice is that a war is over as soon as one godfather gets the first hit. When people of equal level (two godfathers i.e.) fight each other, this \\\'damage\\\' system causes the fight to last longer though, which makes it more strategic in my opinion. Chaos can still be fought when there are a few powerful people in the game, it\\\'s just that in the old version a lot of people were about the same strength, and thus it was chaos, because no-one could triumph over the other. <br />\n<br />\n[quote]GBH: well, it should harm and it doesn\\\'t do much. Decreasing the exp of the harmed one and increasing the exp of the attacker would be good. Another thing: GBH\\\'s should let you screwed up for sometime, slowing you down or something, but I don\\\'t think you need to GBH first to Whack someone.[/quote]<br />\nI agree here, aggravated assaults didn\\\'t cause enough harm. I\\\'m not sure about what effect to give it, but that is a nice topic for conversation of course...<br />\n<br />\n<br />\n'),
(23,2,14,20,'2007-02-17 16:42:46','','I think they are fine for now, but I\\\'d like to see more interaction between them.<br />\n<br />\nThe law system is perfect, I mean, can\\\'t be done better than it already is<br />\n<br />\nThe police system is cool too, I don\\\'t really think you have to change it at all<br />\n<br />\nThe Army career should get improved, with more realistic features such as joining the Marines, Navy and etc.<br />\n<br />\nThe Gangster career has to be re-done I think...and thats where we can help you the most<br />\n<br />\nGangster career could have bigger improvements such as a round-table reunion between the families around the CS Globe. It would help out to discuss how the mafia is doing and define when a war should be taken part. It would be fun to see those reunions like we see in the movies :D<br />\n<br />\nI thought about the Gangster Ranks and I have this idea:<br />\n<br />\n- Pickpockets : just punks ranking their way in the dark side of life<br />\n- Dealers : dealing drugs, robbing stores, doing bad earns<br />\n- Pics : same as dealers + getting to protect city corners for the dealers(something like it)<br />\n- Sgars : running his \\\'crew\\\' , dealing drugs and getting his cut out of the drugs his dealers and pics make.<br />\n- Earners : controlling Sgars, getting his cut from them and providing them with drugs<br />\n- Capodecine : running his crew, planning bank robberies, major store robberies and helping out the Caporegime<br />\n- Caporegime : Crew Leader.<br />\n- Godfather: Running the whole Mafia(I mean, all the mafias are under him)<br />\n'),
(24,2,12,20,'2007-02-17 16:48:52','','Keep them all...<br />\nbut I thought a different thing about earning...<br />\nIn the employement agency you should keep the earns and random questions whenever you can so you can define the char. I mean, bad options and good options.<br />\nIf you get enough bad options, you will eventually earn the Petty Crimes menu.<br />\n<br />\nPetty Crimes right on the start just don\\\'t seem good...'),
(25,2,12,1,'2007-02-17 16:51:38','','True, choices could be added, although I don\\\'t want to clone IJ too much of course. And petty crimes isn\\\'t available from the start in the old version either, you have to get a little bad exp first... (\\\"Petty crimes, you? Ha! You wouldn\\\'t hurt a fly!\\\" ;))'),
(26,2,15,20,'2007-02-17 16:53:40','','Like I said it works perfectly...<br />\nbut, I thought about some stuff that could be included into the police training:<br />\n<br />\nIn real life, you have a special squad, such as SWAT and etc. And you have Intelligence Agencies that work among the government, which could help fighting Gangsters and crimes around the citites<br />\n<br />\nInteligence Agencies would help out to take out the local families and fight equalliy for the control of the cities, instead of getting gangsters fighting themselves to get room in the cities.<br />\n<br />\nI\\\'m thinking that wars could be fought by legits(PD + Squads + Intelligence Agencies) against Criminals and make it a little more realistic<br />\n<br />\nYet, I\\\'m not saying that wars agains\\\'t families should end, cause I still believe that mafias will always fight for territory, but I\\\'m saying that Legits should have their part in the wars too to make their streets safer'),
(27,2,14,1,'2007-02-17 17:07:08','','I agree with most of your points. The only thing I must add to is that I think that you should replace \\\"Godfather\\\" with Don. Don is another name for \\\'capo di tutti capi\\\', which is basically what you meant with \\\'boss over the mafia\\\' I think. I think Godfather should still be in the game, but it shouldn\\\'t be reached by ordinarily ranking. To be a godfather is something special and not anyone should be able to get it if they just have enough time.<br />\n<br />\nPerhaps being a godfather is only possible if you meet requirements such as owning more than a big number of businesses and having more than a fixed number of caporegimes under himself. In other words, rather than simply \\\'ranking\\\', he will have to actually be very successful in the world of crime. So, basically, a godfather would then be like a \\\'superdon\\\', not the \\\'next rank\\\'.'),
(28,2,16,20,'2007-02-17 17:09:39','','Well thats something we didn\\\'t see in the Old version of Crime Syndicate<br />\n<br />\nBusiness career could be very cool in the game, since I\\\'m getting my  Degree in Business Administration...<br />\n<br />\nSome ideas:<br />\n<br />\n- the same old banking option: being a banker, ranking up through the bank, managing accounts, helping clients and making sure the bank is making money.<br />\n<br />\nThe ranks as I think:<br />\n<br />\n- Trainee: you can learn how the bank works, do some earns and help out with paper work that your boss doesn\\\'t want to do. Eg: Filling a bank form for an insurance apply(a client wants to get insurance, so when he dies his family should get some money, paying a daily fee to the bank).<br />\n- Senior Trainee: helping trainees, doing less paperwork(more important stuff such as making loan-apllies forms) and getting to promote, demote and fire Trainees.<br />\n- Senior Banker : promoting, demoting, firing Senior Trainees that don\\\'t get their Trainees to work properly and managing bank services(Insurance, Loans and etc).<br />\n- Bank Manager : managing accounts, making sure the bank is making money, setting up sallaries and firing, demoting, promoting options.<br />\n- Bank President : he owns the bank after a long experience in the field. He gets to get money out of the bank whenever he wants, even if it will make a huge bankrupt(he\\\'s responsible and if he pisses people off, he\\\'ll get shot before using the money).<br />\n<br />\nI think it will make it more interesting and should give a good exp in the future about dealing with money and with a big crew :)'),
(29,2,16,1,'2007-02-17 17:12:57','','Sounds delightful :D It\\\'ll still have to be worked out in details of course, but I\\\'m sure many people will agree these are some very nice foundations to build on :D'),
(30,2,14,20,'2007-02-17 17:16:13','','Agreed...<br />\n<br />\nPlus, the godfather should be appointed in those Roundtable reunions if he has enough exp for it.<br />\n<br />\nWhen you see in movies, they appoint their godfathers and stuff, this way it turns out a lot more realistic, so there will be a godfather respected among all'),
(31,2,14,1,'2007-02-17 17:18:07','','[quote]Plus, the godfather should be appointed in those Roundtable reunions if he has enough exp for it.[/quote]<br />\nMight work out, except that the \\\'exp part\\\' of that sentence should\\\'ve been left away. After all, godfather wasn\\\'t going to be ordinary ranking ;)'),
(32,2,12,21,'2007-02-17 17:19:53','','Well Ij really only has questions on the basic unemployed earns. The career earns simply random or fail with no questions asked. '),
(33,2,14,21,'2007-02-17 17:35:57','','For the judicial, police, and governement careers I think they should be very closely tied to make the have to be organized and keep it ballanced. <br />\n<br />\nMaybe the Head of the local government apoints judges and comissioners to there roles and maybe even have more than one district if there are enough people in the city and have one judge and one commissioner and a district attorney for each district and maybe 4-5 cops for each  one and cases be assigned to them by someone in a position of power in the government. I think you get what I\\\'m trying to say'),
(34,2,14,20,'2007-02-17 17:46:26','','that sounds cool :D'),
(35,2,14,22,'2007-02-17 22:28:27','','Bringing the godfather thing back up, The term Godfather is a sight of Respect,Used after a Family Event,Meeting Or Like Say, When you first set up, Then its showing respect (well not when you like stright away set up if ya get what i mean)'),
(36,3,3,22,'2007-02-17 22:35:34','','Well you could have somthing like based in the early 20\\\'s and running up to the late 50\\\'s every month it goes up a year.<br />\n<br />\nlike your family was killed in a mob hit,and your father worked for the Don of (your City), 15 years later,You got into some trouble with a local street gang and they betrayed you and left you for dead, but a Capo from the family see\\\'s you and helps you out ( not realising that your father was his capo). But he says you look familiar,You speak to the don, and he send you to collage , gets degree\\\'s send you to join the police to get the heat of the familia send you to the army to get your weapon skills then offers you to join the familia , start of at dealer,or if youve been agging at sgarrista'),
(37,2,6,22,'2007-02-17 22:38:34','','When i was on the old game,some of the prices for some iteam was seriously high,maybe you could lower them?'),
(38,2,12,22,'2007-02-17 22:40:35','','IJ would of got some off the things of that game from a different game wouldnt they, plus its only borrowing ^-^'),
(39,3,3,16,'2007-02-18 00:16:57','','hmm stu that sound like a pretty cool sorty line'),
(40,2,16,14,'2007-02-18 02:57:09','','Maluco you have nothing better to do than post on these forums? xD<br />\n<br />\nAnyway I think the idea sounds good and you covered pretty much every aspect of the layout of the career, Now for Roger to get to it!'),
(41,2,12,14,'2007-02-18 03:00:24','','What you are saying is kinda going towards the style IJ has running (Like Roger said) I mean the way it is now (in my mind) is perfect and needs no changes.'),
(42,2,6,14,'2007-02-18 03:01:30','','This one feature is why I love you Roger, It makes the game stand out and defines it from other games, Keep it up. :P'),
(43,2,11,14,'2007-02-18 03:04:44','','The stats on the old version were a little too easy to work and \\\"rape\\\" as I like to say it (lolz), Anyway what you have mentioned seems to have a new edge to it?'),
(44,3,3,1,'2007-02-18 05:22:11','','Sounds interesting, but there\\\'s a \\\'but\\\' though. Making it play in the 20\\\'s means we\\\'d have to scratch a lot of stuff simply because it\\\'s too modern. (M-16 in 1920? forget about it). I\\\'d rather have things play in modern times, as it\\\'ll increase the possibilities to improve the game.<br />\nFurthermore, what you present us with is a \\\'fixed\\\' storyline. It\\\'ll surely be fun once, but you\\\'ve got to keep in mind that with a fixed storyline everyone will go through the same trajectory, which will turn annoying in the end. It was already mentioned in #crimesyndicate if I remember correctly, but things like questions that pop up randomly in the game and that decide your character\\\'s... well, character, make it easier to make your character stand out from the rest. I think we\\\'ll have to find a way to weave a more dynamic storyline into this game.'),
(45,3,3,22,'2007-02-18 06:26:47','','I got a similer story from the Godfather game :P<br />\n<br />\nWel if its based at the present, <br />\n<br />\nyour family was killed in a mob hit, now you must take your own path to find out who killed them in the Crime Syndicate'),
(57,2,14,21,'2007-02-23 13:10:34','','ANother idea for a new career..... private investigators.<br />\n<br />\nNone of that \\\"their name ended with wtf\\\" shit. I\\\'m thinking maybe you have to be a former cop to go into this career and they could help you find a murderer of a friend, or the guy who torched your house down... but there has to be some objective way for them to find the info using their heads, not just click an earn and get like a piece of their name cause that would be lame.<br />\n<br />\nif it\\\'s not a nightmare to code that is.'),
(58,2,14,1,'2007-02-23 17:55:22','','By all means post that idea in a topic of it\\\'s own, Kronik. I think it\\\'s a huge improvement over the stupid \\\'their name ended with\\\' mechanism, but it\\\'ll need proper working out...'),
(59,2,14,21,'2007-02-23 18:13:56','','*shrugs*<br />\n<br />\nThis is the careers thread. I figured it went here :P<br />\n<br />\nI\\\'ll move it shortly'),
(60,2,14,1,'2007-02-24 07:33:53','','True, but if all careers went in here it\\\'d be a bit hard to figure out about which career comments are being made etc. ;)'),
(47,1,18,1,'2007-02-18 06:49:48','','As already pointed out by Maluco on IRC, I won\\\'t be able to do all the graphics myself. I simply suck at making graphics (see the old profile pics :P) and I want to focus on the coding anyway. This means someone else, who doesn\\\'t suck at it, will have to make profile pics and item icons etc. It\\\'s a hell of a job, but if you happen to be that person, or know that person that can do this I\\\'d be most grateful if you would reply to this post. :)<br />\n<br />\nI\\\'m not entirely without inspiration on the way the images should be looking, I had a rather cartoonish image style in mind and would like to illustrate this with two examples from the injustice profile pics.<br />\n<br />\n[img]http://injustice.net.nz/images/ranks/NCO.gif[/img]<br />\n<br />\nversus<br />\n<br />\n[img]http://injustice.net.nz/images/ranks/Godfather.gif[/img]<br />\n<br />\n<br />\nI\\\'ll start with saying I prefer the second image. Why is that? The first image shows us a real human, whereas the second image shows us a cartoonish gangster. I think I need to say no more. I\\\'d really like it if we got to have graphics for Crime Syndicate in a consistent cartoonish style. I\\\'d like Crime Syndicate to be identified with that cartoonish style.<br />\n<br />\nSo, if you think you\\\'re up for it, or know someone who\\\'s up for it, don\\\'t hesitate and post!<br />\n<br />\nEdit: Profile pics should be 150x150, item images should be 32x32, although 64x64 is OK as well, as long as they resize nicely.	'),
(48,3,19,1,'2007-02-18 08:47:20','','Stew has already posted a \\\'storyline suggestion\\\' in the forum guidelines topic (where the suggestion doesn\\\'t belong, btw :P), but before anyone puts more efforts into storyline writing, I\\\'ll quickly write up what I had in mind for the storyline. I\\\'m not talking about an actual story here, but rather what the actual story should be about. This is closely connected to the fact that I want a dynamic storyline, and you can\\\'t write a story without knowing what I mean with that.<br />\n<br />\nThe most important thing is that \\\'the storyline\\\' should absolutely NOT be about the character you\\\'re making. A storyline telling the background of your character simply undermines the user-made background story and decreases the uniqueness of characters. I want characters to really stand out from each other, and that\\\'s why, apart from the initial choices you have to make, I\\\'m thinking about adding choices that define your character throughout the game. That\\\'s what makes your character\\\'s story, not a pre-defined story that we can come up with here.<br />\n<br />\nWhat then, is the storyline forum\\\'s use? I have just said that I want character storylines to be dynamic and influenced by choices they make, but there\\\'s yet another storyline that may be fixed. Basically, this would be the story of the world of Crime Syndicate. Crime Syndicate is a simulation of a modern day society, but there must have been a history that has made Crime Syndicate to what it is now. Why are there only X major cities left? What wars have been fought in the past that still influence the world of Crime Syndicate to this day? In other words, a history of how the world of Crime Syndicate \\\'came to be\\\' is much more what I had in mind...							'),
(49,2,13,17,'2007-02-18 11:36:26','','ok GBHs: When you do one you should be able to target a specific part of the body or what it hits is random. Like if you shoot them in the legs they earn slower and stuff and you shoot them in the arms tehy cant attack as well and basically if you dont get it fixed by the doctor it takes off some of your health like every 10 minutes because of blleeding.<br />\nBullets: How about we can buy different kinds of bullets for our guns. Like in rl there are bullets when you shoot somebody they disingrate inside the body so it leaves no bullet evidence. But like have some bullets stronger then others because I dont really feel like googling the types of bullets atm because im too lazy.'),
(50,2,20,17,'2007-02-18 11:54:39','','Porn Carrer:<br />\nCamera Man: Youll be the camera man and your earn will be film the scene. Youll earn between $200 and $400. There is not much benefit at this rank<br />\nFilm Star: At this rank youll actually be the person on camera. Your earn will be have sex with people on film. youll earn between $300 and $500. And like every 20 times you do the earn you finish a movie. After three hours the game will decided how many copys youve sold and youll get a cut of that.<br />\nDirector: Youll be the guy filming the movies. Your earn will be \\\"I watch people have sex and just complain when its not turning me on\\\" At this rank youll hire porn stars for your film industry and based on their exp in the carrer and how hott they are will influence how good your movies sell.'),
(51,2,14,17,'2007-02-18 14:00:21','','I thinks we should have a supreme court and they would tell us what the rules of CS are and what they arent and it will be good'),
(52,2,14,17,'2007-02-18 14:02:29','','This is where ill say something witty when i come back'),
(53,2,14,14,'2007-02-19 01:09:38','','Yea sure Scott... *touches*'),
(54,1,4,13,'2007-02-20 00:34:20','','Sounds pretty interesting I cant wait until its released or well people can get to test it out. Keep up the good work '),
(55,2,21,1,'2007-02-21 07:58:25','','Hey, after googling a bit for icons I could use I figured that the image that can be seen below would be a quite nice style for the navigation images. The image below would of course indicate a \\\'travel\\\' link or something, but I just want comments on how you think it looks/could be improved.<br />\n<br />\n[img]http://www.crimesyndicate.biz/images/nav/nav_travel.png[/img][img]http://www.crimesyndicate.biz/images/nav/nav_income.png[/img][img]http://www.crimesyndicate.biz/images/nav/nav_comms.png[/img][img]http://www.crimesyndicate.biz/images/nav/nav_comms.gif[/img]<br />\n<br />\nedit: I added more images...<br />\n<br />\nOf course, there\\\'d be more images, but it\\\'s the style that matters. Other images would be pretty much the same style...																												'),
(56,2,15,1,'2007-02-21 08:15:46','','Nobody replied to this thread yet, so I had better be the first to do so. :P Anyway, I\\\'ve split up your post and provided my opinion on each part of it ;)<br />\n<br />\n[quote]In real life, you have a special squad, such as SWAT and etc. And you have Intelligence Agencies that work among the government, which could help fighting Gangsters and crimes around the citites[/quote]<br />\nKeep in mind, the police department as you saw it in the old version wasn\\\'t complete. There were still trainings etc. like forensics planned in order to allow for more specialization within the police department. Same goes for intelligence agencies, the federal police career would largely cover that, with features such as going undercover and gathering secret information for dossiers.<br />\n<br />\n[quote]Inteligence Agencies would help out to take out the local families and fight equalliy for the control of the cities, instead of getting gangsters fighting themselves to get room in the cities.[/quote]<br />\nI agree, but only to fill the gaps that the police department can\\\'t fill. As mentioned earlier, the intelligence agencies would supply secret information for cases where the regular evidence is not enough. In other words, they would basically be there \\\'to be called upon\\\'. That\\\'s why I also think it\\\'s a good idea to either make intelligence just a small part of the police career (since there can be periods where you don\\\'t have to do anything for it) or make it a very large career of itself which would also add the dimension of those agencies actively gathering information as they see fit.<br />\n<br />\n[quote]I\\\'m thinking that wars could be fought by legits(PD + Squads + Intelligence Agencies) against Criminals and make it a little more realistic<br />\n<br />\nYet, I\\\'m not saying that wars agains\\\'t families should end, cause I still believe that mafias will always fight for territory, but I\\\'m saying that Legits should have their part in the wars too to make their streets safer[/quote]<br />\nThat\\\'s true indeed, the original aim of Crime Syndicate was to make it balanced between crime and legits. The police would be much stronger than in Injustice, allowing them to actively participate in the fight for control over cities. However, I feel Crime Syndicate should not only revolve around \\\'legit vs crime\\\'. The purpose of having cities in different countries was that countries could attack other countries as well, which is where the army would come in. This would also add more interesting options for the crime career. For example, rather than defending a country, criminals could seek to destabilize it from the inside if they\\\'d so desire. This makes for a change of roles where criminals would suddenly become rebels, or even revolutionalists. (Not visible as in a rank name, but essentially it would be so).<br />\n<br />\nAnyway, this all goes way beyond the scope of the police department... I hope there were some useful points in the comments :P'),
(61,1,18,36,'2007-02-28 23:04:34','','Gangster Career:<br />\n[IMG]http://i93.photobucket.com/albums/l67/Euphorikal/Profile%20Pictures/GangsterGodfathercopy.jpg[/IMG]<br />\nLaw Career:<br />\n[img]http://i93.photobucket.com/albums/l67/Euphorikal/Profile%20Pictures/LawLawyercopy.jpg[/img]<br />\nPolice Career:<br />\n[IMG]http://i93.photobucket.com/albums/l67/Euphorikal/Profile%20Pictures/PoliceDetectivecopy.jpg[/IMG]																					'),
(62,1,18,13,'2007-02-28 23:56:14','','Damn those are some nice pics'),
(63,1,18,3,'2007-03-01 03:09:40','','i just have to vote for euphorikal :D if he wants the job any way :S lol'),
(64,2,14,3,'2007-03-01 03:27:49','','another idea is for the fake careers the gangsters get might wanna add a few more choices like army bank so on so forth'),
(65,5,22,3,'2007-03-01 03:40:06','','yippeee lol<br />\nthought id put sometyhing in here seeing that it looked so lonely :( lol'),
(66,5,22,1,'2007-03-01 03:42:18','','True, it looks very lonely... And you needed to get your post count up as well eh? :P'),
(67,2,21,3,'2007-03-01 03:42:46','','nice and simple but evective :D sounds great roger :D'),
(68,2,13,3,'2007-03-01 04:26:28','','on the bullets theres a speacial ops weopon that is uses bullets created from the current enviroment like in snow regions the gun compresses the snow to metal denseness and later on it thaws out <br />\nbut also for more commen bullets ill use nicks like armour piercing (ignore set amount of armour)<br />\n9 mm(low damage more acurate) <br />\nstirs rounds i cant remember size but yer (med damamge and high accuracy) anyway you get the idea lol sorta like a round for each occasion type thing :P and also add laser pointers for increased chance of hitting or someshit but also bufore hand theres a chance of seeing the red beam :P as an event'),
(69,5,22,14,'2007-03-01 21:25:40','','Topic: genral<br />\n<br />\nGuess you shouldn\\\'t have dropped out of school at the age of 13 eh kali?'),
(70,1,23,1,'2007-03-02 12:24:40','','I added a user list. I think, if your browser supports it, that is, many of you will prefer it over the traditional \\\'always visible\\\' version. I\\\'ve added a little icon tray in the right navigation bar and the only icon currently available will show you the users that are currently online. I intend to add more of these icons that will bring up windows in the future. A perfect example would be your inventory.<br />\n<br />\nNow, about browser support. I\\\'ve tested it in Firefox 2, IE 7 and Opera 9 so far, and opera 9 seems to have trouble overwriting the \\\'loading users\\\' message. I don\\\'t have IE 6 but I would not at all be surprised if it fucks up. You\\\'ll just have bad luck then :P<br />\n<br />\nAnyway, let me know what you think.'),
(71,1,23,36,'2007-03-02 20:36:20','','cant see it :S<br />\n<br />\nim using firefox 2'),
(72,1,23,1,'2007-03-03 03:42:13','','You\\\'re sure you clicked the user list icon in the right navigation bar?'),
(73,1,23,36,'2007-03-03 18:10:55','','had to clear my cache >_>'),
(74,1,23,14,'2007-03-03 22:53:37','','I like how it pops up like that, Although the older way you mentioned is better :P<br />\n<br />\nBut!  yes... Good work lol							'),
(75,1,18,14,'2007-03-03 22:54:42','','Pro from the west... (h)'),
(76,1,18,20,'2007-03-05 00:42:29','','Euphie is the man for the job :D'),
(77,3,19,41,'2007-03-06 11:03:42','','[quote]Basically, this would be the story of the world of Crime Syndicate. Crime Syndicate is a simulation of a modern day society, but there must have been a history that has made Crime Syndicate to what it is now. Why are there only X major cities left? What wars have been fought in the past that still influence the world of Crime Syndicate to this day?<br />\n[/quote]<br />\n<br />\nThis gave me a small idea, but it really depends on where your going with the game, and how it functions. Theoretically, it can be possible to create a real-time history and story. Properly setup, the game can essentially log major actions, for -example-, a declaration of war. Whoever held the authority to create war, would perhaps have to write a description of what the war was originally about, name the war, and misc. other details about it, after the \\\'declared end\\\' of the war, a victory or defeat could be logged for it in the city\\\'s history, and some other stuff if necessary. <br />\n<br />\nBasically what I\\\'m saying is that each city can have a formal log, that can tell and act like a history book. However this requires a lot of attention to detail, and additional features that don\\\'t really hit top of the to do list. Although its a suggestion to oppose a constant history. It\\\'d probably be worth the time to explore the idea of different \\\'history systems\\\' though.																												'),
(78,1,18,36,'2007-03-09 00:20:30','','[IMG]http://i93.photobucket.com/albums/l67/Euphorikal/Profile%20Pictures/Crime%20Syndicate%20Profile%20PIctures/unemployed.jpg[/IMG]						'),
(79,1,18,1,'2007-03-09 03:07:03','','Brilliant, as ever... Now people will have something prettier to look at in their account screens :P'),
(80,5,22,11,'2007-03-21 06:04:02','','Xanther don\\\'t make me talk about you >.< :D'),
(81,6,24,18,'2007-08-27 15:43:23','','At the same time this costs a lot of time as well<br />\n<br />\nThis was in the Donation area, where you are redirected to after you click play.. The wording is wrong... <br />\n<br />\nReWorded: At the same time, this costs a lot of our time, <br />\n<br />\n<br />\nThanks :)							'),
(82,6,25,1,'2007-08-27 15:43:30','','Dear reader,<br />\n<br />\nWelcome to the Bug Reporting forum of Red Republic! As this game will always be updated with new content, there will always be new bugs and exploits that we would like to know about. You can report these here. There are certain rules for this forum, which I shall list below:<br />\n<br />\n- First of all, read the forum rules! For those who don\\\'t/didn\\\'t, a summary can be found next.<br />\n- Please do not double post, rather edit your own post, in order to minimize database-load.<br />\n- Refrain from posting the wrong topics in the wrong forum. This goes for replies as well, please stick to the topic.<br />\n- Please make a new thread for each bug, unless your bug has already been reported in which case you have to add to that thread.<br />\n- Furthermore, when creating a topic or reply, please bear in mind that we will not allow content that expresses racism or discrimination. Such post will be deleted without furter doubt!<br />\n- Last, but not least, our admin team has the right to modify or delete any post on this forum, and by posting you agree to this.<br />\n<br />\nWith dear regards,<br />\nRed Republic Admin Team'),
(83,6,24,18,'2007-08-27 15:45:08','','Your topic \\\'Typo\\\\\\\'s\\\' was posted successfully<br />\n<br />\nWhen i posted this... it has placed an \\\\ infront of the \\\'...'),
(84,6,24,18,'2007-08-27 15:46:12','','You were successfully logged out from Crime Syndicate.<br />\n<br />\nAlso, says when logged in... <br />\nSuggest rewording to Red Republic!'),
(85,6,24,1,'2007-08-27 15:49:37','','Yes, I\\\'m aware of the many instances of \\\'Crime Syndicate\\\' that will still have to be replaced with \\\'Red Republic\\\'. We\\\'ll see to that eventually. Basically, as we go along making changes to files we will also update these \\\"typo\\\'s\\\" whenever we come across them. As to your other report, I\\\'ll take a look at it and I might make some changes in the wording. Do keep in mind, however, that these changes may not become apparent immediately, seeing as we do a lot of development off-line.<br />\n<br />\nEdit: about the / in front of the apostrophe, it\\\'s the way things are stored in the database meaning I have simply forgotten to parse them everywhere. I should have fixed most of those issues by now.					'),
(86,2,26,18,'2007-08-27 15:52:37','','When you first log in, the donations area, i suggest placing a link after the text, to go to the donations page!'),
(87,2,27,18,'2007-08-27 15:53:40','','I Suggest having A Favicon, as currently, you dont have one! '),
(88,2,27,1,'2007-08-27 15:54:39','','Agreed, I\\\'ll get our graphics department to work :P'),
(89,2,28,18,'2007-08-27 16:01:49','','Some of the Clothing images are missing, i suggest making some :)'),
(90,5,22,18,'2007-08-27 16:09:14','','You spelt the title wrong :p'),
(91,6,29,18,'2007-08-27 16:27:50','','I tried to trick people in a callcenter duty, and got this earn message<br />\n<br />\nYou tried to sell fake-guns to a local police station. They weren\\\'t happy!<br />\n<br />\nIs this a bug?'),
(92,6,30,18,'2007-08-27 16:29:23','','With the emoticons, they have a white background, i suggest changing there background to the colour of the forums, so they will blend in!'),
(93,2,31,18,'2007-08-27 16:34:33','','When you try to earn, but you cant, you get an error:<br />\n<br />\nYou cannot do another job so quickly! You hardly recovered from the last!<br />\n<br />\nI suggest you add a counter, maybe a Javascript counting down, Example:<br />\n<br />\nYou cannot do another job so quickly! You hardly recovered from the last! You have TIME Before you can Earn again... Your Exhausted!'),
(94,2,32,18,'2007-08-27 16:38:36','','I suggest you have Capital letters in the earning Section... Capitals for names and such.<br />\n<br />\nExamples:<br />\n<br />\n<br />\nFitting jobs<br />\n	Work in the local Dairy Factory<br />\n	Clean out the Public Toilets<br />\n	Archive Dossiers in an Office<br />\n	Work as Bouncer at a Nightclub<br />\n	Work as Barman at a Nightclub<br />\n	Trick people during a Callcenter Shift<br />\n	Clean Toilets at the Local Brothel<br />\n<br />\nAlso, i have reworded some, to what i feel would be better, not sure if you like them!'),
(95,2,33,18,'2007-08-27 16:42:19','','i Suggest moving the log out switch, to with the Char options, or adding a pop-up asking you to confirm that, as it can easily be pressed, by accident when trying to get the Account options!'),
(96,2,33,1,'2007-08-27 16:45:53','','Good Idea, I still think I\\\'ll have logout in the upper link bar, but I suppose it wouldn\\\'t hurt moving it away from the account button...'),
(97,2,32,1,'2007-08-27 16:47:17','','I\\\'ll certainly go with the callcenter shift, that\\\'s exactly the word I was looking for. As for the others, I\\\'ll keep them in mind and discuss them with Aaron as well ;)'),
(98,6,30,1,'2007-08-27 16:49:15','','I should actually give them a transparent background and change them into .png\\\'s. However, that is going to take some time and motivation from my side and I think those two are better spent on in-game issues right now ;)'),
(99,6,29,1,'2007-08-27 16:50:27','','I suggest you re-word it. What I meant was, at the callcenter you try to sell products to people, and obviously trying to sell fake-guns to a police station isn\\\'t a very smart thing to do. But you\\\'re right in that it may look a bit odd '),
(100,6,29,18,'2007-08-27 17:01:18','','Ive Re-Worded it, so it makes more sense..<br />\n<br />\nYou tried to sell Fake Equipment to a local Police Station during you shift at the call center... That was incredibly dumb!<br />\n<br />\n*shrugs*<br />\n'),
(101,2,34,18,'2007-08-27 17:08:15','','Ive made this post, so i can post in things i suggest to be rewording..<br />\n<br />\n<br />\nThis is the Cheese Earn thing:<br />\n<br />\nYou sent a plastic cheese-cover through the cutting machine and broke the production line!<br />\n<br />\nSuggested Re Wording:<br />\n<br />\nYou sent a Plastic Cheese-Cover through the Cutting Machine and broke the production line, You had to use Your day\\\'s pay to fix this!<br />\n'),
(102,2,34,18,'2007-08-27 17:13:32','','Cheese Production Earn:<br />\n<br />\nOriginal:<br />\nThe cheeses made you sick today, you went home feeling shaky...<br />\n<br />\nReWorded:<br />\nThe Cheeses at the Factory made you feel Sick Today, you went Home Early, And lost your pay... <br />\n<br />\n'),
(103,2,34,18,'2007-08-27 17:18:15','','Cheese Earn:<br />\n<br />\nOriginal:<br />\nYou made many cheeses today and brought back a meager $31.<br />\n<br />\nRe Worded:<br />\nYou made many types of Cheeses Today and Brought back a measly$31 for your hard work!'),
(104,3,19,18,'2007-08-27 17:35:02','','I like the idea of a city log... the war and such, would be awesome to have!'),
(105,1,18,48,'2007-08-27 22:07:18','','::Item List::<br />\n<br />\n[img]http://i15.tinypic.com/62f72vo.jpg[/img] - Box Of Cigars<br />\n[img]http://i11.tinypic.com/4vsqced.jpg[/img] - Dirty Sweater<br />\n[img]http://i13.tinypic.com/53tw0hv.jpg[/img] - Doctors Bag<br />\n[img]http://i13.tinypic.com/62ihaj6.jpg[/img] - Filthy Old Sweater<br />\n[img]http://i17.tinypic.com/4mlmd94.jpg[/img] - Handbag<br />\n[img]http://i11.tinypic.com/5x649xl.jpg[/img] - Linen Bag<br />\n[img]http://i9.tinypic.com/4ku6g7l.jpg[/img] - Painted Linen Bag<br />\n[img]http://i13.tinypic.com/5zdslk9.jpg[/img] - Playstation 3<br />\n[img]http://i19.tinypic.com/63hr9mp.jpg[/img] - Ragged Blue Pants<br />\n[img]http://i11.tinypic.com/63heskk.jpg[/img] - Small Plastic Bag<br />\n[img]http://i15.tinypic.com/54myhle.jpg[/img] - Tramps Bag<br />\n[img]http://i17.tinypic.com/5zc3xus.jpg[/img] -Trousers<br />\n<br />\nThere\\\'s the items, that you gave me to do a picture for. Hope you like ;). Hit me up on MSN if you need anymore done.<br />\n<br />\nEdit by Roger: I changed the links into the actual images!																				'),
(106,2,28,48,'2007-08-27 22:12:00','','Look in the graphics section, i made some ;).'),
(107,2,33,48,'2007-08-27 22:12:44','','I reckon the log out button should be placed at the end of the navbar. That\\\'s where it\\\'s usually place.'),
(108,2,31,48,'2007-08-27 22:13:17','','That\\\'s a good suggestion. I hate not knowing when to earn xD.'),
(109,2,13,48,'2007-08-27 22:17:33','','I personally would hate to see bullets installed because the game shouldn\\\'t be all about money and having bullets is just gay xD.<br />\n<br />\nGBH\\\'s: Depends on the weapon whether it\\\'s slow earns, or a timeout. should knock off like 10-20 Health Points. Can\\\'t make it too realistic with bleeding or everyone is going to die, lol.Won\\\'t be enough doctors to install that.'),
(110,5,22,48,'2007-08-27 22:22:59','','Learn to spell ;).'),
(111,1,23,48,'2007-08-27 22:23:29','','Works for me and looks good ^^, Isn\\\'t it possible to have it on every page at the bottom?.'),
(112,6,35,48,'2007-08-27 22:25:55','','Aggravated Crimes<br />\n<br />\nAggrevated Crimes are the way ruthless gangsters and experienced criminals make money. Don\\\'t go here unless you are confident that you are strong enough to perform these major crimes. Always remember that the police is the least of your fears if you messed these up... <br />\n<br />\nShould be <br />\n<br />\n[b]Aggravated[/b] Crimes are the way ruthless gangsters and experienced criminals make money. Don\\\'t go here unless you are confident that you are strong enough to perform these major crimes. Always remember that the police is the least of your fears if you [b]mess[/b] these up... '),
(113,2,36,48,'2007-08-27 22:27:43','','1]    I reckon timers should be a donator\\\'s option.<br />\n2]    Custom Profile Avatar.<br />\n<br />\nThere can be some donating options ;).'),
(114,1,4,48,'2007-08-27 22:28:10','','Sounds good >_>'),
(115,2,36,14,'2007-08-28 05:55:46','','Want to copy anything else from Injustice while you are at it?'),
(116,2,31,14,'2007-08-28 06:00:02','','Dont really see the need for this, There are plenty of ways you can keep count of how long until your next earn...<br />\n<br />\nI myself use egg timers.. Simple and does the job.'),
(117,2,31,1,'2007-08-28 06:02:17','','Besides, having no timers means it\\\'s up to your determination and motivation to keep doing earns in quick succession. I wouldn\\\'t want to ease that process for players ;)'),
(118,6,35,1,'2007-08-28 06:06:09','','Fixed both of them on my local server, should be updated in the live version sooner or later...'),
(119,2,36,48,'2007-08-28 06:20:40','','Well no offense but they\\\'re an obvious choice, i would have chose them if Injustice wasn\\\'t created....'),
(120,6,37,14,'2007-08-28 06:21:28','','When ever i deposit money into my bank account it takes the fee off my hand, but it puts my money on hand into negatives...<br />\n<br />\nNow I consider this a \\\"real\\\" bug....<br />\n<br />\nI demand that you fix this Roger because its making me poor. :('),
(121,6,37,48,'2007-08-28 06:23:26','','lol you got enough money for the bank how O.o xD'),
(122,6,37,1,'2007-08-28 06:30:23','','Fixed ;)'),
(123,2,31,18,'2007-08-28 06:36:28','','You could add this as a donation option... im sure people would donate cash for things like that'),
(124,2,31,48,'2007-08-28 07:09:12','','I would probably donate later on.'),
(125,2,38,48,'2007-08-28 07:21:44','','I deposited $432 just now but is there anyway i can see it? Not in account page. Bank Money = $ (Blank).And didn\\\'t see any options in the bank to see. I went to withdrawal and it didn\\\'t actually say how much i had in there. Maybe you could add it?'),
(126,2,38,1,'2007-08-28 07:29:19','','I\\\'m working on that now, there will be an account overview page with all transactions and the current balance for each of your bank accounts.'),
(127,2,38,48,'2007-08-28 07:36:36','','Okay that sounds good :)'),
(128,1,39,17,'2007-08-30 23:12:56','','Roger can I be admin LOL<br />\nnah seriously sup homeslice? <3 its been a while ;o You didnt call me last night Roger why is that :('),
(129,1,39,1,'2007-08-31 01:59:29','','I\\\'m sorry hun, I had to defend your very existence in a phonecall with my ex that took WAY longer than I wanted it to take. And the compromise I ended up making included that you can\\\'t be admin :('),
(130,1,39,17,'2007-08-31 11:54:09','','with your ex? if shes your ex why is she still bashing us Roger ;s'),
(131,6,40,48,'2007-09-01 01:39:08','','You cornered EchO in a deserted street with malfunctioning streetlights. Before he could plead with you, you sank your fists into his stomach and began hitting him wherever you could! As seconds passed, his resistance got weaker until finally you delivered the killing blow! You grabbed $527297 from him and ran away!<br />\n<br />\nHowever he is still alive; can agg/whack, but no information shows, or anything. He can earn as well. Kind of crazy eh?'),
(132,6,41,48,'2007-09-01 02:32:10','','When equipping an item you already have on, it just removes the one already there and places the one you just selected on, therefore deleting one and adding it on. Shouldn\\\'t it just say no, already got that item equipped?.'),
(133,2,42,48,'2007-09-01 02:33:31','','Be able to change it through the game. Like background... Role-Play be able to update information as it happens =].'),
(134,2,43,48,'2007-09-01 02:35:30','','Should be a suicide option >_> For those depressed, lonely, absolent people :)'),
(135,6,41,48,'2007-09-01 16:23:39','','Seems it\\\'s fixed now, good job.'),
(136,6,40,48,'2007-09-01 16:24:01','','Fixed as well, good job!'),
(137,6,41,1,'2007-09-01 18:33:23','','As far as my knowledge goes (I\\\' *did* write the code...) it basically \\\'swaps\\\' items. So if you\\\'ve already got an item equipped, it will put that item into your inventory, and put the item that was in your inventory into the equipslot...'),
(138,2,43,1,'2007-09-01 18:44:34','','I consider that a luxury item... In any case, not something on top of the to-do list :P'),
(139,6,41,48,'2007-09-02 01:19:03','','Yes it does tested it many times :)'),
(140,6,40,1,'2007-09-02 10:18:07','','To be honest, I wasn\\\'t quite prepared for the speed at which you  managed to whack the first player... Kind of makes me wonder whether you are testing or actually playing? :P'),
(141,2,42,1,'2007-09-02 10:19:11','','Perhaps, but like suicide that is basically a luxury feature and probably won\\\'t be done anytime soon...'),
(142,6,24,10,'2007-09-02 10:31:14','','There are some typo\\\'s in the player guides as well ;)<br />\n<br />\n\\\"How offten am I able to earn?<br />\nHow offten am I able to commit a crime?<br />\nHow offten am I able to study or train?\\\"<br />\n<br />\nI believe \\\"offten\\\" has to be \\\"often\\\" '),
(143,6,24,1,'2007-09-02 10:32:18','','Yes, I suggest our support manager takes an extra course on spelling.'),
(144,6,40,18,'2007-09-02 13:13:29','','Phase tried to kill me... And i didnt get any report... just lost health, this should be fixed, because it is weird, and will cause problems in the future`<br />\n'),
(145,6,24,48,'2007-09-02 13:43:23','','While you were stalking MrTinketi you found out that they are healthy, but have previously suffered injuries. You also found the victim to be carrying extremely large amounts of money. They bought only the most expensive items and went only to the most expensive bars. MrTinketi didn\\\'t [b]apear[/b] very smart, infact they [b]semt[/b] downright dumb at times.<br />\n<br />\n1st bold=appear<br />\n2nd bold=seemed'),
(146,1,44,1,'2007-09-02 15:08:10','','Hey all,<br />\n<br />\nAlthough it is sort of obvious what changes have been made during the past week (seeing as you tested them yourselves), I thought it would be a good idea to summarize and explain them here, in the forums, nonetheless. I have provided a few short explanations on most additions so you know what\\\'s going on.<br />\n[list] <br />\n[*]A new aggravated crime, Stalking<br />\n[*]The addition of the conflict page and the ability to attempt murder<br />\n[*]A weaponshop<br />\n[*]Stocks in shops, shops no longer sell limitless supplies of items<br />\n[*]Petty crimes<br />\n[*]Agg/kill protection timers<br />\n[*]Equipping/de-equipping items<br />\n[*]A brand new character statistics screen<br />\n[/list]<br />\n[b]Stalking[/b]<br />\nStalking is the new aggravated crime that Aaron made (I thank him a thousand times for that, as it\\\'s simply brilliant in my opinion). The idea is that, when stalking, you try to gather information about your victim. As of yet, there is information about your victim\\\'s health, your victim\\\'s money (in hand) and your victim\\\'s intellect. We don\\\'t provide flat out information about how strong your victim is (intellect might give something away about your victim\\\'s total exp, but more often than not, it doesn\\\'t), as that might make the game a bit too easy. Also, the fact that you succeed stalking doesn\\\'t give any information about your chance to succeed a mug, for example, as we made stalking failry easy to succeed at.<br />\n<br />\n[b]Murdering...[/b]<br />\nWell, there\\\'s little left to tell about this, I think I can safely say it\\\'s the most-used feature at the moment... The only thing I can add to this is that we will most probably weaken bare-hands killing a bit, and upgrade the existing weapons (or add new, better weapons).<br />\n<br />\n[b]Weaponshop[/b]<br />\nThe weaponshop restocks every 12 hours, getting some stocks of certain weapons. As the amount of weapons in-game increases, the diversity of the stocks will increase as well. <br />\n<br />\n[b]Stocks in shops[/b] Speaks for itself. Limitless supplies isn\\\'t very realistic *and* affects the value of items negatively. Though that may not be much of a concern now, it will be when more valuable items are added and item-trading is implemented. <br />\n<br />\n[b]Petty Crimes[/b]<br />\nThe only petty crime, nick from stores, that is available at this time allows you to steal relatively worthless crap from an imaginary shop. Although criminal earns will mostly yield dirty money, this one indirectly yields clean money because you can sell the items you gain.<br />\n<br />\n[b]Agg/kill protection timers[/b]<br />\nThere is now protection in the game... This is basically because Phase got \\\'really into\\\' the game and acted a bit over-enthusiastically at the opportunity to be able to shoot people. The only exception to this protection is the stalker agg. We figured it shouldn\\\'t trigger protection timers so as to stimulate cooperation (\\\'You stalk the guy, I\\\'ll mug him if he turns out to have money...\\\'). The protection after an agg is 1 hour and 40 minutes (4 times the agg timer) and the protection for a murder attempt is 3 hours.<br />\n<br />\n[b]Equipping/de-equipping items[/b]<br />\nObviously, this is a very needed feature. It doesn\\\'t require much explanation. You are bound by the tier requirements on certain items. To equip an item you simply click its image in your inventory. To de-equip it, click it in your character statistics screen. <br />\n<br />\n[b]A brand new character statistics screen[/b]<br />\nSpeaks for itself as well. If there\\\'s anything not clear about it, don\\\'t hesitate to ask!<br />\n<br />\nRegards, <br />\nRoger'),
(147,1,44,48,'2007-09-02 15:41:48','','Niceeeeeeeeee<br />\n<br />\n\\\"<br />\nAgg/kill protection timers<br />\nThere is now protection in the game... This is basically because Phase got \\\'really into\\\' the game and acted a bit over-enthusiastically at the opportunity to be able to shoot people. The only exception to this protection is the stalker agg. We figured it shouldn\\\'t trigger protection timers so as to stimulate cooperation (\\\'You stalk the guy, I\\\'ll mug him if he turns out to have money...\\\'). The protection after an agg is 1 hour and 40 minutes (4 times the agg timer) and the protection for a murder attempt is 3 hours.<br />\n<br />\n\\\"<br />\n<br />\n< I own.'),
(148,6,45,48,'2007-09-04 11:10:09','','Whacking system keeps braking :( Goes to white page when you try to whack someone.'),
(149,6,45,1,'2007-09-04 12:05:51','','Thanks for posting this bug. Earlier, I changed the script to add a normal event as well, when being shot at but not killed, and I left out some necessary syntax... It\\\'s been fixed, should work as ever again...'),
(150,7,46,1,'2007-09-06 15:43:52','','Hey guys,<br />\n<br />\nThis is the replacement of old CS\\\' admin forums - basically it\\\'s a forum that no-one but us can see. Having said that, I\\\'ve added the forum for a reason. As developers we are responsible for making this a great game and as such, discussing features or other game-related issues is a necessity. As we can\\\'t always have direct chats with each other (and direct chats don\\\'t allow for large amounts of texts to explain ideas/concepts) I thought we could use this forum to exchange ideas and concepts and any other game-related things that you wish other developers to know about.<br />\n<br />\nRegards,<br />\nRoger'),
(151,7,46,54,'2007-09-06 15:49:07','','Hey ho! Present and accounted for! :) Will post some ideas when I got them! :) '),
(152,7,46,1,'2007-09-06 15:51:47','','That\\\'s nice of you. It\\\'s not as if ideas are suddenly required to be written down now that I\\\'ve added this forum, but I thought it\\\'d be a handy means of communication. Also, IF someone has that next great idea, there is at least a place to put it ;)'),
(153,2,42,54,'2007-09-06 18:23:41','','I might actually add this in now with the profiles, they seem abit bare at the moment.'),
(154,7,47,1,'2007-09-07 17:35:59','','[b]Introduction[/b]<br />\nThough brainstorm may not accurately describe this topic, I do feel that the crime career is going to be a major challenge for Red Republic to conquer. In Crime Syndicate it was the one career that wasn\\\'t as well thought out as the others and its mechanics were severely flawed. If Red Republic is to become popular, this is something it has to improve upon.<br />\n<br />\n[b]The good parts[/b]<br />\nBefore further bashing the old crime career and stressing the point that we\\\'re in need of a better one in this game, let me list a few good/avarage parts of the old version. Of course, there were the ranks, which I think could return in Red Republic slightly adjusted. They are listed below:<br />\n[list]<br />\n[*]Pickpocket<br />\n[*]Dealer<br />\n[*]Piciotto<br />\n[*]Sgarrista<br />\n[*]Capodecime/Caporegime<br />\n[*]Capo Bastone (2nd in command)<br />\n[*]Consigliere/Contabile (advisors)<br />\n[*]Capo Crimini (Don)<br />\n[/list]<br />\nPartly invented, partly based on what I could find on the internet about the mafia family structure this list was assembled. The problem is, the names (in my opinion) aren\\\'t as gripping as Injustice\\\'s ranks, but simply copying from Injustice is not an option I think. Furthermore, the ranks listed here are not very much in order of linear progression, especially not in the bottom part of it. Basically, the ranks of consigliere, contabile and capobastone are not ranks that you naturally grow into. They are more like special positions that you have been assigned to as a special honor. <br />\n<br />\nIt is that last problem that might also hold the key to a unique crime career, at least, different from that in Injustice. Though it is certainly nice to have the certainty that you will always rank if you just gather enough exp, I think the game would become more intreging if we side-step from the path of linear progression, and instead look at ways to make player stand out in a crime family other than gaining a lot of exp. This is a challenge, and I\\\'m not sure if we can come up with a good framework, but it\\\'s definately worth a try.<br />\n<br />\nAnother good part of the old game was the fact that you weren\\\'t \\\'just invited\\\' as a member to a family once you had enough exp. You actually had to start actively gathering contacts in the streets (this was an agg and it\\\'d put names of higher ranked gangsters on a list of yours). Once you got yourself a few contacts you could start doing jobs for them, and eventually you\\\'d be invited into the actual family (and become a made man). I think this mechanic should be preserved as well.<br />\n<br />\n[b]Moving forward?[/b]<br />\nThis being a bit of a summary of the old crime career I realise we will need fresh ideas and concepts to move forward and create a better career. A huge challenge is, I think, the drug-trade part of the game. It would be really interesting to introduce the concept of trade routes into the game, allowing for more efficient drug transports than the individual smuggling operations as are currently in Injustice. Securing these trade routes and setting them up should be a real challenge to mafia families but with a great reward: money through drug-selling! <br />\n<br />\nOf course, it is our challenge to think this through and make it work together with other elements intuitively, thus ultimately resulting in smooth gameplay and praises from many players! :P<br />\n<br />\nI\\\'ll be happy to hear your input.<br />\n<br />\nRegards,<br />\nRoger'),
(155,7,47,54,'2007-09-08 18:20:52','','Mmmm... <br />\n<br />\nOk, well here are my thoughts on the whole thing, I\\\'ll just brainstorm in this post. IJ\\\'s gangster career\\\'s is probably one of its strongest careers. I like the ranks, apart from pickpocket as I wouldn\\\'t personally see that as gangster but what can you do! I\\\'ll just list of things that I don\\\'t like about IJ\\\'s gangster career and we\\\'ll try build on them.<br />\n<br />\n1. The black market. Never used, pointless in the game, apart from it gives a boost to stats. I\\\'d like to see it more worthwhile!<br />\n2. Agg\\\'s.. The AGG\\\'s make ranking to predictable. And then some of them are stupid to fail, or succeed 100% of the time in. Like come on, you can continue torching and AR\\\'ng as many buisness\\\'s as you want once you hit a certain rank. I\\\'d like to see things like CCTV in buisness\\\'s like in real life, but thats kind of going of point. Anyway, thats my thought on AGGs.<br />\n3. Familys themselves, To steal an idea from IJ that will never be implemented I think a brilliant idea would be for when a family member goes to jail for it to be an option to them to sell out their CL or Godfather, this would stop just everyone joining one family in a city and really make people wonder who they trust. <br />\n4. Cops. If I was a gangster, I wouldn\\\'t know what cop busted me. And I shouldn\\\'t either. This makes the game more balanced and has always been a problem for IJ, Judges also shouldn\\\'t be able to see who closed the case, and vice-versa a gangster shouldn\\\'t be able to see what Judge jailed him. <br />\n5. Drugs, I gotta aggree, I\\\'d love to see more secure drug routes that were really hard to get but made you enough money that it was worth it, however the only ways I could think of would be buying the funeral parlour? Or perhaps employing someone in the funeral parlour fulltime [automatically money gets taken out of your account each week for them working for you] but there is a high chance of them going to jail for it so not many people would want to? Only one I can think of!<br />\n6. Droughts, its a real life thing, and I think it would be brilliant to have in game. In drug droughts prices go up, and its usually to do with a gangster getting busted or what not.. <br />\n<br />\nI\\\'m all out for now!<br />\n'),
(156,2,42,54,'2007-09-08 22:57:49','','Quote has been added. Will be available once I upload the new files to the site.. '),
(157,7,47,1,'2007-09-10 03:49:08','','I\\\'ve been thinking about the crime career a bit more lately and there have been a couple of things that I would like to share with you. Note, there\\\'ll probably be a lot of comparisons with Injustice again, but you\\\'ll have to forgive me for that. After all, it is about the only other game that we all played. As a reference, therefore, it serves quite well.<br />\n<br />\n[b]Non-linear ranking[/b]<br />\nWhat you see in Injustice is that many careers employ, what I like to call, linear ranking. The rank of a player is directly proportional to the amount of experience gained in that particular career. While this is not necessarily a bad thing (it gives the player the certainty that, unless he simply does nothing than chatting with other players, he will rank further in his career), it is also not very exciting when you do it for the Xth time.<br />\n<br />\nInjustice\\\'s crime career is less linear than its other careers, it has to be given that. Of course, its crime career is Injustice\\\'s major selling point so it can\\\'t afford to be repetitive. I think Injustice\\\'s crime career is something to keep firmly in the back of your mind while discussing our own crime career, seeing as it\\\'s definately quite polished.<br />\n<br />\nWhen looking at Red Republic\\\'s gangster ranks I see many similarities to the ranks as we can find them in Injustice. The lowest two ranks are basically dummy ranks, only being there to form the step-up towards being part of a mafia family. That is exactly what a Pickpocket and a Dealer will be doing. They will acclimatise to the gangster world and try to find contacts in big, influential families. Ranking here is linear, I see no other way as players with these ranks are simply not in a family yet, which means that they can\\\'t be ranked based on their value to the family.<br />\n<br />\nFrom the rank of piciotto, players are \\\'made men\\\', sworn into a family. However, linear ranking is still required here because I intend to allow people to rank up to caporegime without the requirement of being in a family. This is because I want people to be able to set up their own family if they want to, not requiring them to rank through another family first. Injustice uses this as a game mechanism to make sure there aren\\\'t too many mafia families, but I think players will be able to sort this out by themselves. After all, if a \\\'new\\\' family is a threat to an existing family it is exterminated easily. Not to mention that being in a large family will still be more attractive, seeing as the potential revenue for individual gangsters is way higher, due to the established drug trade-routes and contacts.<br />\n<br />\nThus, piciotto and sgarrista should be achievable by simply having enough exp, but I think it would be possible to have families offer something extra to players with these ranks. Much like the hitman and cleaner positions are something \\\'extra\\\' to the capo ranks in Injustice. Next to the achievement of ranking, players can then also go for that extra achievement, namely the achievement of getting to that special position in the family. How exactly this should work, or what position(s) we\\\'re talking about, I don\\\'t know yet. This is a rather vague outline :P<br />\n<br />\nThen, a caporegime. At this rank you can actually start your own family. However, unlike Injustice, I was thinking that when the player has started his own family he would be instantly \\\'promoted\\\' to Capo Crimini. Of course no experience would be added, but in title the player would be Capo Crimini because he is in fact leader of the family. As you can see, this is a non-linearity. The rank of Capo Crimini is no longer bound to an amount of experience but it is exactly what it portrays to be: the rank you get when you are the head of a family.<br />\n<br />\nI think it\\\'s possible to push this principle even further. For example, if you are a gangster without family, why rank like one? Sure it looks cool to be \\\'sgarrista\\\', but you are no real sgarrista unless you\\\'ve been sworn into an actual family. Perhaps we should make a distinction between gangsters in families and gangsters without one.<br />\n<br />\n[b]Going beyond crewfronts[/b]<br />\nWhile the ranks are one important part of a crime career, another important part is the moneysinks in it. The crime career has a huge potential to bring in quite a lot of money and this would be disastrous to the economy. If gangsters can afford anything there will be a huge inflation and items will be unaffordable for people in less rewarding careers. Thus moneysinks are needed, without a doubt. Crewfronts are quite effective at that, but they are a bit passive. You gather a hell lot of money, buy your crewfront and never look back. I think we can come up with something more intuitive.<br />\n<br />\nBasically, I was thinking (especially after all the news of the italian mafia terrorising villages in southern italy) that what is essential to a family, is to infiltrate deep into the local legit life. You can\\\'t successfully \\\'do your business\\\' if you haven\\\'t got at least one person infiltrated or bribed in the police department. You can\\\'t expect to smuggle drugs if you aren\\\'t at least friendly with the local customs office. That example, by the way, would perhaps be more fitting under the drug trade header. The point I\\\'m getting at, though, is that instead of crewfronts we could perhaps have a system in which this infiltration factor plays a role. Starting from the principle that a mafia family can\\\'t grow big if it isn\\\'t sufficiently infiltrated in the legit world, I imagine it would be possible to implement a mechanic where a family has to actively monitor these \\\'relations\\\' with high ranked legit people and of course, donating a lot of money to them to keep their mouth shut is an essential part of the plan. This is a more pro-active approach, seeing as positions get taken over by others and those in turn would have to be bribed again. In other words, it is a full-time business to ensure that the family is infiltrated well enough to keep itself healthy and large. I imagine that the number of businesses in which the family is infiltrated would decide the amount of capos (caporegime and higher) a family can have.<br />\n<br />\n[b]Drug trading[/b]<br />\nDrug trading has a large potential to ensure a steady income of large sums of money for a crime family. It follows logically that it is in the family\\\'s best interests to coordinate the trafficking of drugs and assign familymembers the responsibility to keep the money from this source of income flowing in. In other words, the coordination of the trafficking of drugs should be like a full-time job to some members of the family, as much as keeping track of the infiltration is.<br />\n<br />\nJust when writing this a rather brilliant idea struck me (forgive my lack of modesty here). I was thinking, if routes for trafficking should be set up to ensure that flow of drugs coming in, then where do those drugs come from? Are we going to copy IJ and implement drug houses? I thought not. Instead of drug houses, I thought, would it not be more realistic and interesting to allow people to buy pieces of land (from the mayor perhaps, seeing as he sort of \\\'owns\\\' the city) and let them decide what to grow on it? They could choose to grow marijuana on it and this would allow gangsters to cut deals with landowners to get percentages of the harvest for a certain amount of money. This, in turn, would allow us to add things like natural disasters etc. that influence the harvest. I think a great deal of dynamics could be added this way.<br />\n<br />\nAnyway, I can hardly lift my arms after writing all this - I would like to hear your reactions ;)'),
(158,7,47,54,'2007-09-10 12:49:45','','Wow, amazing, Reading through it I really like alot of the ideas, I\\\'m not to for the non-linear ranking but sure just see how it goes, I guess I\\\'m just used to IJ that way. The land idea, love it! Nothing to add myself, I think you pretty much covered everything!'),
(159,7,47,41,'2007-09-11 04:05:33','','Alright, I\\\'m a little sleep deprived, but here I go, take it with a tablespoon of salt...<br />\n<br />\n[b]On Structure, and Unique Gangsters[/b]<br />\nI remember we covered the need for more ranks that \\\"stand out\\\" than \\\"cut out\\\" by experience. I can think of a few semi-unique ways to do this.<br />\n<br />\nThe first way is to add something we can call, \\\"Vices and Virtues\\\" (think [i]Total War[/i]). Now, when one becomes a gangster, his actions start becoming watched, kinda like our special karma. If he kills a lot of people he might gain the vice \\\"Murderer\\\", and have a nice little description to help describe this mobster (\\\"This man has killed many men, he is a master at death.\\\"). This makes a great way for mobsters to get really nice names for themselves, and they could also have potential for adding stat modifiers for that mobster. They can be easily hard-coded into the game without much effort.<br />\n<br />\nThe second way is offering the family leadership to hand out \\\"Titles\\\", special things like \\\"Protectorate to the Family\\\" - that make those specially picked mobsters really stand out as close friends to the family.<br />\n<br />\n[b]On Land Ownership[/b]<br />\nI love the idea, but on a side note, maybe it would make since to add some kind of a Farmer career down the road? Anyway, I think that the city leader should be able to sell the land, but thats a kink in the system by itself, it needs to have an opposite reaction to the [i]legitimate side[/i] so that the mayor is obliged to only sell so much before damaging another sector, for instance, because the land is not being used for legitimate cash crops, the citiy\\\'s economics takes damage.<br />\n<br />\n[b]Family Gangsters, and Street Gangsters[/b]<br />\nIt was mentioned that maybe it should be worth making a clear distinction from Family Gangsters and \\\"Sgars\\\" without families. This is a really good idea, currently the only thing I can think of is simply labeling them as \\\"Street {Rank}\\\", just to say that they don\\\'t have any real contacts.<br />\n<br />\n[b]Pacts and Alliances[/b]<br />\nI think it might be interesting to allow family leaders to form friendly relationships with other families. Even simple things for families to offer each other will do, just to show a basic measure of appreciation toward each other.<br />\n<br />\nIn the end, I can\\\'t say a whole lot right now about the crime system, simply because its a huge part of the game, being the opposite of the several legitimate careers.							'),
(160,7,48,1,'2007-09-11 18:22:00','','Hey Sean/Aaron,<br />\n<br />\nWhile making the auction house I encountered a little dillemma - make the auction house global or not? Basically, the plan is to not let every city have an auction house (otherwise it becomes too easy to buy and sell items), but do we restrict items that have been put up for sale in, for example, Amsterdam to only be purchasable in Amsterdam or should they be bought from other cities with an auction house as well?<br />\n<br />\nThe first option means that players really have to engage into community actions such as asking \\\'hey, can you check if this or that item is for sale in city X?\\\', but on the other hand it might also mean that the AH isn\\\'t used as much as I want it to be used. After all, per city there\\\'d be far less items for sale. If that happens, there is less of a player-influenced economy than I want to have.<br />\n<br />\nWhat are your opinions on this? I\\\'m nudging towards a global auction house, especially since we won\\\'t have many players (and thus not many items) in the beginning. I am designing the system so that it is easily changed into a local auction house should the need arise.<br />\n<br />\nThanks in advance for your feedback :)'),
(161,7,48,41,'2007-09-11 22:25:11','','Well, from where I\\\'m standing now, it would be nice to only allow certain items to be sold in certain places - so that you are required to go to another AH at some point or another, but like everything else in the game, the balancing act would be Olympic in deciding what items get sold where. As for making it global, it might be necessary to do so at the moment, but in time we can \\\'unique-it-up\\\' a bit.<br />\n<br />\nNow, I -think- the AH is going to be a success for player trading, I could imagine all trading being done through the AH, since it\\\'s a safe and reliable environment not to get stiffed, and sell your items in an [i]e-bay[/i] fashion, but I still don\\\'t think its unique enough by itself to stand out much more than an \\\"average tool\\\". I believe it needs character. Some random messages, anything to add ambiance to it.<br />\n<br />\nI\\\'ll post more on this subject a little later as the ideas hit me.<br />\nRegards, Aaron.														'),
(162,7,49,41,'2007-09-12 00:50:36','','After some corrections and a delayed paper, I\\\'m proud to bring you a political system. I don\\\'t have much output yet, but I do have enough to give you an idea of what I have in mind.<br />\n<br />\nA screen shot of some output can be found here...<br />\nhttp://glitchpwns.com/images/early.png<br />\n<br />\nNow the system I\\\'m using now, allows for [b]an infinite amount of government styles[/b], the only downfall is that you\\\'ll still end up hard-coding some \\\"special\\\" things every time we create a new government style, but thats optional, I plan on just adding basic city modifiers so you wont be required to hard code anything if you don\\\'t want to.<br />\n<br />\nI haven\\\'t made the SVN commits for it yet, so don\\\'t look for it on your local servers. Feel free to throw in some ideas and criticism. Regards, Aaron.'),
(163,7,49,1,'2007-09-12 01:02:00','','My first reply after seeing that screenshot is: \\\'Woah!\\\'<br />\n<br />\nUnfortunately, I have a high-tea due to some festivities in the family today so I probably won\\\'t be able to write the longer and better reply until this evening. Therefore, consider this post an official placeholder for something more constructive ;)'),
(164,7,49,54,'2007-09-12 15:30:10','','Wow awesome, would there be a possibility that once a leader gets a social goverment going [dictatorship] that his term was indefinate till he stepped down or was killed? That\\\'d be pretty cool! You could have the citizens opt to revolt aswell. Really amazing ideas, obviously the best thing to do by default would be democracy, but really interesting to see were you are going with this. '),
(165,7,48,54,'2007-09-12 15:32:01','','I say global until more players, otherwise its just going to be a pain in the ass to update as it grows! ;) Leave a loophole in it to be changed to city version easy though.. Though thinking of that.. :P Yeah nevermind. Global!'),
(166,7,50,1,'2007-09-12 15:40:38','','In the old crimesyndicate admin forums there was a little political career brainstorm. I\\\'ll repost my own ideas on it (as I wrote them down in that thread) so we can discuss what could still be viable and what not!<br />\n<br />\n[quote]<br />\nRight, so i\\\'ve been thinking about it a little further and thought to come up with some details to which you can either agree or not :P<br />\n<br />\nFirst of all, taxes:<br />\n<br />\nI thought taxes can simply be on a scale from 0-100%. This way the president has absolute freedom in deciding what the taxes will be. Taxes should however closely relate to another system which I will explain underneath.<br />\n<br />\nRegime-points:<br />\nI thought that we could implement something like regime points in order to let the game measure what kind of regime the president is running. The first thing I want regime points tied to is taxes. I thought of a direct scale, where taxes from 0% to 40% represent a democratic government (0-40 regimepoints), taxes from 41% to 80% represent a dicatatorial government (41-80 regimepoints) and taxes from 81% to 100% represent a communist government (everything belongs to the state).<br />\nDuring a presidency your decisions will then take away regime points (move to the democratic side) or add regime points (move to the communist side) which effectively allows a government to switch it\\\'s principles during a presidency. Also, if we let other decisions also play a role in the regime points, you can have very high taxes, but still be democratic because of other decisions. (my father pays 52% taxes for example, but you wouldn\\\'t call the netherlands a dictature).<br />\n<br />\nParties and elections:<br />\n<br />\nA party must be formed by 5 men or more. If you don\\\'t have 5 people or more the party will disband in 12 hours. One man invites other people to join in their party, and a name must then be given to the party. This name can\\\'t change anymore, because people will vote on it.<br />\nOnce a party is formed it must create a program and select a presidency-candidate (based on the party program the regime-type will be decided). This way a party must have a politician that is ranked high enough to run for president. Otherwise you simply can\\\'t announce your program to the public, and thus not join in the elections.<br />\n<br />\nElections:<br />\n<br />\nA government should rule for 14 days (2 weeks), and the last two days are marked by elections. The \\\'elections\\\' or \\\'votes\\\' table in the database could have these fields:<br />\n| candidate | party | program_id | location | #votes |<br />\nprogram_id may need a clarification. Basically it is the id of the template of the party\\\'s program, which is saved into an other table. This id can be used to make a \\\'display this party\\\'s program\\\' page for the voters (just something technical).<br />\n<br />\nFreedom of Speech:<br />\n<br />\nFreedom of speech should not be automatically there. It should be a choice of the president. I imagine we could have a \\\'report\\\' link showing up next to every bar post + personal message if the president chooses to limit the freedom of speech. This report button would then create a police case, and depending on how limited the president has made the freedom of speech, you will either get a trial or be chucked in jail instantly.<br />\n<br />\nFreedom of Press:<br />\n<br />\nWe should have the players run the newspapers as something they can do next to their career. The president however, can choose to limit the freedom of press, which could either be just censure (before the newspaper-redaction gets to publish it, it must have been accepted by the president) or the president fires the newspaper staff and lets his own government officials make it (propaganda).<br />\n<br />\nFair trials or not:<br />\n<br />\nAgain, depending on the choices of the president, players will get a fair trial if they have done something wrong, or not. In the last case the president can order the police to instantly lock you up (if the president has selected this, a new police mission will be available to the police, in order to meet these orders from the president). The president can however also select to do things the democratic way and let an independent judge decide...<br />\n<br />\n<br />\nWell, these are a few of my thoughts. As you can see they\\\'re a little more detailed, so just post what you think of them here ;)<br />\n[/quote]'),
(167,7,50,41,'2007-09-12 21:34:08','','While developing the government system I thought about nearly every one of those points. And I\\\'d like to suggest some small refinements.<br />\n<br />\n[b]Regime Points and Taxes[/b]<br />\nI think 0-100% taxes are perfectly fine, but I think the \\\'Regime Points\\\' should be completely replaced by \\\'Government Templates\\\' all together, instead of trying to guess what type of government the city leader is trying to run. I think its much more detailed and realistic to allow him to choose his position, left wing or right, and then that would reflect what life is like in the city. If you remember that screen shot in the [i]Early Politics[/i] thread, you\\\'ll notice the detail right away (the Socialist [or Socialist Party] \\\'Government Template\\\', featured Socialist concepts like Industrialization and Collectivization - whereas the Democracy [or a Democratic Party] \\\'Government Template\\\' would feature concepts similar to Free Markets and Capitalism). <br />\n<br />\nThats my argument for it, and its only a suggestion :P. As for the rest of the \\\'old brainstorm\\\', I\\\'m pretty much in agreement with it. Tomorrow I\\\'ll try and post more on the subject...<br />\n<br />\nRegards,<br />\nAaron													'),
(168,7,50,1,'2007-09-13 01:46:32','','That sounds very viable - as I said, not everything in the original post holds truth anymore. However, since you\\\'re doing government templates I think it\\\'d make sense to limit the range in which you can set taxes as well. This is to prevent people from having 100% taxes with a democratic government type.'),
(169,7,51,1,'2007-09-14 04:05:47','','I\\\'m just posting to say I might be gone this weekend. This is due to festivities around the happening of my grandparents being married for 50 years. I\\\'m not sure if there\\\'ll be wireless internet where I\\\'m going, but even if there is, I might not be online as often as I am now. <br />\n<br />\nSee you later,<br />\n<br />\nRoger'),
(170,7,51,41,'2007-09-14 04:27:29','','Oh well, I have another project to do on RentACoder right now so I\\\'m not going to be making many commits for the next couple days myself. Looks like its down to one developer for a while. Have fun on the trip.<br />\n<br />\nRegards,<br />\nAaron'),
(171,2,52,52,'2007-09-15 17:58:39','','I can\\\'t make new char, when Phase killed me.'),
(172,2,52,41,'2007-09-15 22:48:04','','Yea, its broken. The character table seems to be updated regularly so its a bit of a pain to keep updating the character registration process. I\\\'ve got things to do now but I might bring you back to life later, just leave the name of your character for me and I\\\'ll see what I can do.'),
(173,2,52,48,'2007-09-16 03:57:13','','It was EchO'),
(174,7,51,1,'2007-09-16 15:20:36','','Well I\\\'m back. Turned out there wasn\\\'t wireless internet so that was a bit of a downer. Alltogether it could\\\'ve been worse and I\\\'m glad you guys didn\\\'t do too much on RR so I don\\\'t feel like I\\\'m not doing enough :P'),
(175,2,52,1,'2007-09-16 16:26:13','','Aaron is, unfortunately, right. The problem is: this game is continuously under development and therefore the database tables that are affected by the character creation process are often updated. This causes the nasty side-effect of other code (like that of the character creation process) becoming bugged since it still tries to work with the old version.<br />\n<br />\nI\\\'ll look into the char. creation process one of these days, so hopefully it\\\'ll be fixed soon enough. '),
(176,6,53,48,'2007-09-16 17:11:31','','You sneaked up on EchO from behind and put a hand over his mouth. You then quickly searched for his money and left the scene with a remarkably heavier wallet! You stole $448277!<br />\n<br />\nOwned? Think you should fix that ^^.'),
(177,6,54,48,'2007-09-16 17:13:10','','This maybe stupid or a thing that you thought you added, but you can\\\'t actually subscribe to a course, like Law, Science, Economics. There is no option. I\\\'ll decide to post everything i\\\'ve found and not think it\\\'s not there they know, but a bug maybe :o'),
(178,6,55,48,'2007-09-16 17:14:30','','I didn\\\'t log on for like 1 week... And i couldn\\\'t earn or do a petty crime it wouldn\\\'t let me. It sometimes does this when i login, says i already done one, can\\\'t do one soon enough? Maybe a bug in the code, or just a natural thing :P'),
(179,6,56,48,'2007-09-16 17:16:30','','Say i put this into quote space<br />\n<br />\n3 Whacks<br />\n206 Defense<br />\nI is a tank.<br />\n<br />\n<br />\nit will come out like this<br />\n<br />\n3 Whacks 206 Defense I is a tank.'),
(180,6,57,48,'2007-09-16 17:18:28','','hmm EchO is rich even though he is dead. has like 1k clean on hand, no money in bank, and like 100k dirty money.<br />\n<br />\nI have over 430k in the bank, 10k on hand, 600k dirty and i\\\'m Wealthy<br />\n<br />\nCould be a bug >_>'),
(181,2,58,48,'2007-09-16 17:20:07','','Maybe a play character icon or something when your on the forums so you don\\\'t have to click Account then Play Character :P I\\\'m just lazy, call it a luxury as Roger would say.'),
(182,6,59,48,'2007-09-16 17:25:05','','I bought a Vehicle, but doesn\\\'t show up in Vehicle: on the profile when playing. And it won\\\'t let me buy another one.'),
(183,2,60,48,'2007-09-16 17:29:54','','I reckon there should be like a journal for when you try whack someone. So say i whacked Roger for 20, it should be recorded in like a Attack Journal. So you can see all your aggs, successful aggs exc. That would be very cool.							<br />\n<br />\nTopic Name should be Agg Keeping sorry						'),
(184,6,61,48,'2007-09-16 17:31:19','','Goes to a white screen when i try to go to the GBH screen =['),
(185,6,53,1,'2007-09-16 18:49:50','','Fixed!'),
(186,2,52,1,'2007-09-16 18:51:05','','Update: I added a delete character button which only works if your character is dead. The creation process should work fine as well, so you shouldn\\\'t have trouble setting up a new character...'),
(187,6,56,41,'2007-09-16 19:33:11','','Fixed, added breaking line support for it in the profile and account page.<br />\n<br />\n[b]Developers[/b]: When displaying quote run a stripslashes and str_replace on \\\\n [replace with <br />] - otherwise it comes out all [i]mysql\\\'ish[/i].							'),
(188,6,59,41,'2007-09-16 19:36:32','','Short Answer: Not finnished.<br />\nLong Answer: I think before I stopped working on the old version I was working on vehicles but never finished it. You probably have a vehicle, you just can\\\'t see it or anything about it. I should get back around to finishing it sometime.'),
(189,6,61,41,'2007-09-16 21:55:33','','Not done yet, shouldn\\\'t be too long before it is.'),
(190,2,58,41,'2007-09-16 22:07:48','','Luxury added to SVN updates. Later I assume Roger will update it to the public [this] version :). It\\\'ll display \\\"Play\\\" next to \\\"Account\\\" in the menubar if your not currently in the game.'),
(191,7,50,41,'2007-09-16 23:06:01','','[b]SVN Commit[/b]<br />\nGovernment Templates added. Its all pretty much for show-n-tell though, because it rates about a 0 on the functionality scale. To add a new government, I suggest you look at the \\\'Anarchy\\\' or \\\'Socialist\\\' template in the database, and work from there as examples (located in the `governments` table). To apply a government to a city, check out the `localgov` table. Cities without entries in `localgov` are considered an \\\'Anarchy\\\'.<br />\n<br />\nDBDump Note: In the `governments` table, the ID 0 is sensitive, it must be set to the Anarchy template.<br />\n<br />\n[b]On Tax Limits[/b]<br />\nThought I should mention that I of course agree with setting tax limits, unfortunatly were still a ways away from a having a governors mansion, a.k.a a city manager :P<br />\n<br />\nRegards,<br />\nAaron'),
(192,6,62,48,'2007-09-17 13:17:33','','Please keep in mind that you can only delete dead characters!<br />\n<br />\nDoesn\\\'t work on EchO\\\'s account. And he is dead, because i killed him.'),
(193,6,63,48,'2007-09-17 13:19:08','','Still doesn\\\'t work, just tried it!! The line breaks if you didn\\\'t know.'),
(194,2,52,48,'2007-09-17 13:20:02','','It doesn\\\'t work :P'),
(195,6,63,1,'2007-09-17 14:05:25','','As Aaron said, he fixed it but I haven\\\'t uploaded the changes yet. It should start working sooner or later, when I update the account page...'),
(196,6,63,48,'2007-09-17 15:56:37','','That sounds good ^^'),
(197,6,62,1,'2007-09-18 03:46:26','','As far as I can verify, deleting a dead character should work now. I tested it on a dummy account. If there are still problems, just post them in this thread ;)'),
(198,6,62,48,'2007-09-18 14:01:40','','Works!, Nice job.'),
(199,6,64,48,'2007-09-19 08:48:06','','I\\\'m not sure if it\\\'s just me, but when i go on on the PC at school and log red-republic the template is all fucked and just screwed'),
(200,6,64,1,'2007-09-19 09:41:21','','Yeah, and that\\\'s probably internet explorer 6.0. I\\\'m sorry, but screw that browser :P I try to follow standards when coding this website, but unfortunately microsoft thinks differently about it. Fortunately, IE7 does support the W3C standards better, resulting in a better display of this site ;)'),
(201,6,64,48,'2007-09-20 04:11:39','','Ah, okay that\\\'s cool and yeh it is, damn people never fucking update do they :@'),
(202,6,65,52,'2007-09-20 10:45:37','','You have been brutally attacked on 20/09/2007 - 10:43:51. You are lucky to still find yourself in the land of the living! You have suffered 20 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!<br />\n<br />\nPhase shot at me and hit me for 20; but my HP hasn\\\'t gone down. Tested it 4 times now!<br />\n<br />\nIt\\\'s Phase by the way :P'),
(203,6,65,52,'2007-09-20 16:17:27','','You have been brutally attacked on 20/09/2007 - 16:15:49. You are lucky to still find yourself in the land of the living! You have suffered 20 damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!<br />\n<br />\nAnd again'),
(204,6,65,1,'2007-09-20 16:20:08','','That\\\'s probably going to happen until I have announced to have fixed the issue in this thread ;)<br />\n<br />\nEdit: That announcement has arrived sooner than I thought. The issue should be fixed now.'),
(205,6,65,52,'2007-09-21 03:20:15','','Fixed.'),
(206,6,66,14,'2007-09-21 20:43:47','','Well i listed something for sale on the auctions and when it sold i got i think it was 7 events saying that it was sold....'),
(207,6,66,1,'2007-09-22 15:31:17','','Yes, that\\\'s right, it took me a few hours to fix the issue that caused the finished auctions to not disappear from the database. For any new auctions, there shouldn\\\'t be problems anymore though ;)'),
(208,7,67,1,'2007-09-26 11:59:31','','Hey Sean/Aaron,<br />\n<br />\nSeeing as I\\\'m going to work on the land-owning next, I have been thinking a little about how we can let the weather influence the crops that people can grow on their land.<br />\nAt first, I simply thought I\\\'d go and implement a simple weather system (you know, insert weather types into the database and let a cronjob randomly choose from it every day), but I thought that would be unrealistic and limited in the long run. My mind trailed away from the weather problem and for a change I decided to solve a sudoku on the sudoku program I made for my java course. I hit the \\\'download sudoku\\\' button and selected the desired difficulty. And then it hit me!<br />\n<br />\nFor the download feature in the sudoku program I simply downloaded the sudoku page from a sudoku site that showed random sudoku\\\'s on every refresh. Downloading the sudoku was simply a matter of parsing the field-data from the HTML and put it in my own sudoku program. So I figured, why not do the same for this weather system? There\\\'ll still be a cronscript that runs every X hours, only the data won\\\'t randomly be chosen from a table in the database, it will come from a site that shows the current weather situation.<br />\n<br />\nI\\\'ve already found such a site, where the HTML is also readable enough to actually parse information from it. Its address is http://weather.weatherbug.com and you can simply request the weather for a city by modifying the hyperlink. For example, to get extended data for Amsterdam, the url would look like this: http://weather.weatherbug.com/NETHERLANDS/Amsterdam-weather/local-observations.html?zcode=z5602. I think that over the course of a few days I will simply add a new \\\'weatherlink\\\' field to the table that contains our current in-game locations, in which we\\\'ll put the link to the weather for that city. That is the only manual action involved in this for each city we add: look up the address of the weather information for it.<br />\n<br />\nI think this is a really interesting feature to add because it makes the game a little more unpredictable. Also, we can keep a table of air-pressure and the related weather type and then, if there\\\'s a hurricane in a city (very low air-pressure), we can actually have the effects of that propagated into the game (as in, damaged appartments and crops etc.).<br />\nI don\\\'t think I need to convince any of you, but I thought i\\\'d share it with you anyway, especially because you will probably be able to come up with more consequences and features that are suddenly enabled by adding realtime weather :)<br />\n<br />\nRegards,<br />\nRoger														'),
(209,1,68,1,'2007-09-27 08:03:40','','Hey everyone,<br />\n<br />\nI thought I\\\'d post another (in my eyes rather exciting) update. Partly to let you know what\\\'s going on, and partly to let you know that there\\\'s something going on at all!<br />\n<br />\nWhile thinking about how to shape the gangster career we figured there would be no such thing as drughouses in Red Republic. This is because we don\\\'t want to copy from Injustice too much, but also because drughouses are a bit too unrealistic. Instead, we figured, we need to build something that resembles the drug smuggling in real life and also works in terms of game mechanics. That\\\'s why, for the production of drugs, we came up with the following:<br />\n<br />\n[b]Land owning[/b]<br />\nSome people will probably still remember the \\\'Real estate agency\\\' in Crime Syndicate where you could buy appartments. We plan on re-introducing this agency, but we will let it sell more than just houses in Red Republic. Basically, the real estate agency is going to sell \\\'property\\\', which means it\\\'ll also sell land. Players who buy land can use that land to build a house on, but they will probably also be able to use their land to grow crops. You\\\'re probably seeing the link with drug production already: you can produce the raw materials needed for drugs on your land. This means that gangsters will have to cut deals with foreign land-owners (or there\\\'ll be no drugsmuggling) to get their drugs. We felt this is more realistic and it is also achievable in terms of gameplay.<br />\n<br />\n[b]Weather[/b]<br />\nSince we established that players can buy land and grow crops on their land, we figured we would also need something to keep the system dynamic and interesting. Weather can provide those dynamics. Periods of serious drought will influence the growth of crops, and thus the drug trade throughout the world in Red Republic.<br />\nWhile thinking about how to implement a weather system for Red Republic we soon figured a few weathertypes and a random choice between them would not suffice. Therefore, I have been looking into retrieving real-world, real-time weather data and I\\\'m very excited to say that I succeeded! In fact, I have already got a working script that pulls weather data for all cities in Red Republic from a reliable source! Thus, weather in Red Republic\\\'s cities will be synchronized to the actual weather in those cities. What other weather-based mechanics we will introduce is still up for discussion, but I thought I\\\'d already share this with you!<br />\n<br />\nRegards,<br />\n<br />\nRoger'),
(210,7,67,41,'2007-09-27 15:04:50','','Its kind of funny because last night I was thinking about adding a weather system. I don\\\'t have to explain it but this sounds a lot better. <br />\n<br />\nBut adding to this, I think it would be interesting to add a day-night system onto it too. It could be possible to have different times based on location, but I think a standard \\\'game time\\\' might be less confusing.<br />\n<br />\nRegards,<br />\nAaron A.							'),
(211,7,67,1,'2007-09-27 16:05:45','','I have been thinking about that too, especially since the data on the site i\\\'m pulling weather info from also includes sunrise/sunset data. I think we will eventually have to switch to using Greenwich Main Time (GMT) as our game time, but have a time display in each city as well that shows the local time. Then, day and night states are checked against the sunset/sunrise info for that city. <br />\n<br />\nThis means that in RR it wouldn\\\'t be the same time everywhere and that it can be night at some places and day at other places. I think that can be fun, especially if we add some stuff that requires a certain time of day. If you can only do something at daytime and other stuff only at night-time this will introduce another thing people will have to consider when planning stuff. It basically allows for more tactics and strategies...<br />\n<br />\nI think that, when I update the locations table, most of what I have in mind should become clear ;)');

-- Table structure for table `forums_topics`
DROP TABLE IF EXISTS `forums_topics`;

CREATE TABLE `forums_topics` (
  `id` bigint(20) NOT NULL auto_increment,
  `f_id` bigint(20) NOT NULL default '0',
  `poster_id` bigint(20) NOT NULL default '0',
  `topic_type` int(11) NOT NULL default '0',
  `topic_closed` tinyint(4) NOT NULL default '0',
  `name` varchar(75) collate latin1_general_ci NOT NULL default '',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `num_views` bigint(20) NOT NULL default '0',
  `last_activity` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `forums_topics`
insert into `forums_topics` values
(1,1,1,1,0,'Forum Guidelines','2007-02-11 17:06:17',71,1171231600),
(2,2,1,1,0,'Forum Guidelines','2007-02-11 17:09:46',29,1171231809),
(3,3,1,1,0,'Forum Guidelines','2007-02-11 17:13:01',80,1171798030),
(4,1,1,1,0,'Welcome','2007-02-13 05:07:09',109,1188268113),
(5,5,1,1,0,'Forum Guidelines','2007-02-13 06:04:19',27,1171364682),
(6,2,1,0,0,'Item System','2007-02-13 07:07:49',37,1171785713),
(7,1,1,0,0,'Updates to forums and accounts','2007-02-15 09:57:28',29,1171551471),
(8,2,17,0,0,'What I want','2007-02-15 21:08:23',32,1171616137),
(11,2,1,0,0,'Character Attributes','2007-02-16 17:14:53',27,1171785907),
(12,2,1,0,0,'Deployment Agency?','2007-02-17 16:33:00',35,1171785647),
(13,2,20,0,0,'Whacking and GBH','2007-02-17 16:33:25',68,1188267476),
(14,2,20,0,0,'Careers','2007-02-17 16:42:46',131,1172737692),
(15,2,20,0,0,'Police Department','2007-02-17 16:53:40',36,1172063769),
(16,2,20,0,0,'Business Career','2007-02-17 17:09:39',27,1171785452),
(22,5,3,0,0,'genral','2007-03-01 03:40:06',68,1188267802),
(18,1,1,0,0,'Graphics and imagery','2007-02-18 06:49:48',151,1188266861),
(19,3,1,0,0,'Dynamic storyline','2007-02-18 08:47:20',88,1188250525),
(20,2,17,0,0,'My Business Carrer Plan','2007-02-18 11:54:39',17,1171817702),
(21,2,1,0,0,'Navigation image style','2007-02-21 07:58:25',50,1172738589),
(23,1,1,0,0,'User List','2007-03-02 12:24:40',62,1188267832),
(24,6,18,0,0,'Typo\\\'s','2007-08-27 15:43:23',36,1188755026),
(25,6,1,1,0,'Forum Guidelines','2007-08-27 15:43:30',6,1188243833),
(26,2,18,0,0,'Donations','2007-08-27 15:52:37',9,1188244380),
(27,2,18,0,0,'Favicon','2007-08-27 15:53:40',11,1188244502),
(28,2,18,0,0,'Clothing Images','2007-08-27 16:01:49',11,1188267143),
(29,6,18,0,0,'Earn bug','2007-08-27 16:27:50',17,1188248501),
(30,6,18,0,0,'Emoticons','2007-08-27 16:29:23',9,1188247778),
(31,2,18,0,0,'Earn','2007-08-27 16:34:33',30,1188299375),
(32,2,18,0,0,'Capital Letters','2007-08-27 16:38:36',8,1188247660),
(33,2,18,0,0,'Log out','2007-08-27 16:42:19',9,1188267187),
(34,2,18,0,0,'ReWording','2007-08-27 17:08:15',8,1188249518),
(35,6,48,0,0,'Spelling Mistake','2007-08-27 22:25:55',11,1188295592),
(36,2,48,0,0,'Some Donating Options','2007-08-27 22:27:43',11,1188296463),
(37,6,14,0,0,'Bank \\\"Bug\\\"','2007-08-28 06:21:28',16,1188297046),
(38,2,48,0,0,'Bank','2007-08-28 07:21:44',12,1188301019),
(39,1,17,0,0,'LOL','2007-08-30 23:12:56',33,1188575672),
(40,6,48,0,0,'Whacking','2007-09-01 01:39:08',20,1188753232),
(41,6,48,0,0,'Item Equipping','2007-09-01 02:32:10',14,1188710366),
(42,2,48,0,0,'Quote/Background','2007-09-01 02:33:31',26,1189306692),
(43,2,48,0,0,'Suicide','2007-09-01 02:35:30',17,1188686697),
(44,1,1,0,0,'Latest Additions (02/09/2007)','2007-09-02 15:08:10',15,1188762131),
(45,6,48,0,0,'Whacking','2007-09-04 11:10:09',12,1188921974),
(46,7,1,0,0,'Welcome developers','2007-09-06 15:43:52',13,1189108330),
(47,7,1,0,0,'Crime Career Brainstorm','2007-09-07 17:35:59',34,1189497956),
(48,7,1,0,0,'Concerning the auction house','2007-09-11 18:22:00',11,1189625544),
(49,7,41,0,0,'Early Politics','2007-09-12 00:50:36',20,1189625433),
(50,7,1,0,0,'Political Career Repost','2007-09-12 15:40:38',20,1189998384),
(51,7,1,0,0,'Somewhat gone this weekend','2007-09-14 04:05:47',14,1189970459),
(52,2,52,0,0,'Making new char','2007-09-15 17:58:39',31,1190049625),
(53,6,48,0,0,'Mugging Dead People','2007-09-16 17:11:31',8,1189983013),
(54,6,48,0,0,'Universitys','2007-09-16 17:13:10',5,1189977213),
(55,6,48,0,0,'Earn Bug/Petty Crime bug','2007-09-16 17:14:30',5,1189977293),
(56,6,48,0,0,'Character Quote','2007-09-16 17:16:30',9,1189985614),
(57,6,48,0,0,'Wealth Status','2007-09-16 17:18:28',6,1189977531),
(58,2,48,0,0,'Shortcut Play Character','2007-09-16 17:20:07',13,1189994891),
(59,6,48,0,0,'Vehicle','2007-09-16 17:25:05',10,1189985815),
(60,2,48,0,0,'Whacking','2007-09-16 17:29:54',8,1189978217),
(61,6,48,0,0,'GBH\\\'ing','2007-09-16 17:31:19',7,1189994156),
(62,6,48,0,0,'Delete Character','2007-09-17 13:17:33',12,1190138523),
(63,6,48,0,0,'Edit Quote','2007-09-17 13:19:08',12,1190059020),
(64,6,48,0,0,'Internet Explorer','2007-09-19 08:48:06',13,1190275922),
(65,6,52,0,0,'Whacking','2007-09-20 10:45:37',17,1190359238),
(66,6,14,0,0,'Auctions','2007-09-21 20:43:47',16,1190489500),
(67,7,1,0,0,'Realtime weather','2007-09-26 11:59:31',21,1190923568),
(68,1,1,0,0,'Update: Land owning and weather','2007-09-27 08:03:40',17,1190894643);

-- Table structure for table `forums_users`
DROP TABLE IF EXISTS `forums_users`;

CREATE TABLE `forums_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `account_id` bigint(20) NOT NULL default '0',
  `num_posts` int(11) NOT NULL default '0',
  `avatar` varchar(255) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `forums_users`
insert into `forums_users` values
(1,1,69,'http://www.fighterops.com/forum/image.php?u=3058'),
(2,2,1,''),
(3,3,6,''),
(8,8,0,''),
(9,9,0,''),
(10,10,1,'http://tn3-1.deviantart.com/fs10/300W/i/2006/088/e/0/Bubbels_by_amethyste.jpg'),
(11,11,2,''),
(12,12,0,''),
(13,13,2,''),
(14,14,13,'http://i18.tinypic.com/2ekomkj.gif'),
(17,17,7,'http://s177.photobucket.com/albums/w222/Scott894/th_untitled2.jpg'),
(16,16,1,'C:\\Documents and Settings\\Shaun\\Desktop\\recent dl\\azwraith.jpg'),
(18,18,19,''),
(19,19,0,''),
(20,20,9,'http://www.imagehosting.com/out.php/i232812_tommy.gif'),
(21,21,4,'http://img165.imageshack.us/img165/1852/flavaflavcopykk4.jpg'),
(22,22,5,''),
(35,35,0,''),
(34,34,0,''),
(33,33,0,''),
(32,32,0,''),
(44,44,0,'http://tn1-5.deviantart.com/fs9/150/i/2006/038/e/d/Gun_to_the_head_by_TigarUK.png'),
(36,36,4,'http://www.fadeeva.com/Beasts/kitten.jpg'),
(37,37,0,''),
(38,38,0,''),
(39,39,0,''),
(40,40,0,''),
(41,41,13,'http://glitchpwns.com/images/soviet.jpg'),
(42,42,0,''),
(43,43,0,''),
(45,45,0,''),
(46,46,0,''),
(47,47,0,''),
(48,48,42,'http://i9.tinypic.com/4p4shnb.gif'),
(49,49,0,''),
(50,50,0,''),
(51,51,0,''),
(52,52,4,''),
(53,53,0,''),
(54,54,7,''),
(55,55,0,'');

-- Table structure for table `frontpage_comments`
DROP TABLE IF EXISTS `frontpage_comments`;

CREATE TABLE `frontpage_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `news_id` bigint(20) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `author` varchar(50) NOT NULL default '',
  `email` varchar(75) NOT NULL default '',
  `ip_address` varchar(15) NOT NULL default '',
  `comment` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `frontpage_comments`
insert into `frontpage_comments` values
(2,1,'2007-09-06 18:23:00','Roger','rogierpennink@gmail.com','86.82.154.168','Welcome Sneo! I\'ll be glad to finish this game with you!'),
(3,16,'2007-09-25 20:45:53','Aaron','etc@glitchpwns.com','4.252.193.176','I never really noticed, but its almost a full cms.');

-- Table structure for table `frontpage_news`
DROP TABLE IF EXISTS `frontpage_news`;

CREATE TABLE `frontpage_news` (
  `id` bigint(20) NOT NULL auto_increment,
  `news_type` int(11) NOT NULL default '0',
  `news_subject` varchar(255) collate latin1_general_ci NOT NULL default '',
  `news_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `news_author` varchar(50) collate latin1_general_ci NOT NULL default '',
  `news_message` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `frontpage_news`
insert into `frontpage_news` values
(1,0,'Red Republic Revamp','2007-02-04 15:28:43','Roger','As you will all have noticed, this new site design for Red Republic has everything to do with an incoming revamp of the game. Apart from the many bugs in the old version of Red Republic (Crime Syndicate) it has become clear that said old version is simply not up for the task of hosting many players in a safe way. The code of the old version is not managable enough and doesn\'t have the flexibility to allow for quick changes and/or bugfixes. \r\n\r\nThe first thing I will do is to write new forums for Red Republic from where the people who are still enjoying the game can discuss it\'s future in version 2.0. If there are any more updates, they will show up on this page - at least until there is a forum.\r\n\r\nRegards, Roger'),
(2,0,'New site design up','2007-02-04 16:13:54','Roger','After some hard work on the new site design I decided to put the new design up as default page. Please note that the new design comes with a new database, new files, and is basically completely different from the old game. It is therefore no longer possible to play the game via www.crimesyndicate.biz, but instead I must ask you to visit <a href=\\\"http://www.red-republic.com/crimesyndicate/\\\">http://www.red-republic.com/crimesyndicate/</a> in order to play the old game.\r\n\r\nAs stated before I will be writing the forums next so we can discuss the future of Red Republic. In the meantime, you can just play Crime Syndicate on the above-mentioned link, but new bugs might have been introduced because of the move (including promotions not working properly). If this is the case, alert me on IRC or send an e-mail (support@red-republic.com) and I will try to sort things out.\r\n\r\nRegards, Roger'),
(3,0,'Development forums up and running!','2007-02-13 05:27:53','Roger','I would just like to confirm that the forums on which I\'ve been working so hard have now opened to be used for a discussion on the future of Red Republic. If you have received the e-mail I sent out to all previous Crime Syndicate users I welcome you happily, knowing that still more people than I thought care(d) about Crime Syndicate. With this in mind I would also like to point your attention to the #red-republic channel on irc.jaundies.com which has been kept intact. If you are serious about helping us out with suggestions etc. you may find it interesting to join us on IRC.<br /><br />A few practical matters deserve attention as well. As told in the e-mail the forums are up, as well as the registration page, but there is no account panel or anything yet. I will definately work on that next, so you can change your avatars and other options. Nonetheless, there might be confusion about the login-process. It is very simple at the moment. You simply have to use the quick login box or the login page to login. If you get a message that you have logged in successfully you can start posting on the forums. Alternatively, when not logged in and attempting to post in the forums you will be asked for your account details. If these are correct you are also logged in.\r\n\r\nRegards,\r\n\r\nRoger'),
(13,0,'Development Update','2007-03-10 08:19:48','Roger','Since the last news item is about the forums being ready for use it is time to provide you with some updates about the current situation. You may have gotten the false impression that things are going slowly or that not much has happened, but the opposite is true.<br />\r\nFirst of all, I think it is appropriate to mention that Aaron (formerly known as Glitch to many of you) has decided to help out again, and it is due to his hard work that the administration panel is really taking shape now. While this is not something most players will benefit from, it is certainly a necessity for the game itself.<br />\r\n<br />\r\nFurthermore we\'ve been having a talk about what features we should be adding and some interesting ideas have been evaluated. In the coming period we will be focussed on adding those features of which we hope that they will add a unique touch to Red Republic. <br />\r\nNot only have we been producing \'ideas\' however. Since the last update earns were added, many of the tables required for making careers etc. have been added, and the communications system is working as well now. <br />\r\nSo much for what has been done, of course there is a planning as well. Basically we\'ve agreed to work on the crime career next so that\'s where most of the improvements will be seen from now on. That\'s it for now, goodbye!'),
(14,0,'New Developer - Oh thats me!','2007-09-06 18:19:31','Sneo','Hello all,<br /><br />\nWell I am now helping out on Red Republic, so development should go twice as fast, depending on *stares at cloud for about five minutes* yes... Anyway, I\'d just like to let you all know I\'m here, and stuff is getting done. For instance at the moment I am making these wonderful things we seem to be lacking called Profiles! Imagine that.. Some of you know me from Injustice, \"Hello\" to those of you who don\'t. But yes, hopefully the game will move along faster now! Got any bugs, you know where the forums is.  <br /><br />\n<br /><br />\nThanks for reading, but I gotta go back to coding now! Speak to you soon, hopefully!'),
(16,0,'Frontpage Revealed','2007-09-25 11:55:28','Roger','I have decided to pull away the message that\'s been populating the frontpage of Red Republic the past month and show the site as it\'s going to be to everyone else. Most people I know have been visiting the real site for a while now so it didn\'t seem necessary to put up with a temporary frontpage when the actual site was already there.<br />\n<br />\nPlease take note of the fact that this does not mean I\'m opening the game or anything - registration is still disabled. I just think it\'s no longer necessary to keep people away from the site - and those interested, whether they are new or have been with us from the beginning, can keep up with the development process by reading the forums and announcement on the news page.<br />\n<br />\nRegards,<br />\nRoger'),
(17,0,'Development Updates','2007-09-27 08:20:15','Roger','<img src=\"http://www.red-republic.com/images/miscellaneous/lightning.png\" align=\"right\" style=\"margin-left: 7px; margin-bottom: 20px;\" />We have introduced a new development update in the Development Announcement forums regarding the creation of a landowner system where people can buy land to build/grow stuff on.<br />\nClosely related to the announcement of this system is the announced creation of a weather system that will influence crops and can be potentially harmful to property.<br />\n<br />\nIf you want to find out more about these announcements and/or provide feedback on these systems you can view the entire thread at this address: <a href=\"http://www.red-republic.com/forums/viewtopic.php?id=68\">http://www.red-republic.com/forums/viewtopic.php?id=68</a>. Any feedback is appreciated. If you don\'t have an account you can post your feedback here as a comment.');

-- Table structure for table `governments`
DROP TABLE IF EXISTS `governments`;

CREATE TABLE `governments` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(225) collate latin1_general_ci NOT NULL default '',
  `formal` varchar(225) collate latin1_general_ci NOT NULL default '',
  `description` text collate latin1_general_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `governments`
insert into `governments` values
(0,'Anarchy','Anarchy','A city without a leader is in anarchy. Because there is no central leadership, it allows a wonderful environment for crime to prosper, while the city\'s services decline.'),
(1,'Socialism','Socialist State','In a socialist state, the productiveness of the city increases, city services are better off, and citizens gain easier employment. Citizen rights are decreased though, and citizens may not own property.');

-- Table structure for table `governments_research`
DROP TABLE IF EXISTS `governments_research`;

CREATE TABLE `governments_research` (
  `id` bigint(20) NOT NULL auto_increment,
  `gov_id` bigint(20) NOT NULL,
  `icon_url` varchar(225) collate latin1_general_ci NOT NULL,
  `name` varchar(225) collate latin1_general_ci NOT NULL,
  `text` text collate latin1_general_ci NOT NULL,
  `work_mod` int(11) NOT NULL,
  `sales_mod` int(11) NOT NULL,
  `budget_mod` int(11) NOT NULL,
  `services_mod` int(11) NOT NULL,
  `income_mod` int(11) NOT NULL,
  `crime_mod` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `governments_research`
insert into `governments_research` values
(2,1,'proletariat.png','Proletariat Rights','This increases the productivity of the working class in the city and has an effect on the government\'s budget as they are the ones billed for the new worker luxuries.',-60,0,-5,0,0,0);

-- Table structure for table `icons`
DROP TABLE IF EXISTS `icons`;

CREATE TABLE `icons` (
  `icon_id` bigint(20) NOT NULL auto_increment,
  `url` varchar(255) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`icon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `icons`
insert into `icons` values
(0,'items/unknown.png'),
(3,'items/digital_camera_1.png'),
(4,'items/cell_phone_1.png'),
(5,'items/gas_mask_1.png'),
(6,'items/playstation_portable_1.png'),
(7,'items/boots_1.png'),
(8,'items/cap_1.png'),
(9,'items/boots_2.png'),
(10,'items/cell_phone_2.png'),
(11,'items/gameboy_advance_1.png'),
(12,'items/xbox_360_1.png'),
(13,'items/playstation_portable_2.png'),
(15,'items/sweater_1.png'),
(16,'items/box_of_cigars_1.png'),
(17,'items/pants_1.png'),
(18,'items/pants_2.png'),
(19,'items/warning.png'),
(20,'items/gun_1.png'),
(21,'items/knife_1.png'),
(24,'items/cap_2.png'),
(26,'items/bag_1.png'),
(27,'items/bag_2.png'),
(28,'items/bag_3.png'),
(29,'items/bag_4.png'),
(30,'items/ps_3.png'),
(31,'items/bag_5.png'),
(32,'items/shirt_1.png'),
(33,'items/colt_1.png'),
(34,'items/cap_3.png'),
(35,'items/shirt_2.png'),
(36,'items/shirt_3.png'),
(37,'items/shirt_4.png'),
(38,'items/trousers_1.png'),
(39,'items/trousers_2.png'),
(40,'items/trousers_3.png'),
(41,'items/trousers_4.png'),
(42,'items/shirt_5.png'),
(43,'items/shirt_6.png'),
(44,'items/shirt_7.png'),
(45,'items/shirt_8.png'),
(46,'items/boots_3.png'),
(47,'items/boots_4.png'),
(48,'items/boots_5.png'),
(49,'items/boots_6.png');

-- Table structure for table `items`
DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `item_id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `icon` bigint(255) NOT NULL default '0',
  `worth` bigint(20) NOT NULL default '0',
  `quality` int(11) NOT NULL default '0',
  `category` int(11) NOT NULL default '0',
  `equipable` tinyint(1) NOT NULL default '0',
  `tier` int(11) NOT NULL default '0',
  `tradable` tinyint(1) NOT NULL default '0',
  `bagslots` int(11) NOT NULL default '0',
  `armor` int(11) NOT NULL default '0',
  `min_dmg` bigint(20) NOT NULL default '0',
  `max_dmg` bigint(20) NOT NULL default '0',
  `speed` bigint(20) NOT NULL default '0',
  `strength` int(11) NOT NULL default '0',
  `defense` int(11) NOT NULL default '0',
  `intellect` int(11) NOT NULL default '0',
  `cunning` int(11) NOT NULL default '0',
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `items`
insert into `items` values
(1,'Box of Cigars',16,99,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(2,'Digital Camera',3,199,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(3,'Motorola Cell Phone',4,279,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(4,'Samsung Cell Phone',4,217,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(5,'Nokia Cell Phone',10,178,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(6,'Gameboy Advance',11,150,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(7,'Playstation Portable',13,299,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(8,'Playstation 3',30,389,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(9,'XBox 360',12,359,0,0,0,0,1,0,0,0,0,0,0,0,0,0),
(10,'Small Plastic Bag',31,4150,0,8,1,1,1,4,0,0,0,0,0,0,0,0),
(32,'Colourful Plastic Bag',29,4250,0,8,1,1,1,4,0,0,0,0,0,0,0,0),
(12,'Linen Bag',27,6200,0,8,1,2,1,5,0,0,0,0,0,0,0,0),
(13,'Painted Linen Bag',28,6250,0,8,1,2,1,5,0,0,0,0,0,0,0,0),
(14,'Ragged Blue Cap',8,3298,0,2,1,0,1,0,23,0,0,0,0,0,0,0),
(15,'Ragged Blue Shirt',32,3799,0,3,1,0,1,0,27,0,0,0,0,0,0,0),
(16,'Ragged Blue Pants',17,3699,0,4,1,0,1,0,26,0,0,0,0,0,0,0),
(17,'Ragged Blue Shoes',7,3389,0,5,1,0,1,0,24,0,0,0,0,0,0,0),
(19,'Tramp\'s Bag',26,1899,0,8,1,0,1,3,3,0,0,0,0,0,0,0),
(20,'Filthy Old Hat',24,3367,0,2,1,0,1,0,24,0,0,0,0,0,0,0),
(21,'Filthy Old Sweater',15,3987,0,3,1,0,1,0,29,0,0,0,0,0,0,0),
(22,'Filthy Old Trousers',18,3786,0,4,1,0,1,0,27,0,0,0,0,0,0,0),
(23,'Filthy Old Boots',7,3456,0,5,1,0,1,0,26,0,0,0,0,0,0,0),
(28,'Rusty Practise Gun',20,6788,0,6,1,1,1,0,0,3,5,40,0,0,0,0),
(29,'Rusty Practise Knife',21,6399,0,7,1,1,1,0,0,2,3,24,0,0,0,0),
(30,'Ragged Savage Gun',33,11599,1,6,1,1,1,0,0,5,8,50,1,0,0,0),
(31,'Brushed Metal Gun',20,9677,0,6,1,2,1,0,0,4,8,40,0,0,0,0),
(33,'Curved Straw Hat',34,7559,1,2,1,0,1,0,38,0,0,0,1,0,0,2),
(34,'Stylish Red Shirt',35,9777,1,3,1,0,1,0,41,0,0,0,1,0,2,0),
(35,'Bright Pink Trousers',40,7599,0,4,1,1,1,0,46,0,0,0,0,0,0,0),
(36,'Bright Pink Shirt',42,9799,0,3,1,1,1,0,49,0,0,0,0,0,0,0),
(37,'Bright Pink Sandals',46,8779,0,5,1,1,1,0,46,0,0,0,0,0,0,0),
(38,'Navy Blue Flipflops',9,12899,1,5,1,1,1,0,78,0,0,0,0,0,3,2),
(0,'Unknown',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

-- Table structure for table `localcity`
DROP TABLE IF EXISTS `localcity`;

CREATE TABLE `localcity` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` bigint(20) NOT NULL default '0',
  `business_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='The table that links businesses and locations';

-- dumping data for table `localcity`
insert into `localcity` values
(1,1,1),
(2,1,2),
(11,1,14),
(8,1,11),
(12,1,15),
(13,1,16),
(14,1,17),
(16,1,18),
(17,1,19),
(18,1,20),
(28,2,30),
(27,2,29),
(26,2,28),
(25,2,27),
(24,2,26),
(29,2,31),
(30,2,32),
(31,2,33),
(32,2,34),
(33,3,35),
(35,3,37),
(36,3,38),
(37,3,39),
(38,3,40),
(39,3,41),
(40,3,42),
(41,1,43);

-- Table structure for table `localgov`
DROP TABLE IF EXISTS `localgov`;

CREATE TABLE `localgov` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` bigint(20) NOT NULL default '0',
  `government_id` bigint(20) NOT NULL default '0',
  `budget` bigint(20) NOT NULL default '0',
  `leader_id` bigint(20) NOT NULL default '0',
  `unemp_check_status` varchar(5) collate latin1_general_ci NOT NULL default '' COMMENT 'false',
  `unemp_check` bigint(20) NOT NULL default '0',
  `taxes` int(11) NOT NULL default '0',
  `notice` text collate latin1_general_ci NOT NULL,
  `freespeech_status` varchar(5) collate latin1_general_ci NOT NULL default '',
  `freepress_status` varchar(5) collate latin1_general_ci NOT NULL default '',
  `html_newspaper` text collate latin1_general_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `localgov`
insert into `localgov` values
(4,1,1,0,2,'true',5000,20,'Welcome new citizens to Amsterdam.','true','true',''),
(5,2,0,0,0,'',0,0,'','','','');

-- Table structure for table `localgov_parties`
DROP TABLE IF EXISTS `localgov_parties`;

CREATE TABLE `localgov_parties` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` bigint(20) NOT NULL,
  `government_id` bigint(20) NOT NULL,
  `leader_id` bigint(20) NOT NULL,
  `budget` bigint(20) NOT NULL,
  `tax_proposal` mediumint(9) NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  `party_member_1` bigint(20) NOT NULL default '0',
  `party_member_2` bigint(20) NOT NULL default '0',
  `party_member_3` bigint(20) NOT NULL default '0',
  `party_member_4` bigint(20) NOT NULL default '0',
  `party_member_5` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `localgov_research`
DROP TABLE IF EXISTS `localgov_research`;

CREATE TABLE `localgov_research` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `status` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `localgov_research`
insert into `localgov_research` values
(4,1,2,0);

-- Table structure for table `localgov_votes`
DROP TABLE IF EXISTS `localgov_votes`;

CREATE TABLE `localgov_votes` (
  `id` bigint(20) NOT NULL auto_increment,
  `party_id` bigint(20) NOT NULL,
  `char_id` bigint(20) NOT NULL,
  `location_id` bigint(20) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `locations`
DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `weather_url` varchar(255) collate latin1_general_ci NOT NULL default '',
  `min_temp` int(11) NOT NULL default '0',
  `max_temp` int(11) NOT NULL default '0',
  `avg_wind_dir` char(3) collate latin1_general_ci NOT NULL default '',
  `avg_wind_speed` int(11) NOT NULL default '0',
  `pressure` int(11) NOT NULL default '0',
  `dewpoint` int(11) NOT NULL default '0',
  `windchill` int(11) NOT NULL default '0',
  `humidity` int(11) NOT NULL default '0',
  `sunrise` time NOT NULL default '00:00:00',
  `sunset` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `locations`
insert into `locations` values
(1,'Amsterdam','/Netherlands/Amsterdam-weather/local-observations.html?zcode=z5602&units=1',11,16,'SW',20,1019,15,15,940,'06:46:00','18:12:00'),
(2,'Berlin','/Germany/Berlin-weather/local-observations.html?zcode=z5602&units=1',6,12,'ESE',11,1021,10,11,870,'06:11:00','17:37:00'),
(3,'Kingston','/Jamaica/Kingston-weather.html?zcode=z5602',25,26,'N',9,0,19,0,69,'06:22:00','05:30:00');

-- Table structure for table `personalities`
DROP TABLE IF EXISTS `personalities`;

CREATE TABLE `personalities` (
  `id` bigint(20) NOT NULL auto_increment,
  `slot_required` varchar(20) collate latin1_general_ci NOT NULL default '',
  `value_required` bigint(20) NOT NULL default '0',
  `secret` varchar(5) collate latin1_general_ci NOT NULL default 'false',
  `name` varchar(225) collate latin1_general_ci NOT NULL default '',
  `description` text collate latin1_general_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `personalities`
insert into `personalities` values
(9,'slot_a',60,'false','Severe Alcoholic','This person cannot go five minutes without a drink. Whiskey, Gin, Moonshine, they\'ll drink it all, in fact, when their around you should probably lock up your liquor cabinet. All those hours at the tavern have obviously had an influence on this.'),
(8,'slot_a',40,'false','Alcoholic','After hanging around the bar so long this person has become an alcoholic with a taste for liquor. It almost seems like they need to visit the tavern before and after work just to get through the day.'),
(7,'slot_a',25,'false','Social Drinker','This person has developed a reputation around the local Tavern, and getting known to be somewhat of a drinker. They tend to enjoy various wines and brews, preferring to drink around friends and loosen up.'),
(6,'slot_a',15,'true','Drinker','Lately this person has been enjoying the local Tavern a little more than usual. Its not yet known, but they are beginning to develop an alcoholic\'s vice.'),
(10,'slot_a',100,'false','Town Drunk','This person is one of the few fools who walk around the streets with a limp and slurred speech all day until the Tavern opens up. They have slept everywhere from trashcans to strange beds, not really knowing where or who they are, only waiting for that next drink.');

-- Table structure for table `persons`
DROP TABLE IF EXISTS `persons`;

CREATE TABLE `persons` (
  `id` bigint(20) NOT NULL auto_increment,
  `firstname` varchar(20) collate latin1_general_ci NOT NULL default '',
  `lastname` varchar(20) collate latin1_general_ci NOT NULL default '',
  `country_id` char(3) collate latin1_general_ci NOT NULL default '',
  `city` varchar(40) collate latin1_general_ci NOT NULL default '',
  `email` varchar(255) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `persons`
insert into `persons` values
(1,'Rogier','Pennink','NLD','Castricum','rogierpennink@gmail.com'),
(7,'Chris','Caunce','GBR','Preston','chriscaunce@gmail.com'),
(8,'gavin','lenuzza','AUS','brisbane','gavinlenuzza@hotmail.com'),
(16,'Alex','Garcia','NLD','DenHaag','Xerces@live.nl'),
(15,'Frederiek','Pennink','NLD','Castricum','freetjepeetje@hotmail.com'),
(14,'Ahmet','Seru','TUR','istanbul','tuluhanerdemi@yahoo.com'),
(13,'sean','keeley','DEU','fallingbostel','cheffysean@hotmail.com'),
(17,'Steve','Jacobs','USA','Blah','ownedcow@gmail.com'),
(18,'Jason','Perrigo','CAN','Gatineau','jason_perrigo@hotmail.com'),
(19,'Cody','Joyce','NZL','Timaru','avalze@gmail.com'),
(22,'Timothy','Bertsch','USA','Ramseur','Scott894@aol.com'),
(21,'shaun','robo','NZL','auckalnd','scrapyardrat@hotmail.com'),
(23,'Dean','Keeler','GBR','Durham','delinquency@btinternet.com'),
(24,'Mads','RasouliBaghban','DNK','Copenhagen','godlydevil@gmail.com'),
(25,'Matheus','Leitao','BRA','SaoPaulo','mef_usa@hotmail.com'),
(26,'Jeremiah','Barth','USA','Sylacauga','crudely.made.doomsday.device@gmail.com'),
(27,'Stew','Johnson','GBR','Preston','stewiejohnson@hotmail.co.uk'),
(45,'Sam','Hall','USA','Texas','cowboy_319@hotmail.com'),
(44,'Richard','Green','NZL','Auckland','Richard_green666@hotmail.com'),
(43,'Daniel','Lagerman','SWE','Stockholm','spjads@gmail.com'),
(42,'Dorrith','Pennink','NLD','Castricum','pennink.d@hccnet.nl'),
(41,'Vijay','Strickland','NZL','Auckland','v.strick@hotmail.com'),
(40,'Jones','Rusty','PHL','Cebu','jayrjun@yahoo.com'),
(39,'lee','hennigan','GBR','blackburn','leehenny69@hotmail.com'),
(38,'Nic','Mowday','NZL','Auckland','oh.wowza@gmail.com'),
(37,'Chris','Ballesteros','GBR','London','bugsy12@gmail.com'),
(46,'Aaron','Amann','USA','DuQuoin','glitch@glitchpwns.com'),
(47,'Raj','Lakhman','GBR','Peterborough','rajlakhman@hotmail.com'),
(48,'Ben','Pope','GBR','Preston','ben_pope@hotmail.co.uk'),
(49,'Noor','Pennink','NLD','Castricum','noor_pennink@hotmail.com'),
(50,'Sander','vanNoort','AFG','heiloo','sandervannoort@hotmail.com'),
(51,'Mark','Graham','AUS','Victoria','kingepix@gmail.com'),
(52,'Finn','Pennink','NLD','Wieringerwaard','finnpennink@xs4all.nl'),
(53,'Harry','Thorne','GBR','England','thenovadream@hotmail.com'),
(54,'Jack','JJWa','GBR','Lincolnshire','jackjjw@gmail.com'),
(55,'Joe','PWNS','AFG','Baghadad','subzero1199@googlemail.com'),
(56,'tom','stubbs','GBR','southmapton','tom_the_legend@hotmail.com'),
(57,'nate','nate','GBR','england','delicroy@googlemail.com'),
(58,'Rogier','Pennink','NLD','castricum','blah@blah.com'),
(59,'Sean','McNamara','IRL','Kilkenny','sean.sneo@gmail.com'),
(60,'Sean','McNamara','IRL','Kilkenny','s.a@seanmcn.com'),
(62,'Habaal','Gregov','1','Red Republic','ai@red-republic.com'),
(63,'Habaal','Gregov','1','Red Republic','ai@red-republic.com'),
(64,'Habaal','Gregov','1','Red Republic','ai@red-republic.com');

-- Table structure for table `playerguides`
DROP TABLE IF EXISTS `playerguides`;

CREATE TABLE `playerguides` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(225) collate latin1_general_ci NOT NULL default '',
  `content` text collate latin1_general_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `playerguides`
insert into `playerguides` values
(1,'The Basics','Red Republic is a pretty easy game to get the hang of once you have played for a few hours, but for all the new players I\'m going to walk you through the basics of how Red Republic works etc.\r\n\r\n\r\n??? Account Menu.\r\n\r\nThe account menu is a really easy and simple place to navagate, its primary purpose is to provide the user/player a kind of \"home base\" From here you can access your character so you can simply play the game, other things such as your characters information can be seen here e.g: Gender, rank , money and location.\r\n\r\nFrom this menu you can also change your default email and even your password, it also allows you to view your forum status, such as how many post you have made and change your avatar.\r\n\r\n??? Game Icons.\r\n\r\nSo your wondering what them pesky little icons up the top right corner are for? Well Ill explain. :)\r\n\r\nYet to be completed.\r\n\r\n\r\n\r\n??? Communications.\r\n\r\nCommunications are pretty self explanatory, from here you can send and receive messages to and from other players, reply, delete and save... There isn\'t really much more to say about it.\r\n\r\n\r\n??? Event Report.\r\n\r\nFrom here you can access all the events that have taken place around you.. Things that vary from bank transfers to crimes against you, witness statements, offers and even murder attempts on your life!\r\n\r\n\r\n??? Income.\r\n\r\nThe Income menu plays a HUGE roll in Red Republic, from this menu you can access many things, from such things as earning money at the local employment agency, to working at your current job and even committing crimes against other players! \r\n\r\n\r\n??? Local city menu.\r\n\r\nAnother key part of Red Republic is the city menu.. packed full of features and locations this is where just about anything and anything can be found!\r\n\r\nJust to name a few of the locations...\r\n\r\n? Local Tavern: This serves as some what of a In game forum.. players can chat here about most things game related.. Mainly used to relay rules about a city or even if players are looking to just have a good chat, this is the place to be.\r\n\r\n? Clothing Shop: The clothing shop is used for well.. buying clothing and other such items, you can purchase items such as Shoes, bags, pants and shirts... You can also sell these items back to the clothing shop if you feel the need of if you are low on cash!\r\n\r\n? The Local Bank: Well what is there to say? its a bank :P, from the bank menu you are able to open new accounts, there are different bank account types to pick from, such as; Checking, savings and investment. You can also deposit, withdraw and transfer money to other players! There is even a friendly help desk crew if you are having any trouble. :) \r\n\r\n? The Garage: Cars, parts, and the smell of gasoline... (or so Roger puts it..) This is the place to come if you are looking to buy a new vehicle or if you would just like to fix or even tune it up alittle if thats what you fancy. ;D\r\n\r\n? The University: So you think your pretty smart eh? think you can hack it at university? This is where you need to come if you want to advance yourself into a new career... From the university you can study many towards many different degrees! They vary from Law, medicine and economics!\r\n\r\n\r\n??? Travel.\r\n\r\nYet to be completed.\r\n\r\n\r\n??? Conflict.\r\n\r\nYet to be completed.');

-- Table structure for table `polls`
DROP TABLE IF EXISTS `polls`;

CREATE TABLE `polls` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` varchar(225) collate latin1_general_ci NOT NULL default '',
  `date` varchar(225) collate latin1_general_ci NOT NULL default '',
  `author` varchar(225) collate latin1_general_ci NOT NULL default '',
  `options` varchar(225) collate latin1_general_ci NOT NULL default '',
  `votes` bigint(20) NOT NULL default '0',
  `open` varchar(5) collate latin1_general_ci NOT NULL default '',
  `displayed` varchar(5) collate latin1_general_ci NOT NULL default '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `polls`
insert into `polls` values
(21,'What, in your opinion, should be top priority on the to-do list?','2007-08-31 09:23:05','Roger','Array',34,'false','false'),
(22,'What do you think of the direction the game is moving in so far?','2007-09-24 12:40:47','Roger','Array',13,'true','true');

-- Table structure for table `polls_options`
DROP TABLE IF EXISTS `polls_options`;

CREATE TABLE `polls_options` (
  `id` bigint(20) NOT NULL auto_increment,
  `parent` bigint(20) NOT NULL default '0',
  `option` varchar(225) collate latin1_general_ci NOT NULL default '',
  `votes` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `polls_options`
insert into `polls_options` values
(20,21,'\nAdding more shops',0),
(19,21,'Creation of the crime career',13),
(21,21,'\nAdding the police/law system',9),
(22,21,'\nAdding item-related content',0),
(23,21,'\nCreation of other careers',12),
(24,22,'I hate it, worst game ever',0),
(25,22,'\nIt really needs improvement',2),
(26,22,'\nI\'m starting to like it',1),
(27,22,'\nIt\'s brilliant!',10);

-- Table structure for table `polls_voters`
DROP TABLE IF EXISTS `polls_voters`;

CREATE TABLE `polls_voters` (
  `ip` varchar(225) collate latin1_general_ci NOT NULL default '',
  `poll` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `polls_voters`
insert into `polls_voters` values
('202.150.117.248',17),
('86.82.154.168',17),
('90.206.124.97',17),
('90.201.110.132',17),
('64.12.117.129',17),
('202.150.125.233',17),
('124.187.98.225',17),
('202.150.118.140',17),
('202.150.117.169',17),
('62.177.181.98',21),
('202.150.121.184',21),
('90.206.124.47',21),
('90.201.110.132',21),
('205.188.117.129',21),
('86.82.154.168',21),
('202.150.113.39',21),
('82.92.41.205',21),
('60.234.125.248',21),
('60.234.125.132',21),
('124.186.176.111',21),
('58.165.113.107',21),
('146.50.9.93',21),
('124.186.79.91',21),
('121.223.76.233',21),
('124.186.45.125',21),
('124.177.160.218',21),
('124.187.107.115',21),
('86.45.148.21',21),
('202.150.122.25',21),
('201.9.0.73',21),
('202.150.121.90',21),
('121.222.37.125',21),
('121.222.122.187',21),
('121.222.52.197',21),
('146.50.7.52',21),
('4.159.180.241',21),
('202.150.114.83',21),
('4.159.181.30',21),
('81.34.148.129',21),
('121.222.31.201',21),
('124.186.61.32',21),
('83.60.112.37',21),
('202.150.111.231',21),
('86.82.154.168',22),
('82.92.41.205',22),
('145.18.18.151',22),
('4.252.193.176',22),
('202.150.111.124',22),
('4.159.181.81',22),
('202.150.124.81',22),
('201.9.100.56',22),
('90.206.124.115',22),
('4.159.176.144',22),
('145.18.18.198',22),
('4.159.179.18',22),
('127.0.0.1',22);

-- Table structure for table `racetrack_applications`
DROP TABLE IF EXISTS `racetrack_applications`;

CREATE TABLE `racetrack_applications` (
  `id` bigint(20) NOT NULL auto_increment,
  `race_id` bigint(20) NOT NULL default '0',
  `driver_id` bigint(20) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `racetrack_bets`
DROP TABLE IF EXISTS `racetrack_bets`;

CREATE TABLE `racetrack_bets` (
  `char_id` bigint(20) NOT NULL default '0',
  `race_id` bigint(20) NOT NULL default '0',
  `driver_id` bigint(20) NOT NULL default '0',
  `betsize` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `racetrack_race`
DROP TABLE IF EXISTS `racetrack_race`;

CREATE TABLE `racetrack_race` (
  `race_id` bigint(20) NOT NULL auto_increment,
  `race_location` bigint(20) NOT NULL default '0',
  `race_done` varchar(5) collate latin1_general_ci NOT NULL default 'false',
  `driver_id_1` bigint(20) NOT NULL default '0',
  `driver_id_2` bigint(20) NOT NULL default '0',
  `driver_id_3` bigint(20) NOT NULL default '0',
  `bet_budget` bigint(20) NOT NULL default '0',
  `winner_budget` bigint(20) NOT NULL default '0',
  `owner_budget` bigint(20) NOT NULL default '0',
  KEY `race_id` (`race_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Holds race information';

-- dumping data for table `racetrack_race`
insert into `racetrack_race` values
(1,1,'false',0,0,0,0,0,0);

-- Table structure for table `realestate_agency`
DROP TABLE IF EXISTS `realestate_agency`;

CREATE TABLE `realestate_agency` (
  `business_id` bigint(20) NOT NULL,
  `price_per_meter` int(11) NOT NULL default '5000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `realestate_agency`
insert into `realestate_agency` values
(19,5000),
(41,5000);

-- Table structure for table `settings_general`
DROP TABLE IF EXISTS `settings_general`;

CREATE TABLE `settings_general` (
  `setting` varchar(255) collate latin1_general_ci NOT NULL default '',
  `value` varchar(255) collate latin1_general_ci NOT NULL default '',
  `formal` varchar(225) collate latin1_general_ci NOT NULL default 'Variable',
  `description` varchar(225) collate latin1_general_ci NOT NULL default 'No Description',
  `hidden` varchar(10) collate latin1_general_ci NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `settings_general`
insert into `settings_general` values
('timedisplay','d/m/Y H:i:s','Time - Full Date & Time Format','Full date & time format for use with the date() function.',''),
('datedisplay','d/m/Y','Time - Date Format','Date format for use with the date() function.',''),
('reg_status','closed','Registration Status','Open or close registrations',''),
('error_reporting','0','Error Reporting','Value for the error_reporting function in utility.inc.php',''),
('ircserver','irc.jaundies.com','IRC - Server Address','The address that points to our IRC server.',''),
('ircchannel','#red-republic','IRC - Channel','The channel where Red-Republic is found.',''),
('shortstrlen','64','Cutoff Length for Short-String','The maximum characters to allow before cutoff, used with the shortstr() function.',''),
('wikienabled','false','Wiki - Enabled','Is the Red Republic Wiki enabled?',''),
('wikilink','unknown/','Wiki - Location Address','The location of the wiki, should be above $rootdir.',''),
('global_ah','true','Global Auction House','True if items sold in one place should be buyable everywhere alse, false if not.','false'),
('deedchances','4','Max. Random for Deed Chance','Max random chance to gain a','false'),
('worthdeduct','2500','Used Vehicle Worth (Deductable)','How much money is taken off the actual worth of the vehicle after beeing purchased (used vehicles are worth less).','false'),
('weather_data_server','weather.weatherbug.com','Weather Data Server','The location to retrieve weather data from.','false'),
('autoupdate','false','MySQL Auto Update','Auto updates your local MySQL database when a new update is available.','false');

-- Table structure for table `settings_include`
DROP TABLE IF EXISTS `settings_include`;

CREATE TABLE `settings_include` (
  `filename` varchar(225) NOT NULL default '',
  `formal` varchar(225) NOT NULL default '',
  `description` varchar(225) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Table structure for table `settings_text`
DROP TABLE IF EXISTS `settings_text`;

CREATE TABLE `settings_text` (
  `setting` varchar(225) NOT NULL default '',
  `value` text NOT NULL,
  `formal` varchar(225) NOT NULL default 'Variable',
  `description` varchar(225) NOT NULL default 'No Description',
  `hidden` varchar(5) NOT NULL default 'false'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `settings_text`
insert into `settings_text` values
('debugbox','','Debug - Note Box','Contains the text from the note box under Quick Debug, ingame.','false'),
('frules','<b>Forum Rules</b><br />\r\nUnder construction.','Document - Forum Rules','Document for the Forum Rules.','false'),
('grules','<b>Game Rules</b><br />\r\nUnder construction.','Document - Game Rules','The document containing rules regarding the game.','false'),
('tos','<b>Terms of Service</b><br />\r\nUnder construction.','Document - Terms of Service','The Terms of Service for Red Republic.','false'),
('slotdoc','<b>Using and Preparing Unused Slots</b><br />\r\nSlots are fairly simple to understand, their basically categories. These categories stretch from cat. A through P (as of Sept. 25, 2007) in alphabetical order.<br /><br />\r\nNow, open up includes/deeddecl.php, and you can see right away that naming and finding an unused slot isn\'t hard at all. An unused slot will have a defined name like, <i>DEED_UNUSEDSLOT_X</i>, and an unused slot will have a formal defined name like, <i>Slot X</i>.<br /><br />\r\nTo create your own slot, first select an unused slot, and change <i>DEED_UNUSEDSLOT_X</i>, to something easier to remember. For instance, if this is for crime related vices and virtues, you might name it <i>DEED_CRIME</i>. Next thing to do is to give it a formal name, the define for it is on the same line as the one we just changed. All you need to do is change the name of your slot to something like \"Criminal Vices and Virtues\".<br /><br />\r\nAnd thats all there is to it.','Document - Using and Preparing Unused Slots','A tutorial explaining how to use and prepare unused deed slots, or categories.','false'),
('trigtut','<b>Deed Trigger Reference</b><br />\r\nTriggering a deed is simple, you only need to use one line of code...<br /><br />\r\n$char->addDeedPoint( DEED_MYDEED, 1, 6 );<br /><br />\r\nNow, the first parameter is obviously the defined slot to add to.<br />\r\nThe next parameter (1) stands for how many points to add on.<br />\r\nThe last one is optional, its only to override the default random chances (0, to default, or override value) set in the configuration.<br /><br />\r\nAnd thats your trigger, just place it wherever a character should be triggering it.','Document - Deed Trigger Reference','A document explaining how to add deed points at specific locations in your code.','false'),
('autosqlupdate','<b>Auto SQL Update Info</b><br />\r\nThe Auto SQL Update feature, allows developers who work on localhost to synchronize their databases with the latest dbdump.sql database dump.<br /><br />\r\nThe device works on the admin panel index, and starts by checking if the developer is working from his or her localhost and if the configuration allows Auto SQL Update. If so it then opens the current database dump (dbdump.sql) and checks to see if its the same as the last one updated (dbdumplast.sql), if their different that means the developer has downloaded a new dbdump from the SVN. After parsing and querying the new dump, it saves the copy to dbdumplast.sql, so that its concidered the old version and will not update until a change is made.','Document - Auto SQL Update Info','Introductory article on the RR Admin, Auto MySQL Update option.','false');

-- Table structure for table `taverns_forums`
DROP TABLE IF EXISTS `taverns_forums`;

CREATE TABLE `taverns_forums` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `taverns_forums`
insert into `taverns_forums` values
(1,1);

-- Table structure for table `taverns_messages`
DROP TABLE IF EXISTS `taverns_messages`;

CREATE TABLE `taverns_messages` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  `char_id` bigint(20) NOT NULL,
  `location_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `taverns_messages`
insert into `taverns_messages` values
(1,'2007-10-18 12:22:36','This is the local bar :).',15,1,0),
(4,'2007-10-18 00:00:00','Testing',15,1,0),
(5,'2007-10-18 00:00:00','I think its operational.',15,1,0),
(6,'2007-10-18 00:00:00','As the game is more developed, we can add more options here, and make this a sort of city comm center.',15,1,0),
(7,'2007-10-21 00:00:00','hey there?',2,1,0),
(8,'2007-10-21 00:00:00','Hmm, this seems to be very good',2,1,0),
(9,'2007-10-21 00:00:00','does it refresh?',2,1,0),
(10,'2007-10-21 00:00:00','yeah it does :(',2,1,0),
(11,'2007-10-31 00:00:00','purchases a beer from the counter.',15,1,1),
(12,'2007-10-31 00:00:00','Test',15,1,0),
(13,'2007-11-11 00:00:00','hello',2,1,0),
(14,'2007-11-11 00:00:00','Just testing of course... I wouldn\'t want to do anything insensitive...',2,1,0),
(15,'2007-11-11 00:00:00','ok, this is better... :)',2,1,0),
(16,'2007-11-11 00:00:00','well well well...',2,1,0),
(17,'2007-11-11 00:00:00','I see no text... :o',2,1,0),
(18,'2007-11-11 00:00:00','hello?',2,1,0),
(19,'2007-11-11 00:00:00','hello?',2,1,0),
(24,'2007-11-11 00:00:00','Well, finally it works :-)',2,1,0),
(21,'2007-11-11 00:00:00','whoa',2,1,0),
(22,'2007-11-11 00:00:00','whoa',2,1,0),
(23,'2007-11-11 00:00:00','hahaah',2,1,0),
(25,'2007-11-11 00:00:00','test',2,1,0),
(26,'2007-11-11 00:00:00','another test',2,1,0),
(27,'2007-11-11 00:00:00','test test test',2,1,0),
(28,'2007-11-11 00:00:00','test test test',2,1,0),
(29,'2007-11-11 00:00:00','lalalal',2,1,0),
(30,'2007-11-11 00:00:00','testerdetesterdetesterdetest!',2,1,0),
(31,'2007-11-11 00:00:00','testypesty',2,1,0),
(32,'2007-11-11 00:00:00','testypesty2',2,1,0),
(33,'2007-11-11 00:00:00','And 3 even :)',2,1,0),
(34,'2007-11-11 00:00:00','Ok',2,1,0),
(35,'2007-11-11 00:00:00','this simply works now ',2,1,0),
(36,'2007-11-11 00:00:00','and that is absolutely great :)',2,1,0),
(37,'2007-11-11 00:00:00','brilliant even',2,1,0),
(38,'2007-11-11 00:00:00','I would say...',2,1,0),
(39,'2007-11-14 00:00:00','purchases a beer from the counter.',2,1,1);

-- Table structure for table `taverns_replies`
DROP TABLE IF EXISTS `taverns_replies`;

CREATE TABLE `taverns_replies` (
  `id` bigint(20) NOT NULL auto_increment,
  `topic_id` bigint(20) NOT NULL,
  `char_id` bigint(20) NOT NULL,
  `location_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `taverns_replies`
insert into `taverns_replies` values
(1,1,2,1,'2007-11-10 12:57:32','Well well well... Isn\'t  this just very interesting???'),
(2,1,38,1,'2007-11-10 12:59:01','Yeah, I agree, it\'s rather cool!'),
(3,1,2,1,'2007-11-10 14:17:05','<3'),
(4,1,2,1,'2007-11-10 14:17:26','<3');

-- Table structure for table `taverns_topics`
DROP TABLE IF EXISTS `taverns_topics`;

CREATE TABLE `taverns_topics` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `notice_tag` varchar(5) collate latin1_general_ci NOT NULL default 'false',
  `subject` varchar(255) collate latin1_general_ci NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  `char_id` bigint(20) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `taverns_topics`
insert into `taverns_topics` values
(1,1,'2007-11-06 00:00:00','true','Just testing','This is a test subject :D',2),
(2,1,'2007-11-10 00:00:00','false','Another test subject','Haha, this is a test!',2),
(3,1,'2007-11-10 00:00:00','true','Another notice','This is a test notice!',2);

-- Table structure for table `travel_flights`
DROP TABLE IF EXISTS `travel_flights`;

CREATE TABLE `travel_flights` (
  `id` bigint(20) NOT NULL auto_increment,
  `to` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `flightstart` bigint(20) NOT NULL default '0',
  `flightend` bigint(20) NOT NULL default '0',
  `cost` int(11) NOT NULL,
  `tickets` bigint(20) NOT NULL default '100',
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `travel_flights`
insert into `travel_flights` values
(168,3,1,1196507493,2393015401,2504,20),
(169,2,1,1196506947,2393013446,1091,20),
(170,2,1,1196506778,2393013764,1114,20),
(171,3,1,1196507022,2393014087,2314,20),
(172,2,1,1196506567,2393013522,1385,20),
(173,3,1,1196508039,2393015213,2184,20),
(174,2,1,1196521854,2393043628,1133,20),
(175,2,1,1196521868,2393043876,1188,20),
(176,3,1,1196522583,2393045060,2996,20),
(177,3,1,1196522165,2393045067,2610,20),
(178,2,1,1196521927,2393043829,1132,20),
(179,2,1,1196521807,2393043683,1497,20),
(180,2,1,1196526377,2393052999,1008,20),
(181,3,1,1196526787,2393054291,2778,20),
(182,3,1,1196527506,2393054281,2890,20),
(183,2,1,1196526616,2393053232,1234,20),
(184,3,1,1196526661,2393053712,2568,20),
(185,3,1,1196527229,2393054263,2172,20),
(186,3,1,1196529454,2393059088,2278,20),
(187,3,1,1196528953,2393058387,2614,20),
(188,3,1,1196528867,2393058007,2658,20),
(189,2,1,1196528233,2393056446,1033,20),
(190,3,1,1196528791,2393057787,2856,20),
(191,3,1,1196529267,2393058293,2252,20);

-- Table structure for table `travel_flights_tickets`
DROP TABLE IF EXISTS `travel_flights_tickets`;

CREATE TABLE `travel_flights_tickets` (
  `id` bigint(20) NOT NULL auto_increment,
  `flight_id` bigint(20) NOT NULL,
  `char_id` bigint(20) NOT NULL,
  `cost` bigint(20) NOT NULL,
  `to` int(11) NOT NULL,
  `done` varchar(5) collate latin1_general_ci NOT NULL default 'false',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- Table structure for table `university_facts`
DROP TABLE IF EXISTS `university_facts`;

CREATE TABLE `university_facts` (
  `faculty_id` bigint(20) NOT NULL,
  `fact` text collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `university_facts`
insert into `university_facts` values
(2,'When engaged in surgery for a very long time, you may want to drink coffee as it keeps you awake!'),
(2,'In case of a fractured limb, make sure it doesn\'t move while stabilizing it!'),
(2,'The Oath of Hippocrates: Treat patients to the best of your ability and do not betray their secrets.'),
(2,'When a patient has apparently been involved in a shooting, or if your patient shows symptoms of having taken drugs, always alert the police!'),
(2,'A bleeding nose can be cured by pressing your fingers against it with your patient\'s head leaning forwards!'),
(2,'Never hesitate to examine your patients with any available means, it\'s better to loose money than lives!'),
(2,'Should your patient have been victim of a rape, the standard procedure is to test for seropositivity!'),
(2,'Cardiology is the study of the heart and blood veins. A cardiogram is a display of the heart\'s functioning state.'),
(2,'Myrmecology is the study of the human muscle system and is an important area of research for fysiotherapists!'),
(2,'In case of a highly contaminating disease, make sure the patients in question are isolated from everyone else. Furthermore, you should trace down anyone they might have been in close contact with!'),
(3,'The market demand curve for a product will not shift when there is a fall in the price of that product.'),
(3,'According to the Law of Supply the higher the price, the larger the quantity supplied.'),
(3,'Economic growth occurs when there is an increase in the economy\'s productive capacity.'),
(3,'Investment may be defined as spending which adds to the capital stock of a country.'),
(3,'At levels of output where the firm\'s short-run average cost curve is increasing, the marginal cost curve is above the short-run average cost curve.'),
(3,'Capital spent on innovation and new technology is not a variable cost in the short run.'),
(3,'When depositing funds into a bank account, you are basically lending money to the bank. Hence the interest.'),
(3,'A checque that doesn\'t have an official bank logo printed on it is most probably a fake checque.'),
(3,'When being employed by a bank and you notice unclaimed money coming in, always report this to the manager!'),
(3,'A currency conversion rate less than one is very beneficial to the export of your nation.');

-- Table structure for table `university_faculties`
DROP TABLE IF EXISTS `university_faculties`;

CREATE TABLE `university_faculties` (
  `business_id` bigint(20) NOT NULL,
  `faculty_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `university_faculties`
insert into `university_faculties` values
(11,1),
(11,2),
(11,3);

-- Table structure for table `university_faculty`
DROP TABLE IF EXISTS `university_faculty`;

CREATE TABLE `university_faculty` (
  `faculty_id` bigint(20) NOT NULL auto_increment,
  `abbreviation` char(3) collate latin1_general_ci NOT NULL,
  `study_name` varchar(255) collate latin1_general_ci NOT NULL,
  `full_name` varchar(255) collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `description2` text collate latin1_general_ci NOT NULL,
  `exp_per_study` int(11) NOT NULL,
  `exp_needed` int(11) NOT NULL,
  PRIMARY KEY  (`faculty_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `university_faculty`
insert into `university_faculty` values
(1,'law','Law','Faculty of Law','If upholding the law to protect citizens from shameless abuse and immoral practises would be the pinnacle of your life, then a degree in Law is just what you\'re looking for. The Faculty of Law seeks motivated and intelligent students who possess a large feeling for justice!','The Faculty of Law is a large building right in the center of $(CITY)$. You enter after climbing marble stairs and you marvel at the size and grandeur of the building. Certainly, this is where great lawyers are made!',13,250),
(2,'med','Medecine','Faculty of Science','Has it alwasy been a burning desire of yours to save other people\'s lives? Have you always been thrilled by medical series on the TV, ever wondering what it\'s really like? If so, a course in medecine is exactly the thing for you. You will learn exactly how the human body works, and more importantly, how to cure it from diseases and other discomfort. A golden career awaits you!','The Faculty of Science is an intimidating building in the outskirts of $(CITY)$. It immediately becomes apparent, upon entering the building, that great architects have designed this statue of science. You get the feeling that this is the place to be in scientific $(CITY)$!',12,300),
(3,'eco','Economics','Faculty of Economics','There can never be enough graduates in economics! You\'ve always liked to play with numbers, or perhaps the financial section of the newspaper has always intrigued you most. If any of this is the case, you may want to pursue a degree in ecomics! Any bank\'ll take you right away with this degree!','The Faculty of Economics of the University of $(CITY)$ is a rather large building with huge marble pillars. Everything about this building tells you that you\'ve arrived at the temple of $(CITY)$\'s financial district. You feel that this is where your future lies!',12,275);

-- Table structure for table `university_questions`
DROP TABLE IF EXISTS `university_questions`;

CREATE TABLE `university_questions` (
  `question_id` bigint(20) NOT NULL auto_increment,
  `faculty_id` bigint(20) NOT NULL,
  `question` varchar(255) collate latin1_general_ci NOT NULL,
  `answer_1` varchar(255) collate latin1_general_ci NOT NULL,
  `answer_2` varchar(255) collate latin1_general_ci NOT NULL,
  `answer_3` varchar(255) collate latin1_general_ci NOT NULL,
  `answer_4` varchar(255) collate latin1_general_ci NOT NULL,
  `correct_answer` tinyint(1) NOT NULL,
  PRIMARY KEY  (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `university_questions`
insert into `university_questions` values
(1,2,'In case of a fractured limb; what is most important to remember?','To stabilize it, and to make sure it doesn\'t move at all.','To keep it warm, due to cut-off bloodveins the limb may loose warmth','To try to bring the bone into place as fast as possible - or it may no longer heal correctly.','To not stand on it!',1),
(2,2,'What does the Oath of Hippocrates roughly say?','That you should never betray the trust of your patients.','That you should leave no means untouched to cure your patient and that you should never betray their trust.','That you should do everything to the fullest of your ability to cure your patient.','That, in cases of emergency, there should always be a safety protocol at hand.',2),
(3,2,'How would you go about curing a bleeding nose?','You let it bleed until it stops; this way the nose will stay clear of obstruction.','There is special medication to cure a bleeding nose. A small amount of nasopedophine should be enough.','Obstruct the nasal cavity to ensure the blood can no longer get out.','Press your fingers against the nose, while your patient sits down with the chin against their chest.',4),
(4,2,'What is the standard procedure when treating a patient that has obviously been raped?','Do not speak of it until psychiatry arives, they\'ll know better what to do.','Talk about it with your patient, they often appreciate your concern.','In all cases, you must test for seropositivity.','Keep your patient isolated from other patients, the shame is often too big to stand.',3),
(5,2,'When you are not sure about your patient\'s condition, what should you do?','Fire the patient; you don\'t want to risk distributing a wrong treatment.','Try random medication and see how they react, this\'ll often clarify their condition.','Fetch someone higher ranked than you, they will be able to cope with the responsibility.','Examine the patient with all available means, it is better to loose money than lives.',4),
(6,2,'What exactly, does the term \'cardiology\' refer to?','It is the study of the heart and blood-veins.','It is the study of the heart only.','It is the study of the muscle and nerve system.','It is the study of the brain and nerve system.',1),
(7,2,'What is your first concern in case of a contaminating disease?','To have the patient leave the hospital as quickly as possible.','To isolate the contaminated patients to stop the disease from spreading.','To vaccinate everyone else in the hospital against that disease before it spreads.','To cure the patient as quickly as possible.',2),
(8,2,'What exactly, does the term \'myrmecology\' refer to?','It is the study of the heart and blood-veins.','It is the study of the heart only.','It is the study of the muscle system.','It is the study of the brain and nerve system.',3),
(9,2,'What to do when you suspect your patient of criminal activities?','Inform the police. This patient may have done more harm to others than what he\'s being treated for.','Leave it the way it is. Fetching the police or blackmailing him will only get you into trouble','Refuse to treat the patient, let him leave the hospital immediately.','Confront the patient with your suspicions and blackmail him to get yourself a little bonus.',1),
(10,2,'When a surgery is taking very long, what can be helpful to remember?','Nothing in particular, just make sure you don\'t loose the patient.','Regularly drink coffee. Nothing keeps you more awake and more accurate than coffee.','That you have taken a long sleep before you engage in surgery - you can\'t afford to be sleepy.','To remain sterile, wash your hands every 30-40 minutes to avoid infections.',2),
(11,3,'When will the market demand for a product not shift?','When there is a rise in consumers real incomes.','When a successful advertising campaign is launched.','When there is an increase in the price for similar products.','When there is a fall in the price of said product.',4),
(12,3,'What exactly, does the Law of Supply state?','The higher the price, the lower the quantity supplied.','The higher the price, the larger the quantity supplied.','Anything that is supplied will be purchased by consumers.','If the price is low, any firm will sell the product.',2),
(13,3,'When does economic growth occur?','When there is an increase in the aggregate demand.','When there is an increase in wage rates.','When there is an increase in the economy\'s productive capacity.','When there is an increase in the inflation rate.',3),
(14,3,'How would you define investment?','As spending which adds to the capital stock of a country.','As that part of household income which is saved rather than spent.','As all types of spending by the government sector.','As spending on goods and services by consumers.',1),
(15,3,'What can you say about a firm that sees its short-run average cost curve increasing?','The firm has lost all control of its costs.','The marginal cost curve is falling.','The marginal cost curve is below the short-run average cost curve.','The marginal cost curve is above the short-run average cost curve.',4),
(16,3,'Which of the following is not a variable cost in the short run?','The labour costs of part time workers employed by the hour.','Capital spent on innovation and new technologies.','Energy costs.','Raw material costs.',2),
(17,3,'Explain why you receive interest over money on a bank account.','Interest is a marketing strategy for many banks which gains them customers.','When depositing funds, you invest in the bank. Interest is your part of the profits.','Depositing funds into a bank account equals lending money to the bank. Hence the interest.','Because bankers, contrary to the public opinion, really are nice guys.',3),
(18,3,'How can you recognise whether a checque is fake or not?','There are black fingerprints all over it.','It doesn\'t have an official logo of the bank printed on it.','Checques for amounts higher than a thousand dollars are fake by definition.','Checques? Who uses those in this age of technological marvels?',2),
(19,3,'In case of unclaimed money coming into a bank, what should you do as employee?','Report it to the manager immediately. Chances are something fishy is going on.','If the money is still unclaimed, you are allowed to claim it.','Unclaimed money doesn\'t exist.','Unclaimed money is a daily occurrence. Nothing you can\'t fix by yourself.',1),
(20,3,'What is a currency conversion rate below one beneficial to?','A country\'s military budget.','A country\'s spending on innovation.','A country\'s import.','A country\'s export.',4);

-- Table structure for table `vehicles`
DROP TABLE IF EXISTS `vehicles`;

CREATE TABLE `vehicles` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(225) NOT NULL default '',
  `speed` int(11) NOT NULL default '0',
  `item_slots` int(11) NOT NULL default '0',
  `drug_slots` int(11) NOT NULL default '0',
  `worth` bigint(20) NOT NULL default '0',
  `tier_required` int(11) NOT NULL default '0',
  `locations` varchar(225) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `vehicles`
insert into `vehicles` values
(1,'Lincoln Town Car',120,5,5,5000,0,'1:2'),
(2,'\'83 Station Waggon',2,7,3,5500,0,'1:2'),
(3,'Cargo Truck',1,15,15,25000,1,'1:2'),
(4,'HD Cargo Truck',0,20,20,30000,2,'1');

-- Table structure for table `vehicles_tuneups`
DROP TABLE IF EXISTS `vehicles_tuneups`;

CREATE TABLE `vehicles_tuneups` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(225) NOT NULL default '',
  `worth` bigint(20) NOT NULL default '0',
  `description` varchar(225) NOT NULL default '',
  `speed_increase` int(11) NOT NULL default '0',
  `drug_slot_increase` int(11) NOT NULL default '0',
  `item_slot_increase` int(11) NOT NULL default '0',
  `worth_increase` bigint(20) NOT NULL default '0',
  `tier_required` int(11) NOT NULL default '0',
  `locations` varchar(225) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- dumping data for table `vehicles_tuneups`
insert into `vehicles_tuneups` values
(1,'Alloy Wheels',1000,'Increases vehicle worth by $1500',0,0,0,1500,0,'1:2'),
(2,'Extended Bed',10000,'Adds an extra 5 drug & item slots, increases worth.',0,5,5,5000,0,'1:2'),
(3,'Nitrous Oxide x2',1250,'Reusable cannisters, increases vehicle speed for short duration.',2,0,0,0,0,'1'),
(4,'Nitrous Oxide x5',3500,'Reusable cannisters, increases vehicle speed for short duration.',5,0,0,0,1,'1'),
(5,'Nitrous Oxide x10',8999,'Reusable cannisters, increases vehicle speed for short duration.',10,0,0,0,2,'1'),
(6,'NOS-X10 Berlin Series Cannisters',15000,'Top of the line nitrous oxide cannisters, guaranteed to send your car flying off a cliff.',20,0,0,0,3,'2');

-- Table structure for table `weaponshop`
DROP TABLE IF EXISTS `weaponshop`;

CREATE TABLE `weaponshop` (
  `store_id` bigint(20) NOT NULL default '0',
  `business_id` bigint(20) NOT NULL default '0',
  `items` varchar(255) collate latin1_general_ci NOT NULL default '',
  `stock` varchar(255) collate latin1_general_ci NOT NULL default '',
  `reset_timer` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- dumping data for table `weaponshop`
insert into `weaponshop` values
(0,16,'29 28','7 3',1196496653);


UPDATE items SET item_id=0 WHERE name='Unknown';
UPDATE char_characters SET id=0 WHERE nickname='ADMINISTRATOR';
UPDATE icons SET icon_id=0 WHERE url='items/unknown.png';
UPDATE governments SET id=0 WHERE name='Anarchy';
