<?php
/**
 * Created by PhpStorm.
 * User: yeti_
 * Date: 03-апр-19
 * Time: 4:11 AM
 */

class XmlLibParser
{
    private $inputFileName;
    private $chunkSize;
    private $fp = null;
    private $xml_parser;
    private $documentData = [];
    private $listTagName;
    private $itemTagName;
    private $itemTmp = [];
    private $currentTag = '';
    private $isItemSkipped = false;

    private $criteriaField = '';
    private $criteriaMin = 0;
    private $criteriaMax = 0;

    function __construct($inputFileName)
    {
        $this->inputFileName = $inputFileName;
        $this->chunkSize = 4096;
        $this->listTagName = 'users';
        $this->itemTagName = 'user';
        $this->criteriaField = 'age';
        $this->criteriaMin = 20;
        $this->criteriaMax = 30;

        $this->init();
    }

    private function init()
    {
        $this->xml_parser = xml_parser_create();
        xml_set_element_handler($this->xml_parser, [$this, "startElement"], [$this, "endElement"]);
        xml_set_character_data_handler($this->xml_parser, [$this, "elementData"]);
    }
    private function startElement($parser, $name, $attributes)
    {
        $this->currentTag = $name;

        if(!array_key_exists($this->listTagName, $this->documentData)){
            if(strcasecmp($this->listTagName, $name) === 0){
                $this->documentData[$this->listTagName] = [];
            }
            else {
                return;
            }
        }
        if(strcasecmp($this->itemTagName, $name) === 0){
            $this->itemTmp = [];
            $this->isItemSkipped = false;
        }
    }

    private function endElement($parser, $name)
    {
        if(strcasecmp($this->itemTagName, $name) === 0 && !$this->isItemSkipped){
            $this->documentData[$this->listTagName][] = $this->itemTmp;
        }
    }

    private function elementData($parser, $data)
    {
        if($this->isItemSkipped) return;

        $cleanData = trim($data);
        $this->filterItem($cleanData);
        if($cleanData && !$this->isItemSkipped) {
            $this->itemTmp[$this->currentTag] = $cleanData;
        }
    }

    private function filterItem($value)
    {
        if(empty($value)) return;

        $number = (int)$value;
        if(strcasecmp($this->currentTag, $this->criteriaField) === 0){
            $this->isItemSkipped = !($number >= $this->criteriaMin && $number <= $this->criteriaMax);
        }
    }

    public function processFile()
    {
        if (!($this->fp = fopen('./'.$this->inputFileName, 'rb'))) {
            die("Can't read XML file");
        }

        while ($data = fread($this->fp, $this->chunkSize)) {
            if (!xml_parse($this->xml_parser, $data, feof($this->fp))) {
                die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($this->xml_parser)),
                    xml_get_current_line_number($this->xml_parser)));
            }
        }
        xml_parser_free($this->xml_parser);

        return $this->documentData;
    }
}