

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Impression de test via PrintNode</div>

                <div class="card-body">
                    <!-- Status Panel -->
                    <div class="print-status mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Statut de l'impression</h5>
                            <div id="print-status" class="badge bg-success">PrintNode connecté</div>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div id="print-progress" class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <div id="print-error" class="alert alert-danger mt-2" style="display: none;"></div>
                        <div id="print-success" class="alert alert-success mt-2" style="display: none;">
                            Test d'impression envoyé à PrintNode avec succès!
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Printer Details -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Imprimante</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Nom</th>
                                            <td><?php echo e($printer->name); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td><?php echo e(ucfirst($printer->type)); ?></td>
                                        </tr>
                                        <?php if($printer->model): ?>
                                        <tr>
                                            <th>Modèle</th>
                                            <td><?php echo e($printer->model); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($printer->printnode_id): ?>
                                        <tr>
                                            <th>PrintNode ID</th>
                                            <td><?php echo e($printer->printnode_id); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($printer->dpi): ?>
                                        <tr>
                                            <th>DPI</th>
                                            <td><?php echo e($printer->dpi); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($printer->label_width && $printer->label_height): ?>
                                        <tr>
                                            <th>Dimensions</th>
                                            <td><?php echo e($printer->label_width); ?> x <?php echo e($printer->label_height); ?> mm</td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- QR Code Preview -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Aperçu du QR Code</h5>
                                </div>
                                <div class="card-body text-center py-4">
                                    <img src="<?php echo e($printData['imageData']); ?>" alt="QR Code" class="img-fluid" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo e(route('printers.test-labels')); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left mr-1"></i> Retour au test
                        </a>
                        <div>
                            <a href="https://app.printnode.com/app/printers" target="_blank" class="btn btn-warning me-2">
                                <i class="fas fa-external-link-alt mr-1"></i> Interface PrintNode
                            </a>
                            <!-- Remplacer le bouton par un lien direct -->
                            <a href="<?php echo e(url('/printers/' . $printer->id . '/direct-print')); ?>" class="btn btn-success">
                                <i class="fas fa-print mr-1"></i> Imprimer
                            </a>
                        </div>
                    </div>
                    
                    <!-- PrintNode Info -->
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <h5>Information PrintNode</h5>
                            <p>Cette application utilise PrintNode pour l'impression. L'imprimante Brother QL-820NWB doit être configurée dans votre compte PrintNode.</p>
                            <p>Assurez-vous que:</p>
                            <ul>
                                <li>Le client PrintNode est installé sur l'ordinateur connecté à l'imprimante</li>
                                <li>L'imprimante est en ligne dans l'interface PrintNode</li>
                                <li>L'ID PrintNode correct est configuré dans cette application</li>
                                <li>Le mode d'impression est configuré sur "Raster"</li>
                                <li>Le bon type de rouleau est sélectionné (DK-22205 pour 62mm continu)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/gmao/resources/views/printers/test_result.blade.php ENDPATH**/ ?>