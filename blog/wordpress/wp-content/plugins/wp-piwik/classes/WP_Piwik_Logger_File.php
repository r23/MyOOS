<?php

	require_once('WP_Piwik_Logger.php');
	
	class WP_Piwik_Logger_File extends WP_Piwik_Logger {
	
		private $loggerFile = null;
	
		private function encodeFilename($fileName) {
			$fileName = str_replace (' ', '_', $fileName);
			preg_replace('/[^0-9^a-z^_^.]/', '', $fileName);
			return $fileName;
		}
		
		private function setFilename() {
			$this->loggerFile = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.
				date('Ymd').'_'.$this->encodeFilename($this->getName()).'.log';
		}
		
		private function getFilename() {
			return $this->loggerFile;
		}
		
		private function openFile() {
			if (!$this->loggerFile)
				$this->setFilename();
			return fopen($this->getFilename(), 'a');			
		}
		
		private function closeFile($fileHandle) {
			fclose($fileHandle);
		}
		
		private function writeFile($fileHandle, $fileContent) {
			fwrite($fileHandle, $fileContent."\n");
		}
		
		private function formatMicrotime($loggerTime) {
			return sprintf('[%6s sec]',number_format($loggerTime,3));
		}
		
		public function loggerOutput($loggerTime, $loggerMessage) {
			if ($fileHandle = $this->openFile()) {
				$this->writeFile($fileHandle, $this->formatMicrotime($loggerTime).' '.$loggerMessage);
				$this->closeFile($fileHandle);
			}
		}
    }