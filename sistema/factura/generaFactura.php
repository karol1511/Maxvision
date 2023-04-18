<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	include "../../conexion.php";
	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		$ventas = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = $noFactura");
		$result_venta = mysqli_fetch_assoc($ventas);
		$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $codCliente");
		$result_cliente = mysqli_fetch_assoc($clientes);
		$productos = mysqli_query($conexion, "SELECT d.nofactura, d.codproducto, d.cantidad, p.codproducto, p.descripcion, p.precio FROM detallefactura d INNER JOIN producto p ON d.nofactura = $noFactura WHERE d.codproducto = p.codproducto");
		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('P', 'mm', array(63, 200));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Ventas");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(38, 5, utf8_decode($resultado['nombre']), 0, 1, 'C');
		$pdf->Ln();
		$pdf->image("img/logo.jpg", 13, 14, 30, 30, 'JPG');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(58,5, utf8_decode("Teléfono "), 0, 1, 'C');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(58, 5, $resultado['telefono'], 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(58, 5, utf8_decode("Dirección "), 0, 0, 'C');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(-58, 13, utf8_decode($resultado['direccion']), 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(9, 5, "Ticket: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(19, 5, $noFactura, 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(1, 5, "Fecha: ", 0, 0, 'R');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(25, 5, $result_venta['fecha'], 0, 1, 'R');
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(58, 5, "Datos del cliente", 0, 1, 'C');
		$pdf->Cell(13, 5, "Nombre:", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(40, 5, utf8_decode($result_cliente['nombre']), 0, 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Ln();
		$pdf->Cell(58, 5, "Detalle de Productos", 0, 1, 'C');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(23, 5, 'Nombre', 0, 0, 'L');
		$pdf->Cell(8, 5, 'Cant', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Total', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 7);
		while ($row = mysqli_fetch_assoc($productos)) {
			$pdf->Cell(24, 5, utf8_decode($row['descripcion']), 0, 0, 'L');
			$pdf->Cell(8, 5, $row['cantidad'], 0, 0, 'L');
			$pdf->Cell(15, 5, number_format($row['precio'], 2, '.', ','), 0, 0, 'L');
			$importe = number_format($row['cantidad'] * $row['precio'], 2, '.', ',');
			$pdf->Cell(15, 5, $importe, 0, 1, 'L');
		}
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Cell(50, 5, 'Total: ' . number_format($result_venta['totalfactura'], 2, '.', ','), 0, 1, 'R');
		$pdf->Ln();
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(65, 5, utf8_decode("Gracias por su preferencia"), 0, 1, 'C');
		$pdf->Output("compra.pdf", "I");
		}

?>
