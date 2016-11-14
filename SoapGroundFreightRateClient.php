<?php

  //Configuration
$access = "9D193CF3301349FF";
$userid = "command";
$passwd = "#Pack3840";
$wsdl = "../../../SCHEMA-WSDLs/FreightRate.wsdl";
$operation = "ProcessFreightRate";
$endpointurl = 'https://wwwcie.ups.com/webservices/FreightRate';
$outputFileName = "XOLTResult.xml";

function processFreightRate(){
      //create soap request
  $option['RequestOption'] = 'RateChecking Option';
  $request['Request'] = $option;
  $shipfrom['Name'] = 'Good Incorporation';
  $addressFrom['AddressLine'] = '2010 WARSAW ROAD';
  $addressFrom['City'] = 'Roswell';
  $addressFrom['StateProvinceCode'] = 'GA';
  $addressFrom['PostalCode'] = '30076';
  $addressFrom['CountryCode'] = 'US';
  $shipfrom['Address'] = $addressFrom;
  $request['ShipFrom'] = $shipfrom;

  $shipto['Name'] = 'Sony Company Incorporation';
  $addressTo['AddressLine'] = '2311 YORK ROAD';
  $addressTo['City'] = 'TIMONIUM';
  $addressTo['StateProvinceCode'] = 'MD';
  $addressTo['PostalCode'] = '21093';
  $addressTo['CountryCode'] = 'US';
  $shipto['Address'] = $addressTo;
  $request['ShipTo'] = $shipto;

  $payer['Name'] = 'Payer inc';
  $addressPayer['AddressLine'] = '435 SOUTH STREET';
  $addressPayer['City'] = 'RIS TOWNSHIP';
  $addressPayer['StateProvinceCode'] = 'NJ';
  $addressPayer['PostalCode'] = '07960';
  $addressPayer['CountryCode'] = 'US';
  $payer['Address'] = $addressPayer;
  $shipmentbillingoption['Code'] = '10';
  $shipmentbillingoption['Description'] = 'PREPAID';
  $paymentinformation['Payer'] = $payer;
  $paymentinformation['ShipmentBillingOption'] = $shipmentbillingoption;
  $request['PaymentInformation'] = $paymentinformation;

  $service['Code'] = '308';
  $service['Description'] = 'UPS Freight LTL';
  $request['Service'] = $service;

  $handlingunitone['Quantity'] = '20';
  $handlingunitone['Type'] = array(
    'Code' => 'PLT',
    'Description' => 'PALLET');

  $request['HandlingUnitOne'] = $handlingunitone;

  $commodity['CommodityID'] = '';
  $commodity['Description'] = 'No Description';
  $commodity['Weight'] = array(
   'UnitOfMeasurement' => 
   array(
    'Code' => 'LBS', 
    'Description' => 'Pounds'),
   'Value' => '5000');

  $commodity['Dimensions'] = 
  array('UnitOfMeasurement' => 
    array(
      'Code' => 'IN',
      'Description' => 'Inches'
      ),
    'Length' => '23',
    'Width' => '17',
    'Height' => '45'
    );
  $commodity['NumberOfPieces'] = '45';
  $commodity['PackagingType'] =
  array(
   'Code' => 'BAG',
   'Description' => 'BAG'
   );
  $commodity['DangerousGoodsIndicator'] = '';
  $commodity['CommodityValue'] = 
  array(
   'CurrencyCode' => 'USD',
   'MonetaryValue' => '5670'
   );
  $commodity['FreightClass'] = '60';
  $commodity['NMFCCommodityCode'] = '';
  $request['Commodity'] = $commodity;

  $shipmentserviceoptions['PickupOptions'] = 
  array(
    'HolidayPickupIndicator' => '',
    'InsidePickupIndicator' => '',
    'ResidentialPickupIndicator' => '',
    'WeekendPickupIndicator' => '',
    'LiftGateRequiredIndicator' => ''
    );
  


  echo "Request.......\n";
  echo "<br />";
  // var_dump($request);die();

  // print_r($request);
  echo "\n\n";
  return $request;
}

try
{

  $mode = array(
     'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
     'trace' => 1
     );

    // initialize soap client
  $client = new SoapClient($wsdl , array("trace" => 1, "exception" => 0));

  	//set endpoint url
  $client->__setLocation($endpointurl);


    //create soap header
  $usernameToken['Username'] = $userid;
  $usernameToken['Password'] = $passwd;
  $serviceAccessLicense['AccessLicenseNumber'] = $access;
  $upss['UsernameToken'] = $usernameToken;
  $upss['ServiceAccessToken'] = $serviceAccessLicense;

  $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);

  $client->__setSoapHeaders($header);


    //get response

  $resp = $client->__soapCall($operation , array(processFreightRate()));

    echo $resp->Commodity->Weight->Value;
    echo "<br>";
    echo $resp->TotalShipmentCharge->MonetaryValue;




//  $fileName = 'response.json';
//  $handle = fopen($fileName, 'w');
//  $result = fwrite($handle, "\xEF\xBB\xBF");
//  $result = fwrite($handle, $resp);
//  fclose($handle);
// die();
 // echo gettype($resp->Response), "\n";

 //get status
 echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";
 //save soap request and response to file
 $fw = fopen($outputFileName , 'w');
 fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
 fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
 fclose($fw);

}
catch(Exception $ex){
print_r ($ex);
}

?>
