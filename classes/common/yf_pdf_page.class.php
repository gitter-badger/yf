<?php

/**
* PDF page view handler
*
* @package		YF
* @author		YFix Team <yfix.dev@gmail.com>
* @version		1.0
*/
class yf_pdf_page {

	var $PATH_TO_PDF = "uploads/pdf/";

	// bug fix: mPDF reset locale setting
	private $_LC_NUMERIC      = array();
	private $_LC_NUMERIC_MPDF = array();

	/**
	*/
	function __construct() {
		$path = "libs/mPDF/mpdf.php";
		if(file_exists(YF_PATH.$path)){
			require_once (YF_PATH.$path);
		}else{
			require_once (PROJECT_PATH.$path);
		}
		$this->_LC_NUMERIC = localeconv();
			// params:
			// coding, format, font size, font name
			// margin left margin right, margin top, margin bottom, margin head, margin footer
			$this->pdf_obj = new mPDF('utf-8', 'A4','10');
		$this->_LC_NUMERIC_MPDF = localeconv();
		setlocale( LC_NUMERIC, $this->_LC_NUMERIC );
		$this->PATH_TO_PDF = PROJECT_PATH.$this->PATH_TO_PDF;
	}

	/**
	 * Show given text as PDF
	 * I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
	 * D: send to the browser and force a file download with the name given by filename.
	 * F: save to a local file with the name given by filename (may include a path).
	 * S: return the document as a string. filename is ignored. You can use the 'S' option to e-mail a PDF file (as a content of email).
	 */
	function go ($text = "", $name = "", $dest = "I") {
		if($dest != "F"){
			main()->NO_GRAPHICS = true;
		}
		if (empty($name)) {
			$name = "page";
		}

		if($dest == "F"){
			_class('dir')->mkdir($this->PATH_TO_PDF);
			$name = $this->PATH_TO_PDF.$name;
		}
		setlocale( LC_NUMERIC, $this->_LC_NUMERIC_MPDF );
		$this->pdf_obj->charset_in = 'utf-8';
		$this->pdf_obj->WriteHTML($text, 2);
		$this->pdf_obj->Output($name.'.pdf', $dest);
		setlocale( LC_NUMERIC, $this->_LC_NUMERIC );
	}
}
