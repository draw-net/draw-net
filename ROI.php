<?php

function executarPing($host) {
    $cmd = "ping -n 1 " . $host; // Para Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') { // Para Linux/Mac
        $cmd = "ping -c 1 " . $host;
    }

    $output = [];
    exec($cmd, $output, $return_var);

    if ($return_var === 0) {
        foreach ($output as $line) {
            if (strpos($line, 'time=') !== false) {
                preg_match('/time=([\d\.]+)ms/', $line, $matches);
                if(isset($matches[1])) {
                    return (float)$matches[1];
                }
            }
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ping_host = $_POST["ping_host"] ?? "8.8.8.8";
    $injecao_materiais = (float)($_POST["injecao_materiais"] ?? 0);
    $valor_projeto = (float)($_POST["valor_projeto"] ?? 0);
    $indice_ipc = (float)($_POST["indice_ipc"] ?? 0);

    $tempo_ping = executarPing($ping_host);

    if($tempo_ping) {
         // Cálculo da Aceleração de Crescimento do ROI (base)
       $aceleracao_roi = ($injecao_materiais * $valor_projeto) - $indice_ipc;

        // Influência do Ping (quanto menor o ping, melhor a influência)
        $influencia_ping = 1; // Começamos com uma influencia base
         if($tempo_ping < 50) {
          $influencia_ping = 1.5; // Se o ping for muito bom
         } elseif ($tempo_ping < 100) {
            $influencia_ping = 1.3;
         } else if ($tempo_ping < 200) {
             $influencia_ping = 1.1;
         }  else {
              $influencia_ping = 0.9;
         }

          // Potencial de ROI influenciado (simulação de criptomoeda)
        $potencial_roi_cripto = $aceleracao_roi * $influencia_ping;

        echo "Potencial de ROI (Simulação Cripto): " . number_format($potencial_roi_cripto, 2) ;
    } else {
        echo "Erro ao executar o ping, verifique o host.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cálculo de ROI com Simulação Cripto</title>
</head>
<body>
  <form method="post">
  <label for="ping_host">Host do Ping:</label><br>
    <input type="text" id="ping_host" name="ping_host" value="8.8.8.8"><br>
    <label for="injecao_materiais">Injeção de Materiais:</label><br>
    <input type="text" id="injecao_materiais" name="injecao_materiais" value=""><br>
    <label for="valor_projeto">Valor do Projeto:</label><br>
    <input type="text" id="valor_projeto" name="valor_projeto" value=""><br>
    <label for="indice_ipc">Índice IPC:</label><br>
    <input type="text" id="indice_ipc" name="indice_ipc" value=""><br>
    <input type="submit" value="Calcular Potencial de ROI (Cripto)">
  </form>
</body>
</html>
