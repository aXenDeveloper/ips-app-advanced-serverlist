<?php

$lang = array(
    // Main Langs
    '__app_axenserverlist' => '(aXen) Advanced Server List',
    'module__axenserverlist_servers' => 'Servers',
    'frontnavigation_axenserverlist' => 'Servers',

    // Menu
    'menu__axenserverlist_servers' => 'Advanced Server List',
    'menu__axenserverlist_servers_settings' => 'Settings',
    'menu__axenserverlist_servers_servers' => 'Servers',
    'menu__axenserverlist_servers_mods' => 'Mods',
    'menu__axenserverlist_servers_servers_refresh' => 'Refresh data',

    // Permissions
    'r__servers' => 'Server list',
    'r__servers_manage' => 'Can manage servers?',

    // Settings in AdminCP
    // * General
    'axenserverlist_tab_general' => 'General',
    // Color filling of players
    'axenserverlist_header_colorFilling' => 'Color filling of players',
    'aXenServerList_settings_colors' => 'Enable color filling of players?',
    'aXenServerList_settings_colors_desc' => 'If disabled, it will get the color from <span style="font-weight: bold;">var(--theme-link_hover)</span>.',
    'aXenServerList_settings_colors_1_20' => '(1-20) Color filling of players',
    'aXenServerList_settings_colors_21_40' => '(21-40) Color filling of players',
    'aXenServerList_settings_colors_41_60' => '(41-60) Color filling of players',
    'aXenServerList_settings_colors_61_80' => '(61-80) Color filling of players',
    'aXenServerList_settings_colors_81_100' => '(81-100) Color filling of players',
    // Server query
    'axenserverlist_header_serverQuery' => 'Server query',
    'aXenServerList_settings_serverQuery_maxQuery' => 'Maximum number of queries',
    'aXenServerList_settings_serverQuery_maxQuery_desc' => 'When the server does not respond, the application will ask the server as many times as is set in the settings.<br><span style="color: red;">This value too high may affect the speed of the server your community is running on!</span>',
    // * Personalization
    'axenserverlist_tab_personalization' => 'Personalization',
    'aXenServerList_settings_fullWidth' => 'Enable table in 2 columns (full width)?',
    'aXenServerList_settings_fullWidth_desc' => '<p class="ipsMessage ipsMessage_warning">When this option is enabled, the widget area should be the full width of the page!</p>',
    'aXenServerList_settings_fullWidth_control' => 'Can users change view?',
    'aXenServerList_settings_fullWidth_control_desc' => 'Users will be can to change widget view.',
    'aXenServerList_settings_fullWidth_default' => 'Enable table in 2 columns (full width) by default?',
    'aXenServerList_settings_footer' => 'Enable footer?',
    'aXenServerList_settings_footer_desc' => 'Footer have statistic: Total servers, Total players, Filled servers.',
    "aXenServerList_settings_table_group" => "Enable server grouping?",
    "aXenServerList_settings_table_group_icon" => "Display icon mod",
    "aXenServerList_settings_table_group_icon_only_category" => "Only in Category",
    "aXenServerList_settings_table_group_icon_only_server" => "Only in Server",
    "aXenServerList_settings_table_group_icon_all" => "Category & Server",
    // Server query
    'axenserverlist_header_buttons' => 'Buttons',
    'aXenServerList_settings_buttons_vote' => 'Vote',
    'aXenServerList_settings_buttons_statistics' => 'Statistics',
    'aXenServerList_settings_buttons_tv' => 'TV',
    'aXenServerList_settings_buttons_forum' => 'Forum',
    // * Scroll
    'axenserverlist_tab_scroll' => 'Scroll',
    'aXenServerList_settings_scroll' => 'Enable scroll in server list?',
    'aXenServerList_settings_scroll_desc' => 'If the height exceeds the set value, a scroll will appear.',
    'aXenServerList_settings_scroll_height' => 'Height server list',
    'aXenServerList_settings_scroll_height_desc' => 'Value in pixels (px).',
    'aXenServerList_settings_scroll_default' => 'Enable scroll by default?',
    'aXenServerList_settings_scroll_control' => 'Can users control scrolling?',
    'aXenServerList_settings_scroll_control_desc' => 'Users will be can to turn the scroll on and off.',
    'aXenServerList_settings_scroll_mobile' => '(Mobile) Force a scroll?',
    'aXenServerList_settings_scroll_mobile_desc' => '<p class="ipsMessage ipsMessage_warning">When this option is enabled then changing the scroll by the user is not possible on the mobile!</p>',
    'aXenServerList_settings_scroll_mobile_value' => '(Mobile) Height server list',
    'aXenServerList_settings_scroll_mobile_value_desc' => 'Value in pixels (px).',

    // Servers Node in AdminCP
    // * Basic
    'aXenServerList_admin_table_servers_tab_basic' => 'Basic',
    'aXenServerList_admin_table_servers_name' => 'Custom name',
    "aXenServerList_admin_table_servers_mod_id" => "Mod",
    'aXenServerList_admin_table_servers_name_default' => 'Get name from the server and display?',
    'aXenServerList_admin_table_servers_name_default_desc' => 'If this option is enable then shows server name instead of custom name.',
    'aXenServerList_admin_table_servers_ip' => 'IP',
    'aXenServerList_admin_table_servers_ip_desc' => 'If your server is Discord then provide widget ID.',
    'aXenServerList_admin_table_servers_ip_custom' => 'Custom IP domian',
    'aXenServerList_admin_table_servers_ip_custom_desc' => 'For example: mc.yourwebsite.com',
    'aXenServerList_admin_table_servers_query_port' => 'Query port',
    'aXenServerList_admin_table_servers_custom_connect' => 'Enable your own link to connect to the server?',
    'aXenServerList_admin_table_servers_custom_connect_link' => 'Own link to connect to the server',
    'aXenServerList_admin_table_servers_custom_connect_link_desc' => '<span style="color: red;">There must be a URL link here that you can paste into your browser!</span>',
    // * Debug
    'aXenServerList_admin_table_servers_tab_debug' => 'Debug mode',
    'aXenServerList_admin_table_servers_debug' => 'Enable debug mode?',
    'aXenServerList_admin_table_servers_debug_text_YesNo' => 'Enable reason?',
    'aXenServerList_admin_table_servers_debug_text_YesNo_desc' => 'The reason will appear in the more tab for the given server and in the summary at the top of the table.',
    'aXenServerList_admin_table_servers_debug_text' => 'Reason',
    'aXenServerList_admin_table_servers_buttons_debug' => "Debug Server",
    // * URLs
    'aXenServerList_admin_table_servers_tab_urls' => 'URLs',
    'aXenServerList_admin_table_servers_url_statistics' => 'Statistics URL',
    'aXenServerList_admin_table_servers_url_tv' => 'TV URL',
    'aXenServerList_admin_table_servers_url_vote' => 'Vote URL',
    'aXenServerList_admin_table_servers_url_forum' => 'Forum URL',
    // * Advanced
    'aXenServerList_admin_table_servers_tab_advanced' => 'Advanced',
    'aXenServerList_admin_table_servers_owners' => 'Owners',
    'aXenServerList_admin_table_servers_new' => 'New server?',
    'aXenServerList_admin_table_servers_top_server' => 'Highlight the server?',
    'aXenServerList_admin_table_servers_top_server_desc' => 'Displays a trophy icon next to the server name.',
    'aXenServerList_admin_table_servers_top_server_text' => 'Text for highlight the server in tootlip',

    // Mods Node in AdminCP
    'aXenServerList_admin_table_mods_icon' => "Icon",
    'aXenServerList_admin_table_mods_icon_desc' => "Icon must have equal dimensions.<br />Recommended: <span style='font-weight: bold;'>24x24</span>px.",
    'aXenServerList_admin_table_mods_name' => "Name",
    'aXenServerList_admin_table_mods_protocol' => "Protocol",
    'aXenServerList_admin_table_mods_api' => "Custom API",
    'aXenServerList_admin_table_mods_api_url' => "Custom API Field<br />Address URL",
    'aXenServerList_admin_table_mods_api_status' => "Custom API Field<br />Status",
    'aXenServerList_admin_table_mods_api_current_players' => "Custom API Field<br />Current Players",
    'aXenServerList_admin_table_mods_api_max_players' => "Custom API Field<br />Max Players",
    'aXenServerList_admin_table_mods_api_name' => "Custom API Field<br />Name",
    'aXenServerList_admin_table_mods_api_password' => "Custom API Field<br />Password",
    'aXenServerList_admin_table_mods_api_map' => "Custom API Field<br />Map API",
    'aXenServerList_admin_table_mods_api_platform' => "Custom API Field<br />Platform",
    'aXenServerList_admin_table_mods_api_connect_link' => "Custom API Field<br />Connect Link",

    // Widgets
    // * Server List
    'block_aXenServerListWidget' => 'Server List',
    'block_aXenServerListWidget_desc' => 'Widget shows servers',
    'aXenServerList_widget_title' => 'Our servers',
    'aXenServerList_widget_more' => 'More',
    'aXenServerList_widget_connect' => 'Connect',
    'aXenServerList_widget_statistics' => 'Statistics',
    'aXenServerList_widget_vote' => 'Vote',
    'aXenServerList_widget_forum' => 'Forum',
    'aXenServerList_widget_tv' => 'TV',
    'aXenServerList_widget_new' => 'New',
    'aXenServerList_widget_toggle_hide' => 'Toggle this widget',
    'aXenServerList_widget_toggle_scroll' => 'Toggle scroll',
    'aXenServerList_widget_map' => 'Map',
    'aXenServerList_widget_owners' => 'Owners',
    'aXenServerList_widget_protocol' => 'Protocol',
    'aXenServerList_widget_password' => 'Password',
    'aXenServerList_widget_most_players' => 'Most players',
    'aXenServerList_widget_percent_players' => 'Percentage filling',
    'aXenServerList_widget_totalServers' => 'Total servers',
    'aXenServerList_widget_totalPlayers' => 'Total players',
    'aXenServerList_widget_totalPlayersPercent' => 'Filled servers',
    'aXenServerList_widget_clickToConnect' => 'Click connect button',
    'aXenServerList_widget_toggle_fullWidth' => 'Toggle view',
    'aXenServerList_widget_toggle_debug' => 'Show servers with Debug mode',

    // Others
    'aXenServerList_debug_icon' => 'Debug mode is enabled on this server',
    'aXenServerList_popup_refresh' => 'Servers data updated',
    'aXenServerList_popup_debug_add' => 'Added server log',
);
