#!/bin/bash
rm -fr web/uploads/documents/*
rm -fr web/uploads/invoices/*
rm -fr web/uploads/orders/*
rm -fr web/uploads/supporting/*
rm -fr web/uploads/users/*
php app/console propel:build
php app/console propel:sql:insert --force
php app/console propel:fixtures:load
php app/console faker:populate

