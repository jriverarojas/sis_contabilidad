<?php
// Extend the TCPDF class to create custom MultiRow   manu  p
class RRetencion extends ReportePDF {
	var $datos_titulo;
	var $datos_detalle;
	var $ancho_hoja;
	var $gerencia;
	var $numeracion;
	var $ancho_sin_totales;
	var $cantidad_columnas_estaticas;
	var $s1;
	var $s2;
	var $s3;
	var $s4;
	var $s5;
	var $s6;
	var $t1;
	var $t2;
	var $t3;
	var $t4;
	var $t5;
	var $t6;
	var $total;
	var $datos_entidad;
	var $datos_periodo;	
	//desde control 
	function datosHeader ($detalle, $totales,$entidad, $periodo) {		
		$tipo =$this->objParam->getParametro('tipo_ret');
		$this->ancho_hoja = $this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT-10;
		$this->datos_detalle = $detalle;
		$this->datos_titulo = $totales;
		$this->datos_entidad = $entidad;
		$this->datos_periodo = $periodo;
		$this->subtotal = 0;
		if($tipo=='todo'){
			$this->SetHeaderMargin(13);		
			$this->SetAutoPageBreak(TRUE, 10);	
			$this->SetMargins(20, 55, 10);
		}else{						
			$this->SetHeaderMargin(9);			
			$this->SetAutoPageBreak(TRUE, 10);
			//(left,bottom,right,TOP) 
			$this->SetMargins(20, 51, 35);	
		}
	}
	//
	function Header() {
		$white = array('LTRB' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 255)));
		$black = array('T' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$this->Ln(3);
		$this->Image(dirname(__FILE__).'/../../lib/imagenes/logos/logo.jpg', 10,5,40,20);
		$this->ln(5);
		$this->SetFont('','B',12);	
		
		$height = 5;
		$width1 = 5;
		$esp_width = 10;
		$width_c1= 42;
		$width_c2= 71;
		$width3 = 40;
		$width4 = 75;
		$width5 = 10;
		$width6 = 25;		
		$nombre =$this->objParam->getParametro('tipo_ret');
		switch ($nombre) {
			case 'rcrb':
				$nombre='Recibo con Retenciones Bienes';
				break;
			case 'rcrs':
				$nombre='Recibo con Retenciones Servicios';
				break;
			case 'rcra':
				$nombre='Recibo con Retenciones de Alquiler';
				break;
			case 'todo':				
				$nombre='Retenciones';
				$height = 5;
		        $width1 = 5;
				$esp_width = 10;
		        $width_c1= 55;
				$width_c2= 112;
		        $width3 = 40;
		        $width4 = 75;
				$width5 = 20;
				$width6 = 50;
				break;	
			default:				
				break;
		}	
		$this->Cell(0,5,$nombre,0,1,'C');		
		$this->SetFont('','B',7);
		$this->Cell(0,5,"(Expresado en Bolivianos)",0,1,'C');		
		$this->Ln(2);
		$this->SetFont('','',10);
	
		if($this->objParam->getParametro('filtro_sql') == 'fechas'){
			$fecha_ini =$this->objParam->getParametro('fecha_ini');
			$fecha_fin = $this->objParam->getParametro('fecha_fin');
			///////////////////////////////////////////////
			$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->Cell($width_c1, $height, 'Del:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->SetFont('', '');
			$this->SetFillColor(192,192,192, true);			
			$this->Cell($width_c2, $height, $fecha_ini, 0, 0, 'L', true, '', 0, false, 'T', 'C');
			///////////////////////////////////////////////			
			$this->Cell($esp_width, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->Cell($width5, $height,'Hasta:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->SetFont('', '');
			$this->SetFillColor(192,192,192, true);			
			$this->Cell(50, $height, $fecha_fin, 0, 0, 'L', true, '', 0, false, 'T', 'C');
			//////////////////////////////////////////////
		}
		else{
			$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->Cell($width_c1, $height, 'Año:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->SetFont('', '');
			$this->Cell($width_c2, $height, $this->datos_periodo['gestion'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
			////////////////////////////////////////////
			$this->Cell($esp_width, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->Cell($width5, $height,'Mes:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
			$this->SetFont('', '');
			$this->Cell(50, $height, $this->datos_periodo['literal_periodo'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
		}
		$this->Ln();
		/////////////////////////////////////////////
		$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell($width_c1, $height, 'Nombre o Razón Social:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->SetFont('', '');
		$this->SetFillColor(192,192,192, true);
		$this->Cell($width_c2, $height, $this->datos_entidad['nombre'].' ('.$this->datos_entidad['direccion_matriz'].')', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		////////////////////////////////////////
		$this->Cell($esp_width, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell($width5, $height,'NIT:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->SetFont('', '');
		$this->SetFillColor(192,192,192, true);
		$this->Cell($width6, $height, $this->datos_entidad['nit'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
		///////////////////////////////////////////
		$this->Ln(8);
		$this->SetFont('','B',6);
		$x =$this->objParam->getParametro('tipo_ret');
		if($x=='todo'){
			$this->cab();
		}
		$this->generarCabecera();
	}
	//
	function Footer() {
		$this->setY(-15);
		$ormargins = $this->getOriginalMargins();
		$this->SetTextColor(0, 0, 0);
		$line_width = 0.85 / $this->getScaleFactor();
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
		$this->Ln(2);
		$cur_y = $this->GetY();
		$this->Cell($ancho, 0, '', '', 0, 'L');
		$pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
		$this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
		$this->Cell($ancho, 0, '', '', 0, 'R');
		$this->Ln();
		$fecha_rep = date("d-m-Y H:i:s");
		$this->Cell($ancho, 0, '', '', 0, 'L');
		$this->Ln($line_width);
	}
	//desde control
	function generarReporte() {
		$this->setFontSubsetting(false);
		$this->AddPage();
		$sw = false;
		$concepto = '';
		$this->generarCuerpo($this->datos_detalle);
		if($this->s1 != 0){
			$this->SetFont('','B',6);
			$this->cerrarCuadro();	
			$this->cerrarCuadroTotal();
		}
		$this->Ln(4);
	} 
	//
	function cab(){
		$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15,15,15,15,16);
		$conf_par_tablealigns=array('C','C','C','C','C','R','L','R','L','R','L','C','C','C');
		$conf_tableborders=array('LTR','TR','TR','TR','TR','T','TR','T','TR','T','TR','TR','TR','TR');
		$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$RowArray = array(
						's0' => '',
						's1' => '',
						's2' => '',
						's3' => '',
						's4' => '',
						's5' => 'BI',
						's6' => 'ENES',
						's7' => 'SERV',
						's8' => 'ICIOS',
						's9' => 'ALQU',
						's10' => 'ILERES',
						's11' => 'IMPORTE ',
						's12' => '',
						's13' => ''
					);
		$this->tablewidths=$conf_par_tablewidths;
		$this->tablealigns=$conf_par_tablealigns;
		$this->tablenumbers=$conf_par_tablenumbers;
		$this->tableborders=$conf_tableborders;
		$this->tabletextcolor=$conf_tabletextcolor;	
		$this->MultiRow($RowArray, false, 1);
	}
	//
	function generarCabecera(){
		$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,16);
		$conf_par_tablealigns=array('C','C','C','C','C','C','C','C','C','C');
		$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0,0,0);
		$conf_tableborders=array();
		$conf_tabletextcolor=array();	
		$nombre =$this->objParam->getParametro('tipo_ret');
		switch ($nombre) {
			case 'rcrb':
				$m='IUE';
				$RowArray = array(
								's0' => 'Nº',
								's1' => 'FECHA DE LA FACTURA O DUI',
								's2' => 'CONCEPTO',
								's3' => 'Nro DE DOCUMENTO',
								's4' => 'IMPORTE TOTAL',
								's5' => 'IT',
								's6' => $m,
								's7' => 'IMPORTE DESCUENTO DE LEY',
								's8' => 'DESCUENTOS',
								's9' => 'LIQUIDO'
							);
				break;
			case 'rcrs':
				$m='IUE';
				$RowArray = array(
								's0' => 'Nº',
								's1' => 'FECHA DE LA FACTURA O DUI',
								's2' => 'CONCEPTO',
								's3' => 'Nro DE DOCUMENTO',
								's4' => 'IMPORTE TOTAL',
								's5' => 'IT',
								's6' => $m,
								's7' => 'IMPORTE DESCUENTO DE LEY',
								's8' => 'DESCUENTOS',
								's9' => 'LIQUIDO'
							);
				break;
			case 'rcra':
				$m='RC-IVA';
				$RowArray = array(
								's0' => 'Nº',
								's1' => 'FECHA DE LA FACTURA O DUI',
								's2' => 'CONCEPTO',
								's3' => 'Nro DE DOCUMENTO',
								's4' => 'IMPORTE TOTAL',
								's5' => 'IT',
								's6' => $m,
								's7' => 'IMPORTE DESCUENTO DE LEY',
								's8' => 'DESCUENTOS',
								's9' => 'LIQUIDO'
							);
				break;
			case 'todo':
				$m='-';
				$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15,15,15,15,16);
				$conf_par_tablealigns=array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
				$conf_tableborders=array('LBR','BR','BR','BR','BR','BTR','BTR','BTR','BTR','BTR','BTR','BR','BR','BR');
				$RowArray = array(
								's0' => 'Nº',
								's1' => 'FECHA DE LA FACTURA O DUI',
								's2' => 'CONCEPTO',
								's3' => 'Nro DE DOCUMENTO',
								's4' => 'IMPORTE',
								's5' => 'IT',
								's6' => 'IUE',
								's7' => 'IT',
								's8' => 'IUE',
								's9' => 'IT',
								's10' => 'RC-IVA',
								's11' => 'DESCUENTO DE LEY',
								's12' => 'DESCUENTOS',
								's13' => 'LIQUIDO'
							);
				break;	
			default:				
				break;
		}	

		$this->tablewidths=$conf_par_tablewidths;
		$this->tablealigns=$conf_par_tablealigns;
		$this->tablenumbers=$conf_par_tablenumbers;
		$this->tableborders=$conf_tableborders;
		$this->tabletextcolor=$conf_tabletextcolor;
		$this->MultiRow($RowArray, false, 1);
	}
	//desde generarcuerpo
	function imprimirLinea($val,$count,$fill){
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('','',6);				
		$newDate = date("d/m/Y", strtotime( $val['fecha']));
		$nombre =$this->objParam->getParametro('tipo_ret');		
		if($nombre=='todo'){
			$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15,15,15,15,16);
			$conf_par_tablealigns=array('C','C','C','C','R','R','R','R','R','R','R','R','R','R');
			$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$conf_tableborders=array('LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR');		
			$RowArray = array(  's0' => $count,
								's1' => $newDate,
								's2' => trim($val['obs']),
								's3' => trim($val['plantilla']),
								's4' => $val['importe_doc'],
								's5' => $val['it_bienes'],
								's6' => $val['iue_bienes'],
								's7' => $val['it_servicios'],
								's8' => $val['iue_servicios'],		
								's9' => $val['it_alquileres'],
								's10' => $val['rc_iva_alquileres'],
								's11' => $val['importe_descuento_ley'],
								's12' => $val['descuento'],
								's13' => $val['liquido']			
							);
		}else{
			$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,16);
			$conf_par_tablealigns=array('C','C','C','C','R','R','R','R','R','R');
			$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0,0,0);
			$conf_tableborders=array('LR','LR','LR','LR','LR','LR','LR','LR','LR','LR');											
			switch ($nombre) {
				case 'rcrb':
					$RowArray = array(  's0' => $count,
										's1' => $newDate,
										's2' => trim($val['obs']),
										's3' => trim($val['plantilla']),
										's4' => $val['importe_doc'],		
										's5' => $val['it_bienes'],
										's6' => $val['iue_bienes'],
										's7' => $val['importe_descuento_ley'],
										's8' => $val['descuento'],
										's9' => $val['liquido']			
									);
					break;
				case 'rcrs':
					$RowArray = array(  's0' => $count,
										's1' => $newDate,
										's2' => trim($val['obs']),
										's3' => trim($val['plantilla']),
										's4' => $val['importe_doc'],		
										's5' => $val['it_servicios'],
										's6' => $val['iue_servicios'],
										's7' => $val['importe_descuento_ley'],
										's8' => $val['descuento'],
										's9' => $val['liquido']			
									);
					break;
				case 'rcra':
					$RowArray = array(  's0' => $count,
										's1' => $newDate,
										's2' => trim($val['obs']),
										's3' => trim($val['plantilla']),
										's4' => $val['importe_doc'],		
										's5' => $val['it_alquileres'],
										's6' => $val['rc_iva_alquileres'],
										's7' => $val['importe_descuento_ley'],
										's8' => $val['descuento'],
										's9' => $val['liquido']			
									);
					break;
				default:					
					break;
			}
		}		
		$this->tablewidths=$conf_par_tablewidths;
		$this->tablealigns=$conf_par_tablealigns;
		$this->tablenumbers=$conf_par_tablenumbers;
		$this->tableborders=$conf_tableborders;
		$this->tabletextcolor=$conf_tabletextcolor;
		$this->calcularMontos($val);		
		$this-> MultiRow($RowArray,$fill,0);
	}	
	//generar reporte
	function generarCuerpo($detalle){
		$count = 1;
		$sw = 0;
		$ult_region = '';
		$fill = 0;
		$this->total = count($detalle);
		$this->s1 = 0;
		$this->s2 = 0;
		$this->s3 = 0;
		$this->s4 = 0;
		$this->s5 = 0;
		$this->s6 = 0;
		$this->s7 = 0;
		$this->s8 = 0;
		$this->s9 = 0;
		$this->s10 = 0;
		$this->s11 = 0;
		$this->s12 = 0;
		$this->s13 = 0;
		foreach ($detalle as $val) {			
			$this->imprimirLinea($val,$count,$fill);
			$fill = !$fill;
			$count = $count + 1;
			$this->total = $this->total -1;
			$this->revisarfinPagina();
		}
	}	
	//desde generarcuerpo
	function revisarfinPagina(){
		$dimensions = $this->getPageDimensions();
		$hasBorder = false;
		$startY = $this->GetY();
		$x=0;
		if($this->objParam->getParametro('tipo_ret')=='todo'){
			$this->getNumLines($row['cell1data'], 80);
			$x=190;	
		}else{
			$this->getNumLines($row['cell1data'], 80);
			$x=80;
		}		
		if ($startY > $x) {
			$this->cerrarCuadro();
			$this->cerrarCuadroTotal();
			if($this->total!= 0){
				$this->AddPage();
			}
		}
	}
	//imprimirLinea suma filas
	function calcularMontos($val){	
		if($this->objParam->getParametro('tipo_ret')=='todo'){
			
			$this->s1 = $this->s1 + $val['importe_doc'];
			$this->s2 = $this->s2 + $val['it_bienes'];
			$this->s3 = $this->s3 + $val['iue_bienes'];
			$this->s4 = $this->s4 + $val['it_servicios'];
			$this->s5 = $this->s5 + $val['iue_servicios'];
			$this->s6 = $this->s6 + $val['it_alquileres'];
			$this->s7 = $this->s7 + $val['rc_iva_alquileres'];
			$this->s8 = $this->s8 + $val['importe_descuento_ley'];
			$this->s9 = $this->s9 + $val['descuento'];
			$this->s10 = $this->s10 + $val['liquido'];
						
			$this->t1 = $this->t1 + $val['importe_doc'];
			$this->t2 = $this->t2 + $val['it_bienes'];
			$this->t3 = $this->t3 + $val['iue_bienes'];
			$this->t4 = $this->t4 + $val['it_servicios'];
			$this->t5 = $this->t5 + $val['iue_servicios'];
			$this->t6 = $this->t6 + $val['it_alquileres'];
			$this->t7 = $this->t7 + $val['rc_iva_alquileres'];
			$this->t8 = $this->t8 + $val['importe_descuento_ley'];
			$this->t9 = $this->t9 + $val['descuento'];
			$this->t10 = $this->t10 + $val['liquido'];
			
		}else{
			$this->s1 = $this->s1 + $val['importe_doc'];
			$this->s2 = $this->s2 + $val['it_bienes'];
			$this->s3 = $this->s3 + $val['iue_bienes'];
			$this->s4 = $this->s4 + $val['it_servicios'];
			$this->s5 = $this->s5 + $val['iue_servicios'];
			$this->s6 = $this->s6 + $val['it_alquileres'];
			$this->s7 = $this->s7 + $val['rc_iva_alquileres'];
			$this->s8 = $this->s8 + $val['importe_descuento_ley'];
			$this->s9 = $this->s9 + $val['descuento'];
			$this->s10 = $this->s10 + $val['liquido'];
						
			$this->t1 = $this->t1 + $val['importe_doc'];
			$this->t2 = $this->t2 + $val['it_bienes'];
			$this->t3 = $this->t3 + $val['iue_bienes'];
			$this->t4 = $this->t4 + $val['it_servicios'];
			$this->t5 = $this->t5 + $val['iue_servicios'];
			$this->t6 = $this->t6 + $val['it_alquileres'];
			$this->t7 = $this->t7 + $val['rc_iva_alquileres'];
			$this->t8 = $this->t8 + $val['importe_descuento_ley'];
			$this->t9 = $this->t9 + $val['descuento'];
			$this->t10 = $this->t10 + $val['liquido'];
		}
	}
	//revisarfinPagina pie
	function cerrarCuadro(){
		//si noes inicio termina el cuardro anterior
		$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15);
		$this->tablealigns=array('R','R','R','R','R','R','R','R','R','R');		
		$this->tablenumbers=array(0,0,0,0,0,0,0,0,0,0);
		$this->tableborders=array('T','T','T','T','LRTB','LRTB','LRTB','LRTB','LRTB','TR');
		$nombre =$this->objParam->getParametro('tipo_ret');		
		switch ($nombre) {
			case 'todo':
				$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15,15,15,15);
				$this->tablealigns=array('R','R','R','R','R','R','R','R','R','R','R','R','R','R');		
				$this->tablenumbers=array(0,0,0,0,0,0,0,0,0,0,0,0,0);
				$this->tableborders=array('T','T','T','T','LRTB','LRTB','LRTB','LRTB','LRTB', 'LRTB','LRTB','LRTB','TR');
				$RowArray = array(  's1' => '',
									's2' => '',
									's3' => '', 
									'espacio' => 'Subtotal',
									's4' => $this->s1,
									's5' => $this->s2,
									's6' => $this->s3,
									's7' => $this->s4,
									's8' => $this->s5,
									's9' => $this->s6,
									's10' => $this->s7,
									's11' => '',
									's12' => '',
									's13' => ''
								);
				break;
			case 'rcrb':
				$RowArray = array(  's1' => '',
									's2' => '',
									's3' => '', 
									'espacio' => 'Subtotal',
									's4' => $this->s1,
									's5' => $this->s2,
									's6' => $this->s3,
									's7' => $this->s8,
									's8' => $this->s9,
									's9' => $this->s10
								);
				break;
			case 'rcrs':
				$RowArray = array(  's1' => '',
									's2' => '',
									's3' => '', 
									'espacio' => 'Subtotal',
									's4' => $this->s1,
									's5' => $this->s4,
									's6' => $this->s5,
									's7' => $this->s8,
									's8' => $this->s9,
									's9' => $this->s10
								);
				break;
			case 'rcra':
				$RowArray = array(  's1' => '',
									's2' => '',
									's3' => '', 
									'espacio' => 'Subtotal',
									's4' => $this->s1,
									's5' => $this->s6,
									's6' => $this->s7,
									's7' => $this->s8,
									's8' => $this->s9,
									's9' => $this->s10
								);
				break;
			default:				
				break;
		}		
		$this-> MultiRow($RowArray,false,1);
		$this->s1 = 0;
		$this->s2 = 0;
		$this->s3 = 0;
		$this->s4 = 0;
		$this->s5 = 0;
		$this->s6 = 0;
		$this->s7 = 0;
		$this->s8 = 0;
		$this->s9 = 0;
	}
	//revisarfinPagina pie
	function cerrarCuadroTotal(){
		//si noes inicio termina el cuardro anterior
		$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15);
		$this->tablealigns=array('R','R','R','R','R','R','R','R','R','R');		
		$this->tablenumbers=array(0,0,0,0,0,0,0,0,0,0);
		$this->tableborders=array('','','','','LRTB','LRTB','LRTB','LRTB','LRTB','TRB');
		$nombre =$this->objParam->getParametro('tipo_ret');		
		switch ($nombre) {
			case 'todo':
				$conf_par_tablewidths=array(6,20,45,15,15,15,15,15,15,15,15,15,15);
				$this->tablealigns=array('R','R','R','R','R','R','R','R','R','R','R','R','R','R');		
				$this->tablenumbers=array(0,0,0,0,0,0,0,0,0,0,0,0,0);
				$this->tableborders=array('','','','','LRTB','LRTB','LRTB','LRTB','LRTB', 'LRTB','LRTB','LRTB','TRB');				
				$RowArray = array( 
							't1' => '',
							't2' => '',
							't3' => '', 
							'espacio' => 'TOTAL: ',
							't4' => $this->t1,
							't5' => '',
							't6' => '',
							't7' => '',
							't8' => '',
							't9' => '',
							't10' => '',
							't11' => $this->t8,
							't12' => $this->t9,
							't13' => $this->t10
						);
				break;
			case 'rcrb':
				$RowArray = array(  't1' => '',
									't2' => '',
									't3' => '', 
									'espacio' => 'TOTAL: ',
									't4' => $this->t1,
									't5' => '',
									't6' => '',
									't7' => $this->t8,
									't8' => $this->t9,
									't9' => $this->t10
								);
				break;
			case 'rcrs':
				$RowArray = array(  't1' => '',
									't2' => '',
									't3' => '', 
									'espacio' => 'TOTAL: ',
									't4' => $this->t1,
									't5' => '',
									't6' => '',
									't7' => $this->t8,
									't8' => $this->t9,
									't9' => $this->t10
								);
				break;
			case 'rcra':				
				$RowArray = array(  't1' => '',
									't2' => '',
									't3' => '', 
									'espacio' => 'TOTAL: ',
									't4' => $this->t1,
									't5' => '',
									't6' => '',
									't7' => $this->t8,
									't8' => $this->t9,
									't9' => $this->t10
								);
				break;
			default:
				break;
		}
		$this-> MultiRow($RowArray,false,1);
	}
}
?>