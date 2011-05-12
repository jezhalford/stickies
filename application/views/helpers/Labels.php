<?php

class Zend_View_Helper_Labels extends Zend_View_Helper_Abstract {
    
    private $_pageSize;
    
    private $_labelSize;
    
    private $_margins;
    
    private $_columns;
    
    private $_padding;
    
    private $_fontSize;
    
    private $_cursors;
    
    /**
     * @var Zend_Pdf_Page
     */
    private $_page;
    
    private $_pdf;
    
    public function labels($spec, $data) {
        
        $this->_parseSpec($spec);
        
        $this->_pdf = new Zend_Pdf();
        
        $this->_page = new Zend_Pdf_Page($this->_pageSize);
        
        $this->_pdf->pages[] = $this->_page;
        
        $this->_setCursorPositions();
        
        $this->_page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA),
                $this->_fontSize);
        
        $this->_drawLabels();
        
        return $this->_pdf->render();
        
    }
    
    protected function _drawLabels() {
        
        
        foreach($this->_cursors as $cursor) {
            
            
            $this->_page->drawText('hello', $cursor['x'], $cursor['y']);
            
        }
        
        
        
    }
    
    /**
     * Sets up the view helper with info from the spec or suitable defaults.
     * 
     * @param array $spec 
     */
    private function _parseSpec($spec) {
        
        if(isset($spec['pageSize']) && $spec['pageSize'] == 'A4') {
           $this->_pageSize = Zend_Pdf_Page::SIZE_A4;
        }
        else {
            throw new Zend_View_Exception('No valid page size specified');
        }
        
        if(isset($spec['columns'])) {
           $this->_columns = $spec['columns'];
        }
        else {
            throw new Zend_View_Exception('No valid column count specified');
        }
        
        if(isset($spec['fontSize'])) {
           $this->_fontSize = $spec['fontSize'];
        }
        else {
            throw new Zend_View_Exception('No valid font size specified');
        }
        
        if(isset($spec['margins'])) {
            
            $this->_margins = array('top' => 0, 'bottom' => 0, 'left' => 0, 'right' => 0);
            
            foreach($spec['margins'] as $k => $v) {
                
                if($k == 'top' || $k == 'bottom' || $k == 'left' || $k == 'right') {
                    $this->_margins[$k] = $this->_mmToPts($v);
                }
            }
            
        }
        else {
            throw new Zend_View_Exception('No margins specified');
        }
        
        if(isset($spec['padding'])) {
            
            $this->_padding = array('horizontal' => 0, 'vertical' => 0);
            
            foreach($spec['padding'] as $k => $v) {
                if($k == 'horizontal' || $k == 'vertical') {
                    $this->_padding[$k] = $this->_mmToPts($v);
                }
            }
        }
        else {
            throw new Zend_View_Exception('No padding specified');
        }
        
        if(isset($spec['labels'])) {
            
            $this->_labelSize = array('height' => 0, 'width' => 0);
            
            foreach($spec['labels'] as $k => $v) {
                
                if($k == 'height' || $k == 'width') {
                    $this->_labelSize[$k] = $this->_mmToPts($v);
                }
            }
        }
        else {
            throw new Zend_View_Exception('No label size specified');
        }
        
    }
    
    /**
     * Populates the _cursors variable with starting x/y coordinates for each label on the page.
     */
    private function _setCursorPositions() {
        
        $this->_cursors = array();
        
        for($i = 0; $i < $this->_columns; $i++) {
            
            $x = 0;
            
            $y = 700;
            
            // we always need to add some left margin...
            $x += $this->_margins['left'];
            
            if($i > 0) {
                // if this isn't the first label in the row then we also need to add 
                // the horizontal padding (i.e. the gap between columns) plus the width of the
                // number of labels across we are...
                $x += $this->_padding['horizontal'] * ($i+1);
                
                $x += $this->_labelSize['width'] * $i;
                
            }
            
            $this->_cursors[] = array(
                'x' => $x,
                'y' => $y
            );
        }
        
//        echo '<pre>';
//            print_r($this->_cursors);
//        echo '</pre>';
        
    }
    
    private function _mmToPts($mm) {
        return $mm * 2.834645669;
    }
}

?>
