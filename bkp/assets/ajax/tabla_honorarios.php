<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

$anio = $_POST['anio'] ?? date('Y');
$mes  = $_POST['mes']  ?? date('m');
$id_cliente = $_POST['cliente_id'] ?? 0;
$periodo = $anio.$mes;

$stmt = $connect->prepare("SELECT * FROM mov_compras WHERE periodouso = ? and idcliente =? and tipdoc ='02' ORDER BY movkey DESC");
$stmt->execute([$periodo,$id_cliente]);

$colorconta = '#119906';
$colorsire  = '#EB9115';
$colortexto = '#fff';
?>

<?php if($stmt->rowCount() > 0): ?>
<table id="tablacompras" class="table table-striped table-bordered" style="width:100%;">
    <thead class="table-dark">
        <tr>
            <th>movkey</th>
            <th>periodo</th>
            <th>RUC Proveedor</th>
            <th>Razon Proveedor</th>
            <th>tipo</th>
            <th>serie</th>
            <th>número</th>
            <th>fecha</th>
            <th>Descripcionn</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?>;">B.I. Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?>;">I.G.V. Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?>;">oth Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?>;">Total Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?>;">B.I. SIRE</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?>;">I.G.V. SIRE</th>
              <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?>;">Adq. no Gravada</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?>;">oth SIRE</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?>;">total SIRE</th>
            <th>Dif. B.I.</th>
            <th>Dif. I.G.V.</th>
            <th>Adq. no Gravada</th>
            <th>Dif. oth</th>
            <th>Dif. Totales</th>
            <th>Moneda</th>
            <th>Tip. Cambio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
          $moneda = $row['moneda'];
          $simbolo ='$';
          if($moneda=='PEN')
          {
              $simbolo ='S/';
          }
        ?>
            <tr>
                <td><?= htmlspecialchars($row['movkey']) ?></td>
                <td><?= htmlspecialchars($row['periodouso']) ?></td>
                <td><?= htmlspecialchars($row['rucemisor']) ?></td>
                <td><?= htmlspecialchars($row['razonemisor']) ?></td>
                <td><?= htmlspecialchars($row['tipdoc']) ?></td>
                <td><?= htmlspecialchars($row['seriedoc']) ?></td>
                <td><?= htmlspecialchars($row['numdoc']) ?></td>
                <td><?= $row['fechadocsire'] ?></td>
                <th>Descripcionn</th>

                <td align="right"><?= formatMoney($row['basedocple'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['igvdocple'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['othdocple'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['totaldocple'],$simbolo) ?></td>

                <td align="right"><?= formatMoney($row['basedocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['igvdocsire'],$simbolo) ?></td>
                 <td align="right"><?= formatMoney($row['nogravado'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['othdocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['totaldocsire'],$simbolo) ?></td>

                <td align="right"><?= formatMoney($row['basedocple']-$row['basedocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['igvdocple']-$row['igvdocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['nogravado']-$row['nogravado'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['othdocsire']-$row['othdocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['totaldocsire']-$row['totaldocsire'],$simbolo) ?></td>
                <td><?= htmlspecialchars($row['moneda']) ?></td>
                <td><?= htmlspecialchars($row['tcambiosire']) ?></td>
                <td>
    <div class="btn-group" role="group">
        <a class="btn btn-sm btn-primary" 
           href="<?=media()?>/ajax/proxy_descarga.php?tipo=02&ruc=<?= urlencode($row['rucemisor']) ?>&tipodoc=<?= urlencode($row['tipdoc']) ?>&seriedoc=<?= urlencode($row['seriedoc']) ?>&numerodoc=<?= urlencode($row['numdoc']) ?>">
           XML
        </a>
        <a class="btn btn-sm btn-success" 
           href="<?=media()?>/ajax/proxy_descarga.php?tipo=01&ruc=<?= urlencode($row['rucemisor']) ?>&tipodoc=<?= urlencode($row['tipdoc']) ?>&seriedoc=<?= urlencode($row['seriedoc']) ?>&numerodoc=<?= urlencode($row['numdoc']) ?>">
           PDF
        </a>
    </div>
</td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="alert alert-warning">No hay registros para el periodo <?= $anio ?>-<?= $mes ?>.</div>
<?php endif; ?>
