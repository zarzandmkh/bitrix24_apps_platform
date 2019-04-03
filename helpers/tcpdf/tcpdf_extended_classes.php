<?php 
  	define('PDF_PADDING_TOP', 20); 
    define('PDF_PADDING_RIGHT', 20);
    define('PDF_PADDING_LEFT', 20);
    define('PDF_PADDING_BOTTOM', 30);

/*******************************************************  classes to extend tcpdf 		************************************************///  

    class TCPDF_EXTENDED extends TCPDF
    {
        public function Header() {
            $this->SetMargins(0, 0, PDF_PADDING_RIGHT, true);
            $this->Image('upload/pdf-new-header.jpg', 0, 0, 30, 700, 'JPG', '', 'T', false, 300, 'L', false, false, 0, false, false, true);
            $this->SetMargins(PDF_PADDING_LEFT, PDF_PADDING_TOP, PDF_PADDING_RIGHT, true);
           
            if ($this->page == 1) {
                // $this->Image('upload/your_logo.jpg', 0, 7, 40, 0, 'JPG', '', 'T', true, 300, 'L', false, false, 0, false, false, false);

                $header = 
                '<p style="color:black;">
                    Your header goes here
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
            $footer = '<span style="color:#1F6373">Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().'</span>';
            $this->writeHTMLCell(100, 10, PDF_PADDING_RIGHT+67, 282, $footer);
            $this->SetMargins(PDF_PADDING_LEFT, PDF_PADDING_TOP, PDF_PADDING_RIGHT, true);
            $this->SetAutoPageBreak(true, PDF_PADDING_BOTTOM);
        }
    }   



 ?>