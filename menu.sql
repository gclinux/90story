
TRUNCATE TABLE `t_admin_menu`;
INSERT INTO `t_admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES
	(1, 2, 12, 'evn', 'fa-bar-chart', '/', NULL, NULL, '2019-11-13 18:24:23'),
	(2, 0, 11, 'Admin', 'fa-tasks', '', NULL, NULL, '2019-11-13 18:24:23'),
	(3, 2, 13, 'Users', 'fa-users', 'auth/users', NULL, NULL, '2019-11-13 18:24:23'),
	(4, 2, 14, 'Roles', 'fa-user', 'auth/roles', NULL, NULL, '2019-11-13 18:24:23'),
	(5, 2, 15, 'Permission', 'fa-ban', 'auth/permissions', NULL, NULL, '2019-11-13 18:24:23'),
	(6, 2, 16, 'Menu', 'fa-bars', 'auth/menu', NULL, NULL, '2019-11-13 18:24:23'),
	(7, 2, 17, 'Operation log', 'fa-history', 'auth/logs', NULL, NULL, '2019-11-13 18:24:23'),
	(8, 2, 18, 'Redis Manager', 'fa-database', 'redis', 'auth.management', '2019-09-02 05:44:07', '2019-11-13 18:24:23'),
	(9, 0, 1, '书库', 'fa-book', '#', NULL, '2019-09-02 08:04:09', '2019-11-13 18:26:01'),
	(10, 9, 2, '书库列表', 'fa-align-justify', 'books', NULL, '2019-09-02 08:05:18', '2019-11-13 18:26:01'),
	(11, 0, 8, '爬虫管理', 'fa-edge', '#', NULL, '2019-09-03 08:30:23', '2019-11-13 18:24:23'),
	(13, 11, 10, '代理列表', 'fa-american-sign-language-interpreting', 'proxy', NULL, '2019-09-03 08:32:11', '2019-11-13 18:24:23'),
	(14, 9, 3, '章节内容列表', 'fa-align-left', 'catalogs', NULL, '2019-09-21 05:55:03', '2019-11-13 18:26:01'),
	(15, 0, 4, '广告管理', 'fa-audio-description', 'axd', NULL, '2019-10-03 04:46:10', '2019-11-13 18:26:01'),
	(16, 11, 9, '书库来源', 'fa-bars', 'book-sources', NULL, '2019-10-25 11:19:05', '2019-11-13 18:24:23'),
	(17, 18, 6, '友情链接', 'fa-chain', 'links', NULL, '2019-11-06 16:48:47', '2019-11-13 18:26:01'),
	(18, 0, 5, 'SEO', 'fa-american-sign-language-interpreting', NULL, NULL, '2019-11-13 18:22:30', '2019-11-13 18:26:01'),
	(19, 18, 7, '搜索引擎访问记录', 'fa-bug', 'seo-logs', NULL, '2019-11-13 18:23:51', '2019-11-13 18:26:01');


INSERT INTO `t_book_sources` VALUES (2, 'biqugex.com.js', '新笔趣阁', 1, '2019-10-25 11:19:34', '2019-10-25 11:19:34');
