/**
 * QZ Tray Integration for GMAO
 * This file manages the connection to QZ Tray and handles printing requests
 */

// Store qz object reference and connection status
let qz;
let connected = false;

// Initialize QZ Tray when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Load QZ Tray libraries
    loadQZLibraries()
        .then(initQZ)
        .catch(function(error) {
            console.error('Error loading QZ Tray:', error);
            showQZError('Impossible de charger QZ Tray. Veuillez vérifier que l\'application est installée.');
        });
});

/**
 * Load QZ Tray libraries (from CDN for example)
 */
function loadQZLibraries() {
    return new Promise(function(resolve, reject) {
        // Reference to the QZ Tray library
        if (typeof qz !== 'undefined') {
            resolve();
            return;
        }

        // Load the required JavaScript files
        const qzScript = document.createElement('script');
        qzScript.src = 'https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.min.js';
        qzScript.onload = resolve;
        qzScript.onerror = reject;
        document.head.appendChild(qzScript);
    });
}

/**
 * Initialize QZ Tray connection
 */
function initQZ() {
    if (!qz) {
        qz = window.qz;
    }

    // Set promise type to use native Promises
    qz.api.setPromiseType(function promise(resolver) {
        return new Promise(resolver);
    });

    // Attempt to establish connection
    qz.websocket.connect()
        .then(function() {
            console.log('Connected to QZ Tray');
            connected = true;
            updateQZStatus(true);
        })
        .catch(function(error) {
            console.error('Error connecting to QZ Tray:', error);
            connected = false;
            updateQZStatus(false);
            showQZError('Impossible de se connecter à QZ Tray. Veuillez vérifier que l\'application est en cours d\'exécution.');
        });
}

/**
 * Update QZ Tray connection status in UI
 */
function updateQZStatus(isConnected) {
    const statusElement = document.getElementById('qz-status');
    if (statusElement) {
        statusElement.className = isConnected ? 'connected' : 'disconnected';
        statusElement.textContent = isConnected ? 'QZ Tray connecté' : 'QZ Tray déconnecté';
    }
}

/**
 * Show QZ Tray error message
 */
function showQZError(message) {
    // Display error message to user
    if (document.getElementById('qz-error')) {
        document.getElementById('qz-error').textContent = message;
        document.getElementById('qz-error').style.display = 'block';
    } else {
        alert('Erreur QZ Tray: ' + message);
    }
}

/**
 * Print QR code with QZ Tray
 * @param {string} printerName - Name of the printer to use
 * @param {string} base64Image - Base64 encoded image data
 * @param {Object} options - Printing options
 */
function printQRCode(printerName, base64Image, options = {}) {
    if (!connected) {
        initQZ().then(() => _printQRCode(printerName, base64Image, options));
        return;
    }
    
    _printQRCode(printerName, base64Image, options);
}

/**
 * Internal function to handle QR code printing
 */
function _printQRCode(printerName, base64Image, options = {}) {
    // Default printing options
    const defaultOptions = {
        size: '57mm',
        orientation: 'portrait',
        copies: 1,
        margins: { top: 0, right: 0, bottom: 0, left: 0 }
    };
    
    // Merge default options with provided options
    const printOptions = Object.assign({}, defaultOptions, options);

    // Find printer
    qz.printers.find(printerName)
        .then(function(printer) {
            // Create configuration
            const config = qz.configs.create(printer, printOptions);
            
            // Create print data
            const printData = [{
                type: 'image',
                format: 'base64',
                data: base64Image.replace(/^data:image\/(png|jpg|jpeg);base64,/, '')
            }];
            
            // Send print job
            return qz.print(config, printData);
        })
        .then(function() {
            console.log('QR code sent to printer');
            showPrintSuccess();
        })
        .catch(function(error) {
            console.error('Error printing QR code:', error);
            showQZError('Erreur lors de l\'impression: ' + error);
        });
}

/**
 * Show success message after printing
 */
function showPrintSuccess() {
    const successElement = document.getElementById('print-success');
    if (successElement) {
        successElement.style.display = 'block';
        setTimeout(function() {
            successElement.style.display = 'none';
        }, 3000);
    }
}

/**
 * Get list of available printers
 */
function getAvailablePrinters() {
    if (!connected) {
        return initQZ().then(getAvailablePrinters);
    }
    
    return qz.printers.find()
        .then(function(printers) {
            return printers;
        })
        .catch(function(error) {
            console.error('Error getting printers:', error);
            showQZError('Impossible de récupérer la liste des imprimantes.');
            return [];
        });
}

/**
 * Function to be called from PHP controllers to initiate printing
 */
function printQRCodeFromController(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error('Invalid JSON data for printing');
            return;
        }
    }
    
    if (!data.printerName || !data.imageData) {
        console.error('Missing required printing data');
        return;
    }
    
    printQRCode(data.printerName, data.imageData, data.options || {});
}

// Export functions to global scope
window.QZTray = {
    connect: initQZ,
    printQRCode: printQRCode,
    getPrinters: getAvailablePrinters,
    printFromController: printQRCodeFromController
};