/**
 * QZ Tray Integration for GMAO
 * Improved version with lazy loading, connection pooling, and error recovery
 */

// Store QZ Tray state
window.QZTray = {
    qz: null,
    connected: false,
    connecting: false,
    connectionPromise: null,
    printerList: [],
    listeners: [],
    config: {
        reconnectAttempts: 3,
        reconnectDelay: 1000,
        certificatePromise: null
    },
    
    /**
     * Initialize QZ Tray with lazy loading
     */
    init: function() {
        // Only need to register event handlers once
        if (this.initialized) return;
        
        // Register connection status handler
        window.addEventListener('focus', () => {
            if (!this.connected && !this.connecting) {
                console.log('Window focused, checking QZ Tray connection');
                this.connect();
            }
        });
        
        // Mark as initialized
        this.initialized = true;
        
        // Don't auto-connect, we'll connect on demand
        console.log('QZ Tray integration initialized');
    },
    
    /**
     * Connect to QZ Tray
     */
    connect: function() {
        // If already connected, return successful promise
        if (this.connected) {
            return Promise.resolve();
        }
        
        // If already connecting, return the existing promise
        if (this.connectionPromise) {
            return this.connectionPromise;
        }
        
        // Mark as connecting
        this.connecting = true;
        
        // Create a connection promise
        this.connectionPromise = new Promise((resolve, reject) => {
            console.log('Connecting to QZ Tray...');
            
            // First, make sure QZ libraries are loaded
            this._loadQZLibraries()
                .then(() => {
                    if (!this.qz) {
                        this.qz = window.qz;
                        
                        // Set promise type to use native Promises
                        this.qz.api.setPromiseType(function promise(resolver) {
                            return new Promise(resolver);
                        });
                        
                        // Configure for site
                        this.qz.security.setCertificatePromise(function(resolve, reject) {
                            // Utiliser une approche simple pour le certificat
                            resolve("-----BEGIN CERTIFICATE-----\n" +
                                   "MIID9TCCAt2gAwIBAgIJAKfOvMpZG0LOMA0GCSqGSIb3DQEBCwUAMIGQMQswCQYD\n" +
                                   "VQQGEwJGUjEOMAwGA1UECAwFTWFyb2MxFDASBgNVBAcMC0Nhc2FibGFuY2ExFDAS\n" +
                                   "BgNVBAoMC1RlY2FMRUQgTExDMQ8wDQYDVQQLDAZHbWFvSVQxFzAVBgNVBAMMDmdt\n" +
                                   "YW8udGVjYWxlZC5mchEwDwYJKoZIhvcNAQkBFgJOQTAeFw0yNTAzMTcwNzI4MTda\n" +
                                   "Fw0zNTAzMTcwNzI4MTdaMIGQMQswCQYDVQQGEwJGUjEOMAwGA1UECAwFTWFyb2Mx\n" +
                                   "FDASBgNVBAcMC0Nhc2FibGFuY2ExFDASBgNVBAoMC1RlY2FMRUQgTExDMQ8wDQYD\n" +
                                   "VQQLDAZHbWFvSVQxFzAVBgNVBAMMDmdt\n" +
                                   "YW8udGVjYWxlZC5mchEwDwYJKoZIhvcNAQkBFgJOQTCCASIwDQYJKoZIhvcNAQEB\n" +
                                   "BQADggEPADCCAQoCggEBALg+Cl9/9R5FMXlkfUy8H7SegFfSu2Y2NpRgMZIgY2Bk\n" +
                                   "Z6ECBCI0EVbdRCYmAOY61+WKSY5TecAL\n" +
                                   "-----END CERTIFICATE-----");
                        });
                        
                        // Signer pour autoriser l'accès à QZ Tray
                        this.qz.security.setSignaturePromise(function(toSign) {
                            return function(resolve, reject) {
                                try {
                                    // Signature simple pour les besoins de test
                                    const signature = { "r": "abcdefg", "s": "hijklmn", "hashAlgorithm": "SHA512" };
                                    resolve(signature);
                                } catch(err) {
                                    reject(err);
                                }
                            };
                        });
                    }
                    
                    // Try to connect with retries
                    return this._attemptConnection(this.config.reconnectAttempts);
                })
                .then(() => {
                    console.log('Connected to QZ Tray successfully');
                    this.connected = true;
                    this.connecting = false;
                    this._updateQZStatusUI(true);
                    resolve();
                })
                .catch((error) => {
                    console.error('Failed to connect to QZ Tray:', error);
                    this.connected = false;
                    this.connecting = false;
                    this._updateQZStatusUI(false);
                    this._showError('Connexion à QZ Tray impossible. Veuillez vérifier que l\'application est en cours d\'exécution.');
                    
                    // Clear connection promise so we can try again later
                    this.connectionPromise = null;
                    reject(error);
                });
        });
        
        return this.connectionPromise;
    },
    
    /**
     * Load QZ Tray libraries if needed
     */
    _loadQZLibraries: function() {
        return new Promise((resolve, reject) => {
            // If QZ is already loaded, resolve immediately
            if (window.qz) {
                resolve();
                return;
            }
            
            // Check if script is already being loaded
            const existingScript = document.querySelector('script[src*="qz-tray"]');
            if (existingScript) {
                existingScript.addEventListener('load', resolve);
                existingScript.addEventListener('error', reject);
                return;
            }
            
            // Load the script
            console.log('Loading QZ Tray libraries...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.min.js';
            script.async = true;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    },
    
    /**
     * Attempt connection to QZ Tray with retries
     */
    _attemptConnection: function(retriesLeft) {
        console.log(`Connecting to QZ Tray (${retriesLeft} retries left)...`);
        
        return this.qz.websocket.connect()
            .catch((error) => {
                if (retriesLeft > 0) {
                    console.log(`Connection failed, retrying in ${this.config.reconnectDelay}ms...`);
                    
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve(this._attemptConnection(retriesLeft - 1));
                        }, this.config.reconnectDelay);
                    });
                }
                
                throw error;
            });
    },
    
    /**
     * Update QZ status in UI
     */
    _updateQZStatusUI: function(isConnected) {
        const statusElement = document.getElementById('qz-status');
        if (statusElement) {
            statusElement.className = `badge ${isConnected ? 'bg-success' : 'bg-danger'}`;
            statusElement.textContent = isConnected ? 'QZ Tray connecté' : 'QZ Tray déconnecté';
        }
    },
    
    /**
     * Show error in UI
     */
    _showError: function(message) {
        const errorElement = document.getElementById('qz-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        } else {
            console.error('QZ Tray error:', message);
        }
    },
    
    /**
     * Show success message
     */
    _showSuccess: function(message = 'Impression réussie') {
        const successElement = document.getElementById('print-success');
        if (successElement) {
            successElement.textContent = message;
            successElement.style.display = 'block';
            
            setTimeout(() => {
                successElement.style.display = 'none';
            }, 3000);
        }
    },
    
    /**
     * Get available printers
     */
    getPrinters: function(forceRefresh = false, filter = '') {
        // Use cached printer list if available and no refresh is required
        if (this.printerList.length > 0 && !forceRefresh) {
            return Promise.resolve(this._filterPrinters(this.printerList, filter));
        }
        
        // Connect first if not connected
        return this.connect()
            .then(() => this.qz.printers.find())
            .then((printers) => {
                this.printerList = printers;
                return this._filterPrinters(printers, filter);
            })
            .catch((error) => {
                console.error('Error getting printers:', error);
                this._showError('Impossible de récupérer la liste des imprimantes');
                return [];
            });
    },
    
    /**
     * Filter printers by name
     */
    _filterPrinters: function(printers, filter = '') {
        if (!filter) return printers;
        
        const lowerFilter = filter.toLowerCase();
        return printers.filter(printer => 
            printer.toLowerCase().includes(lowerFilter)
        );
    },
    
    /**
     * Print QR code
     */
    printQRCode: function(printerName, imageData, options = {}) {
        return this.connect()
            .then(() => {
                // Default options
                const printOptions = Object.assign({
                    size: '62mm',
                    height: '20mm',
                    orientation: 'portrait',
                    copies: 1,
                    margins: { top: 0, right: 0, bottom: 0, left: 0 },
                    dpi: 300,
                    rasterize: true
                }, options);
                
                // Find the printer
                return this.qz.printers.find(printerName)
                    .then((printer) => {
                        if (!printer) {
                            throw new Error(`Imprimante "${printerName}" non trouvée`);
                        }
                        
                        // Create print configuration
                        const config = this.qz.configs.create(printer, {
                            margins: printOptions.margins,
                            copies: printOptions.copies,
                            orientation: printOptions.orientation,
                            density: printOptions.dpi,
                            rasterize: printOptions.rasterize,
                            scaleContent: true,
                            paperWidth: printOptions.size,
                            paperHeight: printOptions.height,
                            units: 'mm'
                        });
                        
                        // Clean up image data if it's a data URL
                        let cleanImageData = imageData;
                        if (typeof imageData === 'string' && imageData.startsWith('data:image/')) {
                            cleanImageData = imageData.replace(/^data:image\/(png|jpg|jpeg);base64,/, '');
                        }
                        
                        // Create print data
                        const printData = [{
                            type: 'image',
                            format: 'base64',
                            data: cleanImageData,
                            options: {
                                language: 'ESCPOS',
                                dotDensity: 'double',
                                alignment: 'center'
                            }
                        }];
                        
                        // Print with retry logic
                        return this.qz.print(config, printData)
                            .catch((error) => {
                                console.warn('First print attempt failed, retrying with alternative settings:', error);
                                
                                // Try with alternative settings
                                config.reconfigure({
                                    rasterize: !config.getConfiguration().rasterize,
                                    altPrinting: true
                                });
                                
                                return this.qz.print(config, printData);
                            });
                    });
            })
            .then(() => {
                console.log('Print job sent successfully');
                this._showSuccess();
                return true;
            })
            .catch((error) => {
                console.error('Print error:', error);
                this._showError(`Erreur d'impression: ${error.message || error}`);
                return false;
            });
    },
    
    /**
     * Print from controller data
     */
    printFromController: function(data) {
        // Parse the data if it's a string
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
            } catch (e) {
                console.error('Invalid print data JSON:', e);
                this._showError('Données d\'impression invalides');
                return Promise.reject(e);
            }
        }
        
        // Validate required fields
        if (!data.printerName || !data.imageData) {
            const error = new Error('Données d\'impression incomplètes (imprimante ou image manquante)');
            console.error(error);
            this._showError(error.message);
            return Promise.reject(error);
        }
        
        // Send to printer
        return this.printQRCode(data.printerName, data.imageData, data.options || {});
    },
    
    /**
     * Check printer status
     */
    checkPrinterStatus: function(printerName) {
        return this.connect()
            .then(() => this.qz.printers.find(printerName))
            .then((printer) => {
                if (!printer) {
                    return { exists: false, status: 'not_found' };
                }
                
                return { exists: true, status: 'online' };
            })
            .catch((error) => {
                console.error('Error checking printer status:', error);
                return { exists: false, status: 'error', error: error.message };
            });
    }
};

// Initialize QZ Tray integration when the page loads
document.addEventListener('DOMContentLoaded', function() {
    window.QZTray.init();
});