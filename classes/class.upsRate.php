<?php

class upsRate {
	var $requestXML;
	var $shipmentXML;
	var $shipperXML;
	var $shipToXML;	
	var $packageXML;	
	var $packageDimensionsXML;	
	var $packageWeightXML;	

	var $xmlSent;
	
	function upsRate($upsObj) {
		// Must pass the UPS object to this class for it to work
		$this->ups = $upsObj;
	}
	
	// Main function that puts together all the XML builder function variables.  Builds the final XML for Rate calculation
	function rate() {
		$xml = $this->ups->access();
		$content = $this->requestXML;


		$content .= $this->shipmentXML;
		
		$xml .= $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_Main.xml', array('{CONTENT}'), array($content));

		$this->xmlSent = $xml;

		$responseXML = $this->ups->request('Rate', $xml);
		$xmlParser = new XML2Array();
		$fromUPS = $xmlParser->parse($responseXML);
		return $fromUPS;
	}

	// Build Request XML
	function request($params) {
		if ($params['Shop']) {
			$request = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_Request.xml', array('{RATE_OPTION}'), array('Shop')); 
		} else {
			$request = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_Request.xml', array('{RATE_OPTION}'), array('Rate')); 
		}
		$this->requestXML = $request;
		return $request;
	}

	// Build the shipment XML
	function shipment($params) {
		$shipment = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_Shipment.xml', array('{SHIPMENT_DESCRIPTION}','{SHIPPING_CODE}','{SHIPMENT_CONTENT}'), array($params['description'],$params['serviceType'],$this->shipperXML. $this->shipToXML. $this->packageXML));
		
		$this->shipmentXML = $shipment;
		return $shipment;
	}

	// Build the shipper XML
	function shipper($params) {
		$shipper = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_Shipper.xml', array('{SHIPPER_NAME}',
			'{SHIPPER_PHONE}',
			'{SHIPPER_NUMBER}',
			'{SHIPPER_ADDRESS_1}',
			'{SHIPPER_ADDRESS_2}',
			'{SHIPPER_ADDRESS_3}',
			'{SHIPPER_CITY}',
			'{SHIPPER_STATE}',
			'{SHIPPER_POSTAL_CODE}',
			'{SHIPPER_COUNTRY_CODE}'), array($params['name'],$params['phone'],
										$params['shipperNumber'],$params['address1'],
										$params['address2'],$params['address3'],
										$params['city'],$params['state'],
										$params['postalCode'],$params['country']));
		$this->shipperXML = $shipper;
		return $shipper;
	}

	// Build the shipTo XML
	function shipTo($params) {
		$shipTo = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_ShipTo.xml', array('{SHIPTO_COMPANY_NAME}',
			'{SHIPTO_ATTN_NAME}',
			'{SHIPTO_PHONE}',
			'{SHIPTO_ADDRESS_1}',
			'{SHIPTO_ADDRESS_2}',
			'{SHIPTO_ADDRESS_3}',
			'{SHIPTO_CITY}',
			'{SHIPTO_STATE}',
			'{SHIPTO_POSTAL_CODE}',
			'{SHIPTO_COUNTRY_CODE}'), array($params['companyName'],$params['attentionName'],
										$params['phone'],$params['address1'],$params['address2'],
										$params['address3'],$params['city'],$params['state'],
										$params['postalCode'],$params['countryCode']));
		$this->shipToXML = $shipTo;
		return $shipTo;
	}

	// Build the package XML
	function package($params) {
		$package = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_Package.xml', array('{PACKAGE_DESCRIPTION}',
			'{PACKAGING_CODE}','{PACKAGE_SIZE}','{PACKAGE_EXTRAS}'), array($params['description'],$params['code'],$this->packageDimensions(array('length' => '5', 'width' => '5', 'height' => '5')). $this->packageWeight(array('weight' => '5')),''));

		$this->packageXML = $package;
		return $package;
	}

	// Build the packageDimensions XML
	function packageDimensions($params) {
		$packageDimensions = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_PackageDimensions.xml', array('{PACKAGE_LENGTH}',
			'{PACKAGE_WIDTH}',
			'{PACKAGE_HEIGHT}'), array($params['length'],$params['width'],$params['height']));

		$this->packageDimensionsXML = $packageDimensions;
		return $packageDimensions;
	}

	// Build packageWeight XML
	function packageWeight($params) {
		$packageWeight = $this->ups->sandwich($this->ups->templatePath.'Rates/RatingServiceSelection_PackageWeight.xml', array('{PACKAGE_WEIGHT}'), array($params['weight'])); 

		$this->packageWeightXML = $packageWeight;
		return $packageWeight;
	}

		
}
?>
