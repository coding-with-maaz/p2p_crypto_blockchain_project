<?php

/*
 * ==========================================================
 * ADMINISTRATION PAGE
 * ==========================================================
 *
 */

header('X-Frame-Options: DENY');
define('BXC_CLOUD', file_exists(__DIR__ . '/cloud'));
$installation = false;
if (!BXC_CLOUD) {
    if (!file_exists(__DIR__ . '/config.php')) {
        $installation = true;
        $file = fopen(__DIR__ . '/config.php', 'w');
        fwrite($file, '<?php define("BXC_URL", "") ?>');
        fclose($file);
    }
    require(__DIR__ . '/functions.php');
    if (!defined('BXC_USER')) {
        $installation = true;
    }
}
if (BXC_CLOUD) {
    require(__DIR__ . '/functions.php');
    require(__DIR__ . '/cloud/functions.php');
    bxc_cloud_load();
    if (!defined('BXC_URL')) {
        define('BXC_URL', CLOUD_URL);
    }
}
$minify = $installation || isset($_GET['debug']) ? false : (BXC_CLOUD || bxc_settings_get('minify'));

function bxc_box_installation() { ?>
    <div class="bxc-main bxc-installation bxc-box">
        <form>
            <div class="bxc-info"></div>
            <div class="bxc-top">
                <img src="media/logo.svg" />
                <div class="bxc-title">
                    Installation
                </div>
                <div class="bxc-text">
                    Please complete the installation process.
                </div>
            </div>
            <div id="user" class="bxc-input">
                <span>
                    Username
                </span>
                <input type="text" required />
            </div>
            <div id="password" class="bxc-input">
                <span>
                    Password
                </span>
                <input type="password" required />
            </div>
            <div id="password-check" class="bxc-input">
                <span>
                    Repeat password
                </span>
                <input type="password" required />
            </div>
            <div id="db-name" class="bxc-input">
                <span>
                    Database name
                </span>
                <input type="text" required />
            </div>
            <div id="db-user" class="bxc-input">
                <span>
                    Database user
                </span>
                <input type="text" required />
            </div>
            <div id="db-password" class="bxc-input">
                <span>
                    Database password
                </span>
                <input type="password" />
            </div>
            <div id="db-host" class="bxc-input">
                <span>
                    Database host
                </span>
                <input type="text" placeholder="Default" />
            </div>
            <div id="db-port" class="bxc-input">
                <span>
                    Database port
                </span>
                <input type="number" placeholder="Default" />
            </div>
            <div class="bxc-bottom">
                <div id="bxc-submit-installation" class="bxc-btn">
                    Complete installation
                </div>
            </div>
        </form>
    </div>
<?php } ?>

<?php function bxc_box_login() {
    bxc_colors_admin();
    bxc_load_custom_js_css();
    ?>
    <div class="bxc-main bxc-login bxc-box">
        <form>
            <div class="bxc-info"></div>
            <div class="bxc-top">
                <img src="<?php echo bxc_settings_get('logo-admin') ? bxc_settings_get('logo-url', BXC_URL . 'media/logo.svg') : 'media/logo.svg' ?>" />
                <div class="bxc-title">
                    <?php echo bxc_('Sign into') ?>
                </div>
                <div class="bxc-text">
                    <?php echo bxc_('To continue to') . ' ' . bxc_settings_get('brand-name', 'Boxcoin') ?>
                </div>
            </div>
            <div id="username" class="bxc-input">
                <span>
                    <?php echo bxc_('Username') ?>
                </span>
                <input type="text" required />
            </div>
            <div id="password" class="bxc-input">
                <span>
                    <?php echo bxc_('Password') ?>
                </span>
                <input type="password" required />
            </div>
            <?php
            if (bxc_settings_get('two-fa-active')) {
                echo '<div id="two-fa" class="bxc-input"><span>' . bxc_('Verification code') . '</span><input type="password" required /></div>';
            }
            ?>
            <div class="bxc-bottom">
                <div id="bxc-submit-login" class="bxc-btn">
                    <?php echo bxc_('Sign in') ?>
                </div>
            </div>
        </form>
    </div>
<?php } ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <title>
        <?php echo BXC_CLOUD && !bxc_verify_admin() ? bxc_settings_get('brand-name', CLOUD_NAME) . ' | Login' : bxc_('Admin') . ' | ' . (BXC_CLOUD ? CLOUD_NAME : bxc_settings_get('brand-name', 'Boxcoin')); ?>
    </title>
    <script><?php echo bxc_settings_js_admin() ?></script>
    <script src="<?php echo BXC_URL . 'js/client' . ($minify ? '.min' : '') . '.js?v=' . BXC_VERSION ?>"></script>
    <script src="<?php echo BXC_URL . 'js/admin' . ($minify ? '.min' : '') . '.js?v=' . BXC_VERSION ?>"></script>
    <link rel="stylesheet" href="<?php echo BXC_URL . 'css/admin.css?v=' . BXC_VERSION ?>" media="all" />
    <?php
    if (defined('BXC_SHOP')) {
        echo '<script src="' . BXC_URL . 'apps/shop/shop.admin' . ($minify ? '.min' : '') . '.js?v=' . BXC_VERSION . '"></script>';
    }
    if (BXC_CLOUD) {
        bxc_cloud_admin();
    }
    ?>
    <link rel="shortcut icon" type="image/svg" href="<?php echo BXC_CLOUD ? CLOUD_ICON : (bxc_settings_get('logo-admin') ? bxc_settings_get('logo-icon-url', BXC_URL . 'media/icon.svg') : BXC_URL . 'media/icon.svg') ?>" />
</head>
<body>
    <?php
    require(__DIR__ . '/admin_code.php');
    if ($installation) {
        bxc_box_installation();
    } else if (bxc_verify_admin()) {
        bxc_box_admin();
    } else {
        if (BXC_CLOUD) {
            require(__DIR__ . '/cloud/registration.php');
            bxc_cloud_registration_login_box();
        } else {
            bxc_box_login();
        }
    }
    ?>
</body>
</html>
