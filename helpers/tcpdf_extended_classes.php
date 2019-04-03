<?php 
    define('PDF_PADDING_TOP', 20); 
    define('PDF_PADDING_RIGHT', 20);
    define('PDF_PADDING_LEFT', 20);
    define('PDF_PADDING_BOTTOM', 30);

/*******************************************************   Классы для расширения tcpdf 		************************************************///  

	/************************ ДЛЯ МЕТОДА  html2UnibixPdf *************************************/  
    class TCPDF_EXTENDED extends TCPDF
    {
        public function Header() {
            $this->SetMargins(0, 0, PDF_PADDING_RIGHT, true);
            $this->Image('upload/pdf-new-header.jpg', 0, 0, 30, 700, 'JPG', '', 'T', false, 300, 'L', false, false, 0, false, false, true);
            $this->SetMargins(PDF_PADDING_LEFT, PDF_PADDING_TOP, PDF_PADDING_RIGHT, true);
           
            if ($this->page == 1) {
                $this->Image('upload/logo-unibix-white.jpg', 0, 7, 40, 0, 'JPG', '', 'T', true, 300, 'L', false, false, 0, false, false, false);

                $header = 
                '<p style="color:black;">
                    Студия «Unibix»<br>
                    E-mail: <a href="mailto:info@unibix.ru">info@unibix.ru</a><br>
                    Сайт: <a href="http://www.unibix.ru">www.unibix.ru</a><br>
                    +7 (4012) 77-67-80<br>
                    г. Калининград,<br>
                    ул. Гагарина 16г, офис 11
                </p>';
                $this->writeHTMLCell(100, 50, 143, 7, $header);
                $this->SetMargins(PDF_PADDING_LEFT, 50, PDF_PADDING_RIGHT, true);
            }
            else {
                
                $header =  '';
                $this->writeHTMLCell(0, 0, 10, 10, $header);
                $this->SetMargins(PDF_PADDING_LEFT, PDF_PADDING_TOP, PDF_PADDING_RIGHT, true);
            }
            
            
        }

        public function Footer() {
            $this->SetAutoPageBreak(true, 0);
            $this->SetMargins(0, 0, -1, true);
           // $this->Image('upload/pdf-footer.jpg', 0, 277, 0, PDF_PADDING_BOTTOM, 'JPG', '', '', false, 300, 'R', false, false, 0, false, false, true);
            $footer = '<span style="color:#1F6373">Страница '.$this->getAliasNumPage().' из '.$this->getAliasNbPages().'</span>';
            $this->writeHTMLCell(100, 10, PDF_PADDING_RIGHT+67, 282, $footer);
            $this->SetMargins(PDF_PADDING_LEFT, PDF_PADDING_TOP, PDF_PADDING_RIGHT, true);
            $this->SetAutoPageBreak(true, PDF_PADDING_BOTTOM);
        }
    }

    /************************ ДЛЯ ДОКУМЕНТОВ *************************************/
    // расширяем класс со своим подвалом, потом можно и шапку сделать
	class MYPDF extends TCPDF {

		// Page footer
		public function Footer() {
			global $doc_version;
			// Position at 15 mm from bottom
			$this->SetY(-12);
			// Set font
			$this->SetFont('dejavusans', 'I', 8);
			// Page number
			$this->Cell(0, 10, '___________ Страница '.$this->getAliasNumPage().' из '.$this->getAliasNbPages().' (версия документа '.$doc_version.') ___________', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}


 ?>