#!/bin/bash
read -p "URL: " url
read -p "URL admin: " urladmin
read -p "Nom du projet (pas d'espace ni d'accent ni de maj): " project
read -p "Nom du client: " name
read -p "Email de contact: " email
read -p "Téléphone de contact: " phone

git clone git@github.com:Lionel09/bien-dit.git $url
git clone git@github.com:Lionel09/StudioEchoBundles.git $url/src/StudioEchoBundles
cd $url
git branch $project
git checkout $project
git push --set-upstream origin $project
mkdir app/cache app/cache/htmlpurifier app/logs app/sessions web/uploads
cp -R ../data/uploads web/
sudo setfacl -R -m u:www-data:rwx -m u:'adminwww':rwx app/cache app/logs app/sessions web/uploads
sudo setfacl -dR -m u:www-data:rwx -m u:'adminwww':rwx app/cache app/logs app/sessions web/uploads

echo "*** Instance $project initialisée ***"

read -p "Nom de la base: " db_name
read -p "Nom d'utilisateur: " db_user
read -p "Mot de passe: " db_password

touch app/config/parameters.yml
cat > app/config/parameters.yml <<EOF
# This file is auto-generated during the composer install
parameters:
    database_driver: mysql
    database_host: 'bl180859-001.dbaas.ovh.net:35670'
    database_name: $db_name
    database_user: $db_user
    database_password: $db_password
    database_charset: UTF8
    database_dsn: '%database_driver%:host=%database_host%;dbname=%database_name%;charset=%database_charset%'
    mailer_transport: smtp
    mailer_auth_mode: login
    mailer_host: in-v3.mailjet.com
    mailer_port: 587
    mailer_encryption: tls
    mailer_user: 90182fe63e38221a3bb8a623c6e64e02
    mailer_password: 006bbb31bff704c937ff426de9540165
    locale: fr
    secret: 442f9e3bfb7f353a8366e04b239f51dd
    host.admin: $urladmin
    host.public: $url
    router.request_context.host: $url
    router.request_context.scheme: https
    session_name: ${project}_bd
    feed_title: 'Nouvelles publications - ${name^}'
    feed_description: 'Nouvelles publications de ${name^}'
    feed_author: '$name'
    http_cache_base_url: $url
    cookie_name: ${project^^}BIENDIT
    algolia_search: false
    algolia_app_id: 8JS8UNA6O1
    algolia_admin_api_key: 9a0dcb4a01cb18ef465ce6832746c302
    algolia_search_api_key: c8c20f5fe62a729837e1888f5d0e85e3
    algolia_index_name: re7_BIENDIT
    openid_connexion: false
    facebook_client_id: '1896344604003188'
    facebook_client_secret: 82c84a9c3c6748015e9545d68f042edb
    facebook_scope: 'public_profile,email'
    facebook_admin_ids: '1768550816707122'
    facebook_page_id: '555683264482655'
    facebook_graph_version: v3.1
    facebook_access_token: EAAYkP7e3Q14BAL0Ju4qbglm0YxnC0lHKoovA2bofp4rJVrJPA3UHyF7OJbl98NeZBoZBP9bgsYZB0iMT7CKIOZBwXo6jrPbR1ZB4G3abPzPn93V7WiXHHwjNTZBt0ZAJxeQNEWHkuD2guI1L9lADgPJno7RBVBZAS5mFY4yPrS9LbQZDZD
    facebook_pixelcode_id: null
    twitter_api_key: FDH86r6SlO1xMErCXVqnybOqm
    twitter_api_secret: e9nHMNgr6NPaQAu41f5FrbkPsdGHPZmrOd7U0K4jGE3M9ITNxk
    google_client_id: 907494391229-muqmu6q81nu2r84vvoq5i8pgf5o02uh7.apps.googleusercontent.com
    google_client_secret: EVynDc3h7nKqFY5--ujkEJAL
    google_scope: 'email profile'
    google_tagmanager_id: GTM-N2Q2GGN
    google_ga_view_id: 180651950
    contact_email: $email
    contact_phone: '$phone'
    support_email: $email
    sender_email: ne-pas-repondre@bien-dit.com
    idcheck_wsdl_url: 'https://smarteye-test.ariadnext.com:443/ariadnext/ws/SmartEyeWs_v1r0?wsdl'
    idcheck_login: politizr@ariadnext.com
    idcheck_password: Bi3quoov4a
    private_mode: public
    public_user_ids: {  }
    exercise_html_purifier.config.class: Politizr\FrontBundle\Lib\HtmlPurifierConfig
    client_name: '${name^}'
    meta_title: '${name^}'
    meta_description: 'Venez participer aux consultations de ${name^}'
    meta_og_image: share_facebook.jpg
    meta_og_site_name: '${name^}'
    meta_og_title: '${name^}'
    meta_og_description: 'Venez participer aux consultations de ${name^}'
    meta_og_url: 'https://$url'
    meta_tw_site: null
    meta_tw_title: '${name^}'
    meta_tw_description: 'Venez participer aux consultations de ${name^}'
    geo_active: false
    mandate_active: false
    expression_from_scratch: false
    with_group: true
    top_menu_cms: true
    top_menu_publications: false
    top_menu_community: false
    assets_version: 1.0.0
EOF

echo "*** Instance $projet paramétrée ***"

export SYMFONY_ENV=prod
composer install
app/console propel:model:build
app/console assetic:dump --no-debug

echo "*** Env SF de prod du projet $project ***"
