<?php
defined('TYPO3_MODE') || die();

call_user_func(function() {
    $extensionKey = "typo3themeskeleton";

    // Typo3 extension manager gearwheel icon (ext_conf_template.txt)
    // Typo3 <= 8:
    // $_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey]);
    // $rtePresets = $_extConfig['rtePresets'];
    // TYPO3 9:
    $rtePresets = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    )->get($extensionKey, "rtePresets");

    // Register own rte ckeditor config
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['rte_theme'] = $rtePresets;

    // Add addRootLineFields for example slide in TypoScript
    $rootLineFields = &$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"];
    if (trim($rootLineFields) != "") $rootLineFields .= ',';
    $rootLineFields .= 'backend_layout';

    // register svg icons: identifier and filename
    $iconsPng = [
        // BackendLayouts
        'hh_one_column' => 'BackendIcons/header-1col.png',
        'hh_navaside_left' => 'BackendIcons/header-sidenav-left.png',

        // Gridelements
        'hh_grid_1_column' => 'gridelements/1_column.png',
        'hh_grid_2_column' => 'gridelements/2_column.png',
        'hh_grid_3_column' => 'gridelements/3_column.png',
        'hh_grid_4_column' => 'gridelements/4_column.png',
        'hh_grid_25-75_column' => 'gridelements/25-75.png',
        'hh_grid_33-66_column' => 'gridelements/33-66.png',
        'hh_grid_66-33_column' => 'gridelements/66-33.png',
        'hh_grid_75-25_column' => 'gridelements/75-25.png',
        'hh_grid_slider' => 'gridelements/slider.png',
        'hh_grid_tabs' => 'gridelements/tabs.png'
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );

    foreach ($iconsPng as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => "EXT:{$extensionKey}/Resources/Public/Images/{$path}"]
        );
    };

    // Add UserTS config as default for all BE users
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
        ### https://docs.typo3.org/typo3cms/TSconfigReference/UserTsconfig/Options.html
        options {
            clearCache.pages = 1
            folderTree.uploadFieldsInLinkBrowser = 3
            pageTree.showDomainNameWithTitle = 1
            enableBookmarks = 0
        }

        admPanel {
            enable {
                all = 0
                preview = 1
                cache = 0
                publish = 0
                edit = 0
                tsdebug = 0
                info = 0
            }

            override {
                edit {
                    displayFieldIcons = 0
                    displayIcons = 0
                }
            }

            hide = 0
        }

        ### Show pageUID in the pagetree
        [adminUser = 1]
            options {
                pageTree.showPageIdWithTitle = 1
                folderTree.uploadFieldsInLinkBrowser = 3
            }
        [GLOBAL]
    ');

    // Hooks

    // AJAX eID
    // $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['hhtheme'] = "EXT:{$extensionKey}/Classes/EidApi/index.php";
});
