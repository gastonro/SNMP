<?php
function snmp_query($oid){ 
  $sysdesc = snmp3_real_walk('127.0.0.1', 'gaston', 'authPriv', 'MD5', '12345678', 'DES', '12345678', $oid);
  foreach($sysdesc as $key=>$value){ 
    $snmp[$key]=$value; 
  } 
  foreach($snmp as $key => $value){
    $varSnmp=explode("::",$key);
    $valueSnmp=explode(":",$value);
    $var=explode(".",$varSnmp[1]);
    $index=$var[1];
    $data_array[$var[0]][$index]=trim($valueSnmp[1]);
    }
    return $data_array; 
}
 
$dsk_array = snmp_query("dsk");
$ip_array = snmp_query("ipCidrRouteTable");
$ifname_array = snmp_query("ifName");
$tcpConnRemAddress_array = snmp_query("tcpConnRemAddress");
$tcpConnRemPort_array = snmp_query("tcpConnRemPort");
$tcpConnLocalAddress_array = snmp_query("tcpConnLocalAddress");
$tcpConnLocalPort_array = snmp_query("tcpConnLocalPort");
$tcpConnState_array = snmp_query("tcpConnState");
$udpLocalAddress_array = snmp_query("udpLocalAddress");
$udpLocalPort_array = snmp_query("udpLocalPort");

  #parse information to json
    $myFile = "version_3.json";
    $myArray = array_merge($dsk_array, $ip_array, $ifname_array, $tcpConnRemAddress_array, $tcpConnRemPort_array, $tcpConnLocalAddress_array, 
    $tcpConnLocalPort_array, $tcpConnState_array, $udpLocalAddress_array, $udpLocalPort_array);
    $jsondata = json_encode($myArray, JSON_PRETTY_PRINT);
    file_put_contents($myFile, $jsondata);

?> 

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th {
  text-align: left;
}
 table {
  border-spacing: 5px;    
}

</style>
<h1>SNMP - Version 3 - 127.0.0.1 - public</h1>
<hr/>
<h1>SNMP - DF -H</h1>
<hr/>
<br><br>
 <table style="width:100%">
  <tr>
    <th>FileSystem</th>
    <th>Size(B)</th>  
    <th>Used(B)</th>
    <th>Avail(B)</th>
    <th>Percent</th>
    <th>MounPoint</th>
  </tr>

  <?php  foreach($dsk_array['dskDevice'] as $disco => $value): ?>
  
        <tr>            
            <td><?php echo $dsk_array["dskDevice"][$disco] ?></td>
      <td><?php echo $dsk_array["dskTotal"][$disco] ?></td>
      <td><?php echo $dsk_array["dskUsed"][$disco] ?></td>
      <td><?php echo $dsk_array["dskAvail"][$disco] ?></td>
      <td><?php echo $dsk_array["dskPercent"][$disco] ?></td>
      <td><?php echo $dsk_array["dskPath"][$disco] ?></td>
        </tr>
    <?php endforeach;?>
</table> 

<h1>SNMP - IP -R</h1>
<hr/>
<br><br>
 <table style="width:100%">
  <tr>
    <th>Destination</th>
    <th>Interface</th>  
    <th>Protocol</th>
    <th>Next Hop</th>
    <th>Metric</th>
  </tr>

  <?php  foreach($ip_array['ipCidrRouteDest'] as $ip => $value): ?>
  
        <tr>            
            <td><?php echo $ip_array["ipCidrRouteDest"][$ip] ?></td>
      <td><?php echo $ifname_array['ifName'][(integer)$ip_array["ipCidrRouteIfIndex"][$ip]] ?></td>
      <td><?php echo $ip_array["ipCidrRouteProto"][$ip] ?></td>
      <td><?php echo $ip_array["ipCidrRouteNextHop"][$ip] ?></td>
      <td><?php echo $ip_array["ipCidrRouteMetric1"][$ip] ?></td> 
        </tr>
    <?php endforeach;?>
</table>

<h1>SNMP - SS PLUTAN</h1>
<hr/>
<br><br>
 <table style="width:100%">
  <tr>
    <th>NetID</th>
    <th>State</th>  
    <th>Local Address:Port</th>
    <th>Peer Address:Port</th>
  </tr>

  <?php  foreach($tcpConnLocalAddress_array['tcpConnLocalAddress'] as $tcp => $value): ?>
  
        <tr>            
            <td>tcp</td>
      <td><?php echo $tcpConnState_array['tcpConnState'][$tcp] ?></td>
      <td><?php echo $tcpConnLocalAddress_array['tcpConnLocalAddress'][$tcp] . ':' . $tcpConnLocalPort_array['tcpConnLocalPort'][$tcp] ?></td>
      <td><?php echo $tcpConnRemAddress_array['tcpConnRemAddress'][$tcp] . ':' . $tcpConnRemPort_array['tcpConnRemPort'][$tcp] ?></td>  
        </tr>
    <?php endforeach;?>

  <?php  foreach($udpLocalAddress_array['udpLocalAddress'] as $udp => $value): ?>
  
        <tr>            
            <td>udp</td>
      <td>unconn</td>
      <td><?php echo $udpLocalAddress_array['udpLocalAddress'][$udp] . ':' . $udpLocalPort_array['udpLocalPort'][$udp] ?></td>
      <td>0.0.0.0:*</td>  
        </tr>
    <?php endforeach;?>

</table>  
