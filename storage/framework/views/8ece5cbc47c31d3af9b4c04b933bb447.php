<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido de Insumos</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Cabeçalho -->
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <h2 style="color: #2c3e50; font-size: 24px; margin: 0;">📋 Pedido de Insumos</h2>
                            <p style="color: #7f8c8d; font-size: 14px;">Número do Pedido: <strong>#<?php echo e($pedido_id); ?></strong></p>
                            <p style="color: #7f8c8d; font-size: 14px;">Data: <?php echo e(date('d/m/Y')); ?></p>
                        </td>
                    </tr>

                    <!-- Informações do Usuário -->
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <p style="color: #2c3e50; font-size: 16px;"><strong>Solicitante:</strong> <?php echo e($usuario); ?></p>
                            <p style="color: #2c3e50; font-size: 16px;"><strong>Unidade:</strong> <?php echo e($equipe); ?></p>
                        </td>
                    </tr>

                    <!-- Tabela de Itens -->
                    <tr>
                        <td>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="background-color: #3498db; color: #ffffff;">
                                        <th style="padding: 10px; text-align: left;">🛒 Insumo</th>
                                        <th style="padding: 10px; text-align: center;">📦 Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $itens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr style="border-bottom: 1px solid #ddd;">
                                            <td style="padding: 10px;"><?php echo e($item['nome']); ?></td>
                                            <td style="padding: 10px; text-align: center;"><?php echo e($item['quantidade']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td style="text-align: center; padding-top: 20px; font-size: 14px; color: #7f8c8d;">
                            <p>📞 Em caso de dúvidas, entre em contato com o setor responsável.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH /var/www/resources/views/emails/pedido_insumos.blade.php ENDPATH**/ ?>