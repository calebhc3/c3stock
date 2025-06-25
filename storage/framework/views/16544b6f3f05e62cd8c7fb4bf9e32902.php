<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato de Suporte</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Cabeçalho -->
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <h2 style="color: #2c3e50; font-size: 24px; margin: 0;">🛠️ Solicitação de Suporte</h2>
                            <p style="color: #7f8c8d; font-size: 14px;">Data de envio: <?php echo e(date('d/m/Y H:i')); ?></p>
                        </td>
                    </tr>

                    <!-- Informações do Remetente -->
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <p style="color: #2c3e50; font-size: 16px;"><strong>Nome:</strong> <?php echo e($nome); ?></p>
                            <p style="color: #2c3e50; font-size: 16px;"><strong>Email:</strong> <?php echo e($email); ?></p>
                        </td>
                    </tr>

                    <!-- Mensagem -->
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <h4 style="color: #2c3e50; font-size: 18px;">📩 Mensagem:</h4>
                            <p style="background-color: #ecf0f1; padding: 15px; border-radius: 5px; font-size: 15px; color: #34495e;">
                                <?php echo e($mensagem); ?>

                            </p>
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td style="text-align: center; padding-top: 20px; font-size: 14px; color: #7f8c8d;">
                            <p>⚙️ Responda esse e-mail para continuar o atendimento.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH /var/www/resources/views/emails/support.blade.php ENDPATH**/ ?>