<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'third_party/PhpWord/vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class WordProcessor
{
    public function processWordDocument($wordFile)
    {
        // Disable caching to reduce memory usage
        Settings::setOutputEscapingEnabled(true);

        $phpWord = IOFactory::load($wordFile);

        // return $phpWord;

        // $xmlReader = IOFactory::createReader('Word2007');
        // $xmlReader->setReadDataOnly(true);
        // $xmlReader->open($wordFile);

        // $phpWord = new \PhpOffice\PhpWord\PhpWord();

        while ($xmlReader->read()) {
            if ($xmlReader->nodeType === XMLReader::ELEMENT && $xmlReader->name === 'w:p') {
                $xmlReader->read();
                $paragraphXml = $xmlReader->readOuterXml();
                $section = $phpWord->addSection();
                $section->addText($paragraphXml);
            }
        }

        $xmlReader->close();

        return $phpWord;
    }
}
