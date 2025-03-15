#\!/bin/bash

# Script pour corriger les permissions Laravel
echo "Correction des permissions pour Laravel GMAO..."

# Définir les variables
APP_DIR="/var/www/gmao"
USER="www-data"
GROUP="www-data"

# Correction des dossiers qui nécessitent des permissions d'écriture
echo "Correction des permissions pour storage et bootstrap/cache..."
sudo chown -R ${USER}:${GROUP} ${APP_DIR}/storage
sudo chown -R ${USER}:${GROUP} ${APP_DIR}/bootstrap/cache
sudo chmod -R 775 ${APP_DIR}/storage
sudo chmod -R 775 ${APP_DIR}/bootstrap/cache

# Vérification des permissions
echo "Vérification terminée. Les permissions ont été corrigées."
