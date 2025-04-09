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
                            <p style="color: #7f8c8d; font-size: 14px;">Número do Pedido: <strong>#{{ $pedido_id }}</strong></p>
                            <p style="color: #7f8c8d; font-size: 14px;">Data: {{ date('d/m/Y') }}</p>
                        </td>
                    </tr>

                    <!-- Informações do Usuário -->
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <p style="color: #2c3e50; font-size: 16px;"><strong>Solicitante:</strong> {{ $usuario }}</p>
                            <p style="color: #2c3e50; font-size: 16px;"><strong>Unidade:</strong> {{ $equipe }}</p>
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
                                    @foreach($itens as $item)
                                        <tr style="border-bottom: 1px solid #ddd;">
                                            <td style="padding: 10px;">{{ $item['nome'] }}</td>
                                            <td style="padding: 10px; text-align: center;">{{ $item['quantidade'] }}</td>
                                        </tr>
                                    @endforeach
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
