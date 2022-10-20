#!/bin/bash

WPINSTALLDIR=/var/www/html/;

WPINSTALLFLAG=./.installed-wp

echo "docker wp settings start..."

cp -rf /workspaces/growp/.devcontainer/wp-config.php /var/www/html/wp-config.php

wp plugin delete hello --path=${WPINSTALLDIR} --allow-root
wp plugin delete akismet --path=${WPINSTALLDIR} --allow-root
wp theme uninstall --path=${WPINSTALLDIR} twentytwentytwo twentytwentyone twentytwenty twentynineteen twentyeighteen twentyseventeen twentysixteen twentyfifteen twentyfourteen --allow-root
wp core update --path=${WPINSTALLDIR} --allow-root
wp language core install ja --path=${WPINSTALLDIR}
wp language core update --path=${WPINSTALLDIR} --allow-root
wp plugin install \
        wordpress-seo \
        mw-wp-form \
        wp-migrate-db \
        duplicate-post \
        custom-post-type-permalinks \
        custom-post-type-ui \
        intuitive-custom-post-order\
        tinymce-advanced \
        wp-admin-ui-customize \
        siteguard \
        login-rebuilder \
        ga-in \
        classic-editor \
        add-to-any\
        table-of-contents-plus\
        yet-another-related-posts-plugin\
        clear-floats-button\
        relative-url\
        wp-multibyte-patch\
        https://github.com/wp-premium/advanced-custom-fields-pro/archive/master.zip \
        acf-extended\
        http://growp.grgr.red/gg-plugins/admin-columns-pro.zip \
        http://growp.grgr.red/gg-plugins/ac-addon-acf.zip \
        --activate \
        --path=${WPINSTALLDIR} \
        --allow-root

wp language plugin update --all --path=${WPINSTALLDIR} --allow-root

git clone http://github.com/growgroup/gg-styleguide.git /var/www/html/wp-content/themes/gg-styleguide

cd /var/www/html/wp-content/themes/gg-styleguide/ && npm install && npm run build

cp -rf /workspaces/growp/.devcontainer/wp-config.php /var/www/html/wp-config.php